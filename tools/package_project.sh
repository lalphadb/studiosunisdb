#!/usr/bin/env bash
set -euo pipefail

# ==========================
# StudiosDB - Packager PRO
# ==========================
# Usage:
#   bash tools/package_project.sh                # dump DB + pack code (exclusions par défaut)
#   bash tools/package_project.sh --no-db        # pack sans dump DB
#   bash tools/package_project.sh --mask         # anonymise emails/téléphones dans le dump
#   bash tools/package_project.sh --include-logs # inclut activity_log/sessions/jobs
#   bash tools/package_project.sh --with-git     # inclut le dossier .git/
#
# Sortie:
#   dist/studiosdb_YYYYMMDD_HHMMSS.tar.gz
#
# Prérequis:
#   - mysqldump, mysql
#   - php artisan OK dans le projet
#   - gzip (ou pigz si dispo)

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$ROOT"

NOW="$(date +%F_%H%M%S)"
DIST_DIR="$ROOT/dist"
TMP_DIR="$ROOT/tmp/package_$NOW"
OUT="$DIST_DIR/studiosdb_$NOW.tar.gz"
EXCLUDES_FILE="$TMP_DIR/excludes.txt"
MANIFEST_DIR="$TMP_DIR/manifest"
DB_DUMP_RAW="$TMP_DIR/db.sql"
DB_DUMP_GZ="$TMP_DIR/db.sql.gz"

WITH_DB=1
WITH_MASK=0
WITH_LOGS=0
WITH_GIT=0

for arg in "${@:-}"; do
  case "$arg" in
    --no-db) WITH_DB=0 ;;
    --mask) WITH_MASK=1 ;;
    --include-logs) WITH_LOGS=1 ;;
    --with-git) WITH_GIT=1 ;;
    *) echo "Unknown option: $arg" >&2; exit 2 ;;
  esac
done

mkdir -p "$DIST_DIR" "$TMP_DIR" "$MANIFEST_DIR"

say(){ printf "%b\n" "$*"; }

# --------------------------
# 1) Charger la config .env
# --------------------------
get_env() {
  local key="$1"
  # prend la dernière occurrence, enlève quotes
  grep -E "^${key}=" .env 2>/dev/null | tail -n1 | sed -E "s/^${key}=['\"]?([^'\"\r\n]+).*/\1/"
}

APP_NAME="$(get_env APP_NAME || echo StudiosDB)"
APP_ENV="$(get_env APP_ENV || echo local)"
APP_URL="$(get_env APP_URL || echo http://127.0.0.1:8000)"

DB_HOST="$(get_env DB_HOST || echo 127.0.0.1)"
DB_PORT="$(get_env DB_PORT || echo 3306)"
DB_DATABASE="$(get_env DB_DATABASE || true)"
DB_USERNAME="$(get_env DB_USERNAME || true)"
DB_PASSWORD="$(get_env DB_PASSWORD || true)"

if [[ $WITH_DB -eq 1 ]]; then
  : "${DB_DATABASE:?DB_DATABASE manquant dans .env}"
  : "${DB_USERNAME:?DB_USERNAME manquant dans .env}"
  : "${DB_PASSWORD:?DB_PASSWORD manquant dans .env}"
fi

# ---------------------------
# 2) Manifest d’environnement
# ---------------------------
say "📄 Génération du manifest…"
{
  echo "=== StudiosDB Package Manifest ==="
  echo "Date: $NOW"
  echo "APP_NAME=$APP_NAME"
  echo "APP_ENV=$APP_ENV"
  echo "APP_URL=$APP_URL"
  echo
  echo "PHP/Laravel:"
  php -v 2>&1 | head -n1 || true
  php artisan --version 2>/dev/null || true
  echo
  echo "laravel about:"
  php artisan about 2>/dev/null || true
} > "$MANIFEST_DIR/about.txt" || true

php artisan route:list > "$MANIFEST_DIR/routes.txt" 2>/dev/null || true
php artisan migrate:status > "$MANIFEST_DIR/migrations.txt" 2>/dev/null || true

# --------------------------
# 3) Dump MySQL (optionnel)
# --------------------------
if [[ $WITH_DB -eq 1 ]]; then
  say "🗄️  Dump MySQL de '$DB_DATABASE' (host=$DB_HOST)…"
  IGNORE_TABLES=(
    "cache"
    "sessions"
    "jobs" "failed_jobs"
    "telescope_entries" "telescope_entries_tags" "telescope_entries_tagged"
    "password_reset_tokens" "personal_access_tokens"
  )

  if [[ $WITH_LOGS -eq 0 ]]; then
    IGNORE_TABLES+=("activity_log" "activity_logs")
  fi

  IGNORE_ARGS=()
  for t in "${IGNORE_TABLES[@]}"; do
    IGNORE_ARGS+=( "--ignore-table=${DB_DATABASE}.${t}" )
  done

  mysqldump \
    --single-transaction --quick --routines --triggers --events \
    --set-gtid-purged=OFF \
    -h "$DB_HOST" -P "$DB_PORT" -u "$DB_USERNAME" -p"$DB_PASSWORD" \
    "${IGNORE_ARGS[@]}" \
    "$DB_DATABASE" > "$DB_DUMP_RAW"

  # Anonymisation légère si demandé
  if [[ $WITH_MASK -eq 1 ]]; then
    say "🕵️  Anonymisation (emails/téléphones)…"
    # Masque emails & numéros (basique, non destructif du SQL)
    # - Emails: user@example.com -> user+mask@redacted.local
    # - Téléphones: >6 chiffres -> remplacés par 'X'
    perl -0777 -pe '
      s/([[:alnum:]._%+-]+)@([[:alnum:].-]+\.[A-Za-z]{2,})/${1}.mask@redacted.local/g;
      s/(\D)\d{7,}(\D)/$1XXXXXXXX$2/g;
    ' "$DB_DUMP_RAW" > "$DB_DUMP_RAW.masked"
    mv -f "$DB_DUMP_RAW.masked" "$DB_DUMP_RAW"
  fi

  if command -v pigz >/dev/null 2>&1; then
    pigz -9 -f "$DB_DUMP_RAW"
  else
    gzip -9 -f "$DB_DUMP_RAW"
  fi
  DB_DUMP_GZ="$DB_DUMP_RAW.gz"
  say "✅ Dump DB: $DB_DUMP_GZ"
