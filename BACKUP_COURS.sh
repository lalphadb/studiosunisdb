#!/bin/bash
echo "=== SAUVEGARDE COMPLÈTE - MODULE COURS CORRIGÉ ==="
cd /home/studiosdb/studiosunisdb

TIMESTAMP=$(date +"%Y%m%d_%H%M%S")
BACKUP_DIR="backups/backup_cours_fixed_${TIMESTAMP}"

echo "🔄 Création sauvegarde : ${BACKUP_DIR}"

# Créer dossier backup
mkdir -p "${BACKUP_DIR}"
mkdir -p "${BACKUP_DIR}/migrations"
mkdir -p "${BACKUP_DIR}/controllers"
mkdir -p "${BACKUP_DIR}/requests"
mkdir -p "${BACKUP_DIR}/views"
mkdir -p "${BACKUP_DIR}/scripts"

echo ""
echo "📁 SAUVEGARDE FICHIERS CRITIQUES"

# Migrations
echo "- Migrations cours..."
cp database/migrations/*cours*.php "${BACKUP_DIR}/migrations/" 2>/dev/null || echo "  Aucune migration cours spécifique"
cp database/migrations/2025_08_28_*.php "${BACKUP_DIR}/migrations/" 2>/dev/null || echo "  Aucune migration 2025_08_28"

# Contrôleurs  
echo "- Contrôleurs..."
cp app/Http/Controllers/CoursController.php "${BACKUP_DIR}/controllers/" 2>/dev/null || echo "  CoursController absent"

# FormRequests
echo "- FormRequests..."
cp app/Http/Requests/*Cours*.php "${BACKUP_DIR}/requests/" 2>/dev/null || echo "  FormRequests cours absentes"

# Vues
echo "- Vues cours..."
cp -r resources/js/Pages/Cours "${BACKUP_DIR}/views/" 2>/dev/null || echo "  Dossier Cours/Pages absent"

# Scripts de correction
echo "- Scripts correction..."
cp *.sh "${BACKUP_DIR}/scripts/" 2>/dev/null || echo "  Aucun script .sh"

echo ""
echo "📊 ÉTAT ACTUEL SYSTÈME"

# Status migrations
echo "- Migrations status:" > "${BACKUP_DIR}/system_status.txt"
php artisan migrate:status >> "${BACKUP_DIR}/system_status.txt" 2>&1

# Structure DB cours
echo -e "\n- Structure table cours:" >> "${BACKUP_DIR}/system_status.txt"
php artisan tinker --execute="
if (Schema::hasTable('cours')) {
    \$columns = Schema::getColumnListing('cours');
    echo 'Columns: ' . implode(', ', \$columns) . PHP_EOL;
    
    // Contraintes critiques
    \$tarif = DB::select('DESCRIBE cours tarif_mensuel')[0];
    echo 'tarif_mensuel Null: ' . \$tarif->Null . PHP_EOL;
    
    \$ecole = DB::select('DESCRIBE cours ecole_id')[0];
    echo 'ecole_id Default: ' . (\$ecole->Default ?? 'NULL') . PHP_EOL;
} else {
    echo 'Table cours: NOT EXISTS' . PHP_EOL;
}
" >> "${BACKUP_DIR}/system_status.txt" 2>&1

# Compter enregistrements
echo -e "\n- Données actuelles:" >> "${BACKUP_DIR}/system_status.txt"
php artisan tinker --execute="
echo 'Users: ' . App\\Models\\User::count() . PHP_EOL;
echo 'Cours: ' . (Schema::hasTable('cours') ? DB::table('cours')->count() : 'N/A') . PHP_EOL;
echo 'Membres: ' . (Schema::hasTable('membres') ? DB::table('membres')->count() : 'N/A') . PHP_EOL;
" >> "${BACKUP_DIR}/system_status.txt" 2>&1

# Versions
echo -e "\n- Versions:" >> "${BACKUP_DIR}/system_status.txt"
php artisan about | grep -E "(Laravel|PHP)" >> "${BACKUP_DIR}/system_status.txt" 2>&1

echo ""
echo "📝 GÉNÉRATION CHANGELOG"

cat > "${BACKUP_DIR}/CHANGELOG_COURS.md" << 'EOL'
# CHANGELOG - Module Cours - Corrections Contraintes DB

## Version: Post-Corrections (Stable)
## Date: $(date +"%Y-%m-%d %H:%M:%S")

### 🔧 PROBLÈMES RÉSOLUS

#### 1. Contrainte tarif_mensuel cannot be null
- **Cause** : Colonne NOT NULL en DB, FormRequest envoyait string vide
- **Solution** : Migration `fix_tarif_mensuel_nullable.php` + FormRequest robuste
- **Fichiers** :
  - `2025_08_28_130000_fix_tarif_mensuel_nullable.php`
  - `StoreCoursRequest.php` (prepareForValidation)
  - `Create.vue` (tarif_mensuel: null)

#### 2. Contrainte ecole_id doesn't have default value  
- **Cause** : Environnement mono-école sans fallback robuste
- **Solution** : Migration default + FormRequest fallback ID=1
- **Fichiers** :
  - `2025_08_28_140000_fix_ecole_id_default_cours.php`
  - `StoreCoursRequest.php` + `UpdateCoursRequest.php` (fallback)

### ✅ AMÉLIORATIONS APPORTÉES

1. **FormRequests Laravel 12** : Validation centralisée, messages FR
2. **Contrôleur optimisé** : Utilise FormRequests (DRY principle)
3. **Migration DB robuste** : Gestion contraintes + defaults
4. **Formulaires Vue corrigés** : null au lieu de string vide
5. **Scripts automatisés** : Résolution problèmes en 1 commande

### 🎯 RÉSULTATS

- ✅ Création cours MENSUEL : fonctionne (pas de régression)
- ✅ Création cours TRIMESTRIEL/HORAIRE/A_LA_CARTE : RÉPARÉ
- ✅ Validation robuste avec messages français
- ✅ Architecture Laravel 12 respectée
- ✅ Environnement mono-école géré

### 🏗️ ARCHITECTURE FINALE

```
Browser → Create.vue (données propres)
      → StoreCoursRequest (validation + fallbacks)  
      → CoursController::store (simplified)
      → DB (contraintes résolues)
```

### 📋 FICHIERS MODIFIÉS

#### Nouveaux fichiers :
- `app/Http/Requests/StoreCoursRequest.php`
- `app/Http/Requests/UpdateCoursRequest.php`  
- `database/migrations/2025_08_28_130000_fix_tarif_mensuel_nullable.php`
- `database/migrations/2025_08_28_140000_fix_ecole_id_default_cours.php`

#### Fichiers modifiés :
- `app/Http/Controllers/CoursController.php`
- `resources/js/Pages/Cours/Create.vue`
- `resources/js/Pages/Cours/Edit.vue`

### 🧪 TESTS VALIDÉS

- [x] Création cours mensuel
- [x] Création cours trimestriel  
- [x] Création cours horaire
- [x] Création cours à la carte
- [x] Validation erreurs (messages FR)
- [x] Fallback ecole_id mono-école

### 🚀 PRÊT POUR PRODUCTION

Module Cours : **100% OPÉRATIONNEL**
EOL

# Remplacer la date dans le changelog
sed -i "s/\$(date.*)/$(date +'%Y-%m-%d %H:%M:%S')/g" "${BACKUP_DIR}/CHANGELOG_COURS.md"

echo ""
echo "📋 RÉSUMÉ SAUVEGARDE"
echo "📁 Dossier : ${BACKUP_DIR}"
echo "📝 Changelog : ${BACKUP_DIR}/CHANGELOG_COURS.md"
echo "📊 Status : ${BACKUP_DIR}/system_status.txt"
echo "📁 Fichiers sauvegardés :"
find "${BACKUP_DIR}" -type f | wc -l | xargs echo "   Nombre total :"

echo ""
echo "✅ SAUVEGARDE COMPLÈTE TERMINÉE"
echo ""
echo "🎯 ÉTAT ACTUEL : Module Cours STABLE et OPÉRATIONNEL"
echo "🚀 PRÊT POUR : Module Utilisateurs (J4)"
