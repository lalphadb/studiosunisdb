#!/usr/bin/env bash
set -Eeuo pipefail

PROJECT_ROOT="/home/studiosdb/studiosunisdb"
cd "$PROJECT_ROOT"

TS="$(date +'%Y%m%d_%H%M%S')"
REPORT="docs/AUDIT_CLI_REPORT_${TS}.txt"

sep () { printf '\n%s\n' "================================================================================" >> "$REPORT"; }
hdr () { 
  sep
  printf '%s\n' "# $1" >> "$REPORT"
  printf '## Horodatage: %s\n\n' "$(date -Is)" >> "$REPORT"
}
run () {
  local title="$1"; shift
  local cmd="$*"
  hdr "$title"
  printf 'Commande: %s\n\n' "$cmd" >> "$REPORT"
  # On n’échoue jamais le script sur l’échec d’une commande d’audit — on log l’erreur
  {
    eval "$cmd"
  } >> "$REPORT" 2>&1 || {
    printf '\n[!] ÉCHEC (code %s) pour: %s\n' "$?" "$cmd" >> "$REPORT"
  }
}

# En-tête rapport
{
  echo "# Rapport d’audit CLI — StudiosDB"
  echo "Date de génération: $(date -Is)"
  echo "Projet: $PROJECT_ROOT"
  echo
  echo "## Contexte Système"
  echo "\$ uname -a"; uname -a || true
  echo "\$ php -v"; php -v || true
  echo "\$ composer -V"; composer -V || true
  echo "\$ node -v"; node -v || true
  echo "\$ npm -v"; npm -v || true
  echo "\$ git rev-parse --short HEAD"; git rev-parse --short HEAD || true
} > "$REPORT" 2>&1

# 1) Inventaire routes/permissions
run "Inventaire des routes (artisan route:list -v)" \
  php artisan route:list -v

run "Permissions Spatie (permission:show)" \
  "php artisan permission:show | sed -n '1,200p' || true"

# 2) Dépendances & vulnérabilités
run "Version du framework Laravel (composer show laravel/framework)" \
  composer show laravel/framework

run "Audit dépendances PHP (composer audit)" \
  "composer audit || true"

run "Audit dépendances JS (npm audit --audit-level=high)" \
  "npm audit --audit-level=high || true"

# 3) Qualité & style
run "Laravel Pint (./vendor/bin/pint -v --test)" \
  "./vendor/bin/pint -v --test || true"

run "PHPStan (analyse, limite mémoire 1G)" \
  "./vendor/bin/phpstan analyse --memory-limit=1G || true"

run "Tests unitaires (php artisan test --testsuite=Unit)" \
  "php artisan test --testsuite=Unit || true"

# 4) Migrations & schéma
run "État des migrations (php artisan migrate:status)" \
  php artisan migrate:status

run "InnoDB Status (mysql -e 'SHOW ENGINE INNODB STATUS\\G')" \
  "mysql -e \"SHOW ENGINE INNODB STATUS\\G\" || true"

# 5) Telescope (recherche configuration/usage)"
run "Recherche usages Telescope (grep -R 'Telescope::' vendor/ app/)" \
  "grep -R \"Telescope::\" -n vendor/ app/ || true"

# 6) Patterns risqués (Front & Back)
run "Recherche v-html (XSS potentiel) dans resources/js" \
  "grep -R \"v-html\" resources/js --line-number || true"

run "Recherche DB raw() dans app/ (SQL brut)" \
  "grep -R \"->raw\\(\" app/ --line-number || true"

run "Fonctions dangereuses (unserialize, shell_exec, proc_open)" \
  "grep -R \"unserialize\\(|shell_exec\\(|proc_open\\(\" -n app/ || true"

# Pied de rapport
{
  sep
  echo "# Fin du rapport"
  echo "Fichier généré: ${REPORT}"
  echo "Durée totale ~ jusqu’à $(date -Is)"
} >> "$REPORT"

# Affiche le chemin du rapport
echo "$REPORT"
