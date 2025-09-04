#!/bin/bash
# StudiosDB - Backup complet module Cours (code + données)
# Usage: bash tools/backup_cours_module.sh

set -euo pipefail

TS=$(date +%Y%m%d_%H%M%S)
BASE_DIR="backups/module_cours_legacy/${TS}"

echo "=== BACKUP MODULE COURS (${TS}) ==="

mkdir -p "${BASE_DIR}/code"
mkdir -p "${BASE_DIR}/data"

echo "1) Sauvegarde fichiers code..."

# Liste des chemins à sauvegarder
FILES=(
  "app/Models/Cours.php"
  "app/Http/Controllers/CoursController.php"
  "app/Http/Requests/StoreCoursRequest.php"
  "app/Http/Requests/UpdateCoursRequest.php"
  "app/Policies/CoursPolicy.php"
  "app/Traits/BelongsToEcole.php"
)

# Pages Vue
if [ -d "resources/js/Pages/Cours" ]; then
  FILES+=("resources/js/Pages/Cours")
fi

# Migrations liées
while IFS= read -r f; do
  FILES+=("$f")
done < <(ls database/migrations/*cours* 2>/dev/null || true)

for f in "${FILES[@]}"; do
  if [ -e "$f" ]; then
    destDir="${BASE_DIR}/code/$(dirname "$f")"
    mkdir -p "$destDir"
    cp -r "$f" "$destDir/"
  fi
done

echo "2) Export données (JSON)..."

php artisan tinker --execute="
\$exportDir='${BASE_DIR}/data';
if(!is_dir(\$exportDir)) mkdir(\$exportDir,0777,true);
if (Schema::hasTable('cours')) {
    \$cours = DB::table('cours')->get();
    file_put_contents(\$exportDir.'/cours_data.json', json_encode(\$cours, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
}
if (Schema::hasTable('cours_membres')) {
    \$cm = DB::table('cours_membres')->get();
    file_put_contents(\$exportDir.'/cours_membres_data.json', json_encode(\$cm, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
}
" >/dev/null 2>&1 || echo "⚠️  Export tinker a retourné une anomalie"

echo "3) Hash d'intégrité..."
(
  cd "${BASE_DIR}" || exit 1
  find . -type f -print0 | sort -z | xargs -0 sha256sum > SHA256SUMS.txt
)

echo "4) Archive tar.gz..."
ARCHIVE="backups/module_cours_legacy/cours_backup_${TS}.tar.gz"
tar -czf "${ARCHIVE}" -C "backups/module_cours_legacy" "${TS}"
sha256sum "${ARCHIVE}" > "${ARCHIVE}.sha256"

echo "5) Résumé:"
echo "  Dossier: ${BASE_DIR}" 
echo "  Archive: ${ARCHIVE}" 
echo "  Fichiers code: $(find "${BASE_DIR}/code" -type f | wc -l)"
echo "  Fichiers data: $(find "${BASE_DIR}/data" -type f | wc -l)"

echo "✅ Backup module cours terminé"

exit 0
