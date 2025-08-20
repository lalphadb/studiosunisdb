#!/usr/bin/env bash
set -euo pipefail

# StudiosDB – Backup FULL (code + DB)
# Usage:
#   bash tools/backup_project_full.sh            # dump DB + pack code (inclut public/)
#   bash tools/backup_project_full.sh --no-db    # sans dump
#   bash tools/backup_project_full.sh --mask     # anonymiser emails/téléphones dans le dump
#   bash tools/backup_project_full.sh --exclude-public  # exclure public/
#
# Sortie: dist/studiosdb_full_YYYYMMDD_HHMMSS.tar.gz

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"; cd "$ROOT"
NOW="$(date +%F_%H%M%S)"
DIST="$ROOT/dist"; TMP="$ROOT/tmp/backup_$NOW"
PKG_ROOT="studiosdb_full_$NOW"
OUT="$DIST/${PKG_ROOT}.tar.gz"
EX="$TMP/excludes.txt"; MANIFEST="$TMP/_manifest"; DB_DIR="$TMP/_db"

WITH_DB=1; WITH_MASK=0; EXCLUDE_PUBLIC=0
for arg in "${@:-}"; do
  case "$arg" in
    --no-db) WITH_DB=0 ;;
    --mask) WITH_MASK=1 ;;
    --exclude-public) EXCLUDE_PUBLIC=1 ;;
    *) echo "Unknown option: $arg" >&2; exit 2 ;;
  esac
done

mkdir -p "$DIST" "$TMP" "$MANIFEST"
say(){ printf "%b\n" "$*"; }

# --- .env ---
get_env(){ grep -E "^$1=" .env 2>/dev/null | tail -n1 | sed -E "s/^$1=['\"]?([^'\"\r\n]+).*/\1/"; }
APP_NAME="$(get_env APP_NAME || echo StudiosDB)"
DB_HOST="$(get_env DB_HOST || echo 127.0.0.1)"
DB_PORT="$(get_env DB_PORT || echo 3306)"
DB_DATABASE="$(get_env DB_DATABASE || true)"
DB_USERNAME="$(get_env DB_USERNAME || true)"
DB_PASSWORD="$(get_env DB_PASSWORD || true)"

# --- Manifest (about, routes, migrations) ---
say "📄 Manifest…"
{
  echo "=== StudiosDB FULL Backup Manifest ==="
  echo "Date: $NOW"
  echo "APP_NAME=$APP_NAME"
  php -v 2>&1 | head -n1 || true
  php artisan --version 2>/dev/null || true
  echo
  php artisan about 2>/dev/null || true
} > "$MANIFEST/about.txt" || true
php artisan route:list > "$MANIFEST/routes.txt" 2>/dev/null || true
php artisan migrate:status > "$MANIFEST/migrations.txt" 2>/dev/null || true

# --- Dump DB (optionnel) ---
if [[ $WITH_DB -eq 1 ]]; then
  : "${DB_DATABASE:?DB_DATABASE manquant dans .env}"
  : "${DB_USERNAME:?DB_USERNAME manquant dans .env}"
  : "${DB_PASSWORD:?DB_PASSWORD manquant dans .env}"
  mkdir -p "$DB_DIR"
  RAW="$DB_DIR/${DB_DATABASE}.sql"

  say "🗄️  Dump MySQL '${DB_DATABASE}'…"
  IGNORE=( cache jobs failed_jobs sessions telescope_entries telescope_entries_tags telescope_entries_tagged password_reset_tokens personal_access_tokens )
  ARGS=(); for t in "${IGNORE[@]}"; do ARGS+=("--ignore-table=${DB_DATABASE}.${t}"); done

  mysqldump --single-transaction --quick --routines --triggers --events \
    --set-gtid-purged=OFF \
    -h "$DB_HOST" -P "$DB_PORT" -u "$DB_USERNAME" -p"$DB_PASSWORD" \
    "${ARGS[@]}" "$DB_DATABASE" > "$RAW"

  if [[ $WITH_MASK -eq 1 ]]; then
    say "🕵️  Anonymisation emails/téléphones…"
    perl -0777 -pe 's/([[:alnum:]._%+-]+)@([[:alnum:].-]+\.[A-Za-z]{2,})/${1}.mask@redacted.local/g; s/(\D)\d{7,}(\D)/$1XXXXXXXX$2/g;' "$RAW" > "$RAW.mask" && mv -f "$RAW.mask" "$RAW"
  fi

  if command -v pigz >/dev/null 2>&1; then pigz -9 -f "$RAW"; else gzip -9 -f "$RAW"; fi
  say "✅ Dump DB: ${RAW}.gz"
else
  say "⏭️  Skip dump DB (--no-db)"
fi

# --- Exclusions ---
cat > "$EX" <<'EOF'
vendor
node_modules
storage
bootstrap/cache
*.backup
*.log
*.bak
*.tmp
*.swp
*.zip
*.tar
*.gz
*.rar
composer.lock
package-lock.json
packagelock.json
yarn.lock
.idea
coverage
maintenance
migrations_backup
A_consrver
EOF
# Exclure public/ si demandé
if [[ $EXCLUDE_PUBLIC -eq 1 ]]; then echo "public" >> "$EX"; fi

# --- Création TAR ---
say "📦 Création archive…"
if command -v pigz >/dev/null 2>&1; then
  tar -cf - -X "$EX" . "$MANIFEST" ${WITH_DB:+ "$DB_DIR"} | pigz -9 > "$OUT"
else
  tar -czf "$OUT" -X "$EX" . "$MANIFEST" ${WITH_DB:+ "$DB_DIR"}
fi

SIZE="$(du -h "$OUT" | awk '{print $1}')"
SHA="$(sha256sum "$OUT" | awk '{print $1}')"

echo
echo "✅ Archive prête : $OUT"
echo "   Taille : $SIZE"
echo "   SHA256 : $SHA"
echo
echo "🔎 Vérifs rapides :"
echo "  • resources/ présent :"; tar -tzf "$OUT" | grep -E '(^|/)(resources)/' | head -n 3 || true
echo "  • routes/ présent :";    tar -tzf "$OUT" | grep -E '(^|/)(routes)/' | head -n 3 || true
echo "  • dump DB :";           tar -tzf "$OUT" | grep -E '_db/.*\.sql\.gz$' || echo "  (DB ignorée car --no-db)"
echo
echo "Intégrité :"; echo "$SHA  $OUT" | sha256sum -c -
