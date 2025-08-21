#!/usr/bin/env bash
set -euo pipefail
REF=".freeze/dashboard.sha256"
FILES=(
  "app/Http/Controllers/DashboardController.php"
  "resources/js/Pages/Dashboard/Admin.vue"
)
mkdir -p "$(dirname "$REF")"

if [[ "${1:-}" == "--record" ]]; then
  : > "$REF"
  for f in "${FILES[@]}"; do
    [[ -f "$f" ]] || { echo "⚠️ $f manquant"; continue; }
    sha256sum "$f" >> "$REF"
    echo "📌 enregistré: $f"
  done
  echo "✅ FREEZE enregistré dans $REF"
  exit 0
fi

# Vérification
STATUS=0
while read -r saved_hash saved_file; do
  [[ -f "$saved_file" ]] || { echo "❌ disparu: $saved_file"; STATUS=1; continue; }
  cur=$(sha256sum "$saved_file" | awk '{print $1}')
  if [[ "$cur" != "$saved_hash" ]]; then
    echo "❌ MODIFIÉ: $saved_file"
    echo "   attendu=$saved_hash"
    echo "   actuel =$cur"
    STATUS=1
  else
    echo "OK: $saved_file"
  fi
done < "$REF" || true

exit $STATUS
