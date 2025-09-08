#!/usr/bin/env bash
set -Eeuo pipefail

ROOT="/home/studiosdb/studiosunisdb"
cd "$ROOT"
TS="$(date +'%Y%m%d_%H%M%S')"
REPORT="docs/SAFE_UPDATE_REPORT_${TS}.txt"

log(){ printf '%s\n' "$*" | tee -a "$REPORT" ; }
hdr(){ printf '\n================================================================================\n# %s\nGenerated: %s\n\n' "$1" "$(date -Is)" | tee -a "$REPORT" ; }

# En-tête
echo "# Safe Update Report" > "$REPORT"
log "Project: $ROOT"
log "Git: $(git rev-parse --abbrev-ref HEAD 2>/dev/null || echo 'n/a') @ $(git rev-parse --short HEAD 2>/dev/null || echo 'n/a')"
log "PHP: $(php -v | head -1)"
log "Composer: $(composer -V)"
log "Node: $(node -v 2>/dev/null || echo 'n/a') | NPM: $(npm -v 2>/dev/null || echo 'n/a')"

# 1) Outdated overview
hdr "Outdated (direct)"
composer outdated --direct | tee -a "$REPORT" || true

hdr "Outdated (Laravel ecosystem)"
composer outdated "laravel/*" spatie/* nunomaduro/* | tee -a "$REPORT" || true

# 2) Liste candidates (laravel/* + direct)
CANDIDATES=$( (composer outdated "laravel/*" | awk '{print $1}'; composer outdated --direct | awk '{print $1}') | sort -u )

hdr "Dry-run per package (with-all-dependencies, no-scripts)"
if [[ -z "$CANDIDATES" ]]; then
  log "No candidates found."
else
  for pkg in $CANDIDATES; do
    log ">>> $pkg"
    set +e
    composer update "$pkg" --with-all-dependencies --no-scripts --dry-run >> "$REPORT" 2>&1
    CODE=$?
    set -e
    if [[ $CODE -ne 0 ]] || grep -qiE "Your requirements could not be resolved|conflict" <(tail -n 50 "$REPORT"); then
      log "[!] $pkg : CONFLIT ou ECHEC dry-run"
      # Aide au diagnostic
      log "why-not $pkg (dernière version connue):"
      VER=$(composer show "$pkg" | awk '/latest/ {print $2}' | tail -1)
      composer why-not "$pkg" ${VER:-""} | tee -a "$REPORT" || true
    else
      log "[OK] $pkg : dry-run passe"
    fi
    log ""
  done
fi

# 3) Batterie de checks (sans rien modifier)
hdr "Quality gates (current lockfile)"
set +e
./vendor/bin/pint -v --test | tee -a "$REPORT"
./vendor/bin/phpstan analyse --memory-limit=1G | tee -a "$REPORT"
php artisan test --testsuite=Unit | tee -a "$REPORT"
php artisan route:list -v | tee -a "$REPORT"
php artisan migrate:status | tee -a "$REPORT"
php artisan migrate --pretend | tee -a "$REPORT"
set -e

# 4) Conseils de validation
hdr "Decision matrix & next steps"
cat <<'TXT' | tee -a "$REPORT"
Pour chaque paquet:
- [OK dry-run] + [tests passent] -> Eligible MAJ (lot P1)
- [OK dry-run] + [tests cassent] -> Vérifier changelog, patch minimal, retenter
- [Conflit dry-run] -> composer why-not/prohibits; détendre contraintes d'une dépendance ou attendre
- Toujours merger via PR/branch avec:
  - composer update <paquet> --with-all-dependencies
  - php artisan config:clear && route:clear && view:clear
  - php artisan migrate --pretend (puis en vraie si validé)
  - test suite + smoke (route:list, pages clés, login)
TXT

log "Report path: $REPORT"
echo "$REPORT"