else
  say "⏭️  Skip dump DB (--no-db)"
fi

# ---------------------------------
# 4) Préparer .env.sample (sanité)
# ---------------------------------
say "🧪 Création .env.sample (secrets vidés)…"
ENV_SAMPLE="$TMP_DIR/.env.sample"
if [[ -f .env ]]; then
  awk '
    BEGIN{FS=OFS="="}
    /^APP_KEY=/{$2="";print;next}
    /^DB_PASSWORD=/{print "DB_PASSWORD="; next}
    /^MAIL_PASSWORD=/{print "MAIL_PASSWORD="; next}
    /^REDIS_PASSWORD=/{print "REDIS_PASSWORD="; next}
    /^AWS_ACCESS_KEY_ID=/{print "AWS_ACCESS_KEY_ID="; next}
    /^AWS_SECRET_ACCESS_KEY=/{print "AWS_SECRET_ACCESS_KEY="; next}
    {print}
  ' .env > "$ENV_SAMPLE"
fi

# ---------------------------------
# 5) Exclusions & empaquetage
# ---------------------------------
say "🧰 Préparation des exclusions…"
cat > "$EXCLUDES_FILE" <<'XEOF'
# lourds/éphémères
vendor
node_modules
storage
public/build
bootstrap/cache

# VCS & IDE
.git
.idea
coverage

# fichiers temporaires/logs/archives
*.log
*.tmp
*.swp
*.zip
*.tar
*.gz
*.rar

# backups volumineux
backups
tmp
dist

# artefacts spécifiques du projet (archives internes)
studiosdb_incomplete_*
members_backup_*
membres_backup_*
membres_20*
XEOF

# Autorise .git si demandé
if [[ $WITH_GIT -eq 1 ]]; then
  sed -i '/^\.git$/d' "$EXCLUDES_FILE"
fi

say "📦 Création de l’archive…"
# On regroupe dans une arbo propre à l’intérieur du tar
PKG_ROOT="studiosdb_package_$NOW"
mkdir -p "$TMP_DIR/$PKG_ROOT"

# Copie des manifests et du dump dans le package
mkdir -p "$TMP_DIR/$PKG_ROOT/_manifest"
cp -a "$MANIFEST_DIR/." "$TMP_DIR/$PKG_ROOT/_manifest/"

if [[ -f "$DB_DUMP_GZ" ]]; then
  mkdir -p "$TMP_DIR/$PKG_ROOT/_db"
  cp -a "$DB_DUMP_GZ" "$TMP_DIR/$PKG_ROOT/_db/"
fi
[[ -f "$ENV_SAMPLE" ]] && cp -a "$ENV_SAMPLE" "$TMP_DIR/$PKG_ROOT/.env.sample"

# Tar principal: code source (avec exclusions) + ajout du répertoire _db/_manifest préparé
mkdir -p "$DIST_DIR"
# 1) archive du projet (racine)
tar -czf "$OUT" \
  -X "$EXCLUDES_FILE" \
  --transform "s|^|$PKG_ROOT/|S" \
  $(ls -A | grep -vE "^dist$|^tmp$") 2>/dev/null || true

# 2) append des _manifest & _db au tar existant
tar -rzf "$OUT" -C "$TMP_DIR" "$PKG_ROOT/_manifest" 2>/dev/null || true
if [[ -d "$TMP_DIR/$PKG_ROOT/_db" ]]; then
  tar -rzf "$OUT" -C "$TMP_DIR" "$PKG_ROOT/_db" 2>/dev/null || true
fi

# ---------------------------------
# 6) Checksums & résumé
# ---------------------------------
SIZE="$(du -h "$OUT" | awk '{print $1}')"
SHA="$(sha256sum "$OUT" | awk '{print $1}')"

say ""
say "✅ Archive prête : $OUT"
say "   Taille : $SIZE"
say "   SHA256 : $SHA"
say ""
say "Contenu clé dans le tar :"
say "  $PKG_ROOT/_manifest/about.txt"
say "  $PKG_ROOT/_manifest/routes.txt"
say "  $PKG_ROOT/_manifest/migrations.txt"
[[ -f "$DB_DUMP_GZ" ]] && say "  $PKG_ROOT/_db/$(basename "$DB_DUMP_GZ")"
say ""
say "Vérification rapide :"
say "  tar -tzf \"$OUT\" | head -n 30"
say "  echo \"$SHA  $OUT\" | sha256sum -c -"
