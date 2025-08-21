#!/usr/bin/env bash
set -euo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$ROOT"

BACKUP_DIR="backups/cleanup_$(date +%F_%H%M%S)"
mkdir -p "$BACKUP_DIR"

say(){ printf "%b\n" "$*"; }

# 1) Cartographie des artefacts EN possibles
declare -A FILES_EN=(
  ["app/Models/Member.php"]="App\\Models\\Member"
  ["app/Models/Course.php"]="App\\Models\\Course"
  ["app/Models/Attendance.php"]="App\\Models\\Attendance"
  ["app/Models/Payment.php"]="App\\Models\\Payment"
  ["app/Models/Belt.php"]="App\\Models\\Belt"

  ["app/Policies/MemberPolicy.php"]="App\\Policies\\MemberPolicy"
  ["app/Http/Requests/MemberStoreRequest.php"]="App\\Http\\Requests\\MemberStoreRequest"
  ["app/Http/Requests/MemberUpdateRequest.php"]="App\\Http\\Requests\\MemberUpdateRequest"

  ["app/Http/Controllers/MemberController.php"]="App\\Http\\Controllers\\MemberController"
)

MISSING_COUNT=0
REMOVED=()

say "== Vérification des références (EN) avant suppression =="
for f in "${!FILES_EN[@]}"; do
  sym="${FILES_EN[$f]}"
  if [[ -f "$f" ]]; then
    # Cherche une référence au FQCN EN
    if rg -n "$sym\\b" app resources routes 2>/dev/null | head -n1 >/dev/null; then
      say "⚠️  Références trouvées pour $sym — je NE supprime pas $f"
      continue
    fi
    # Pas de références -> on sauvegarde et on retire
    mkdir -p "$(dirname "$BACKUP_DIR/$f")"
    cp -a "$f" "$BACKUP_DIR/$f"
    rm -f "$f"
    REMOVED+=("$f")
    say "🧹 Supprimé (backup créé) : $f"
  else
    MISSING_COUNT=$((MISSING_COUNT+1))
  fi
done

# 2) Nettoyage des .old/.bak résiduels
say "== Nettoyage fichiers .old / .bak (controllers/requests) =="
find app -type f \( -name "*.old" -o -name "*.bak" -o -name "*.back" \) | while read -r f; do
  mkdir -p "$(dirname "$BACKUP_DIR/$f")"
  cp -a "$f" "$BACKUP_DIR/$f"
  rm -f "$f"
  say "🧽 Archivé & supprimé : $f"
done

# 3) AuthServiceProvider : mapping unique MembrePolicy
ASP="app/Providers/AuthServiceProvider.php"
if [[ -f "$ASP" ]]; then
  # Retire MemberPolicy si présent
  sed -i "/MemberPolicy::class/d" "$ASP"
  # Assure mapping MembrePolicy -> Membre
  if ! rg -n "Membre::class\s*=>\s*MembrePolicy::class" "$ASP" >/dev/null 2>&1; then
    sed -i "/protected \$policies = \[/a \        \\App\\Models\\Membre::class => \\App\\Policies\\MembrePolicy::class," "$ASP"
    say "🔧 Mapping MembrePolicy ajouté dans AuthServiceProvider."
  else
    say "✅ Mapping MembrePolicy déjà présent."
  fi
fi

# 4) Composer dump + caches
say "== Composer autoload & caches =="
composer dump-autoload -o >/dev/null
php artisan optimize:clear >/dev/null || true

say ""
say "== Récapitulatif =="
if ((${#REMOVED[@]})); then
  printf "Supprimés (%d):\n" "${#REMOVED[@]}"
  printf " - %s\n" "${REMOVED[@]}"
  say "Backup: $BACKUP_DIR"
else
  say "Aucun fichier EN retiré (soit déjà absent, soit encore référencé)."
fi

say "Terminé."
