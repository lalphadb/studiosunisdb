#!/usr/bin/env bash
set -euo pipefail
PROJ="${1:-$(pwd)}"
[[ -f "$PROJ/artisan" ]] || { echo "❌ Lance depuis la racine du projet"; exit 1; }
source "$PROJ/tools/_load_env.sh" "$PROJ/.env"
OUT="${2:-$PROJ/backups}"
mkdir -p "$OUT"
STAMP="$(date +%Y%m%d-%H%M%S)"
FILE="$OUT/${DB_DATABASE}_dump_${STAMP}.sql.gz"
echo "— Export $DB_DATABASE → $FILE"
mysqldump -h "$DB_HOST" -P "$DB_PORT" -u "$DB_USERNAME" -p"$DB_PASSWORD" "$DB_DATABASE" \
  --single-transaction --routines --triggers --events | gzip -9 > "$FILE"
echo "✅ Export OK: $FILE"
