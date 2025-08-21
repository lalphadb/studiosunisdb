#!/usr/bin/env bash
set -euo pipefail

# StudiosDB – Backup minimal (code only, exclusions exactes)
# Sortie: dist/studiosdb_backup_YYYYMMDD_HHMMSS.tar.gz

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$ROOT"

NOW="$(date +%F_%H%M%S)"
DIST="$ROOT/dist"
TMP="$ROOT/tmp"
OUT="$DIST/studiosdb_backup_${NOW}.tar.gz"
EX="$TMP/backup_excludes_${NOW}.txt"

mkdir -p "$DIST" "$TMP"

# Exclusions EXACTES demandées
cat > "$EX" <<'EOF'
vendor
node_modules
storage
public
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
tools
maintenance
migrations_backup
A_consrver
EOF

echo "📦 Création de l’archive avec exclusions ($EX)…"

if command -v pigz >/dev/null 2>&1; then
  # pigz dispo → plus rapide
  tar -cf - -X "$EX" . | pigz -9 > "$OUT"
else
  # gzip standard
  tar -czf "$OUT" -X "$EX" .
fi

SIZE="$(du -h "$OUT" | awk '{print $1}')"
SHA="$(sha256sum "$OUT" | awk '{print $1}')"

echo ""
echo "✅ Backup prêt : $OUT"
echo "   Taille : $SIZE"
echo "   SHA256 : $SHA"
echo ""
echo "Vérification rapide :"
echo "  tar -tzf \"$OUT\" | head -n 40"
echo "  echo \"$SHA  $OUT\" | sha256sum -c -"
