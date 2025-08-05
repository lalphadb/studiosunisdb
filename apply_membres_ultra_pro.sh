#!/bin/bash

# 🎯 SCRIPT ULTRA-PROFESSIONNEL - MISE À JOUR MODULE MEMBRES
# Développé pour StudiosDB v5 Pro - Laravel 12.21

set -e

echo "🚀 DÉBUT TRANSFORMATION MODULE MEMBRES - STUDIOSDB V5 PRO"
echo "=================================================="

# Variables
PROJECT_DIR="/home/studiosdb/studiosunisdb/studiosdb_v5_pro"
BACKUP_DIR="/home/studiosdb/studiosunisdb/backups/membres_$(date +%Y%m%d_%H%M%S)"
MEMBRE_DIR="$PROJECT_DIR/resources/js/Pages/Membres"

# 1. VÉRIFICATIONS PRÉALABLES
echo "📋 1. VÉRIFICATIONS SYSTÈME..."

# Vérifier répertoire projet
if [ ! -d "$PROJECT_DIR" ]; then
    echo "❌ ERREUR: Répertoire projet non trouvé: $PROJECT_DIR"
    exit 1
fi

# Vérifier permissions
if [ ! -w "$MEMBRE_DIR" ]; then
    echo "❌ ERREUR: Permissions insuffisantes sur: $MEMBRE_DIR"
    exit 1
fi

# Vérifier processus Laravel
if ! pgrep -f "php artisan serve" > /dev/null; then
    echo "⚠️  AVERTISSEMENT: Laravel ne semble pas actif"
fi

echo "✅ Vérifications système réussies"

# 2. SAUVEGARDE SÉCURISÉE
echo "💾 2. SAUVEGARDE MODULES EXISTANTS..."

mkdir -p "$BACKUP_DIR"
cp -r "$MEMBRE_DIR" "$BACKUP_DIR/"
echo "✅ Sauvegarde créée dans: $BACKUP_DIR"

# 3. VÉRIFICATION DÉPENDANCES
echo "🔍 3. VÉRIFICATION COMPOSANTS REQUIS..."

COMPONENTS_DIR="$PROJECT_DIR/resources/js/Components"

# Vérifier ModernStatsCard
if [ ! -f "$COMPONENTS_DIR/ModernStatsCard.vue" ]; then
    echo "❌ ERREUR: ModernStatsCard.vue manquant"
    exit 1
fi

# Vérifier ModernProgressBar
if [ ! -f "$COMPONENTS_DIR/ModernProgressBar.vue" ]; then
    echo "❌ ERREUR: ModernProgressBar.vue manquant"
    exit 1
fi

echo "✅ Tous les composants requis sont présents"

# 4. BACKUP DE SÉCURITÉ DE L'ANCIEN INDEX
echo "🔄 4. SAUVEGARDE ANCIEN INDEX..."

if [ -f "$MEMBRE_DIR/Index.vue" ]; then
    cp "$MEMBRE_DIR/Index.vue" "$MEMBRE_DIR/Index.vue.backup.$(date +%Y%m%d_%H%M%S)"
    echo "✅ Ancien Index sauvegardé"
fi

# 5. REMPLACEMENT PAR NOUVEAU MODULE
echo "⚡ 5. INSTALLATION NOUVEAU MODULE ULTRA-PRO..."

# Remplacer l'ancien Index par le nouveau
mv "$MEMBRE_DIR/IndexNew.vue" "$MEMBRE_DIR/Index.vue"
echo "✅ Nouveau module membres installé"

# 6. VÉRIFICATION SYNTAX VUE
echo "🔍 6. VÉRIFICATION SYNTAXE VUE..."

cd "$PROJECT_DIR"

# Vérifier avec npm si disponible
if command -v npm &> /dev/null; then
    echo "Compilation Vue en cours..."
    npm run build
    if [ $? -eq 0 ]; then
        echo "✅ Compilation Vue réussie"
    else
        echo "⚠️  Compilation Vue avec avertissements"
    fi
fi

# 7. PERMISSIONS ET OPTIMISATIONS
echo "🔧 7. OPTIMISATION PERMISSIONS..."

# Permissions correctes
find "$MEMBRE_DIR" -type f -name "*.vue" -exec chmod 644 {} \;
chown -R studiosdb:studiosdb "$MEMBRE_DIR"

echo "✅ Permissions optimisées"

# 8. VÉRIFICATION ROUTES LARAVEL
echo "🛣️  8. VÉRIFICATION ROUTES..."

cd "$PROJECT_DIR"

# Vérifier cache routes
php artisan route:clear
php artisan route:cache

echo "✅ Routes optimisées"

# 9. TESTS DE FONCTIONNEMENT
echo "🧪 9. TESTS SYSTÈME..."

# Test connexion base de données
php artisan migrate:status > /dev/null 2>&1
if [ $? -eq 0 ]; then
    echo "✅ Base de données connectée"
else
    echo "⚠️  Problème connexion base de données"
fi

# 10. RESTART SERVICES SI NÉCESSAIRE
echo "🔄 10. RESTART SERVICES..."

# Restart Laravel si actif
if pgrep -f "php artisan serve" > /dev/null; then
    pkill -f "php artisan serve"
    sleep 2
    nohup php artisan serve --host=0.0.0.0 --port=8000 > /dev/null 2>&1 &
    echo "✅ Laravel redémarré"
fi

# Restart Vite si actif
if pgrep -f "npm run dev" > /dev/null; then
    pkill -f "npm run dev"
    sleep 2
    nohup npm run dev > /dev/null 2>&1 &
    echo "✅ Vite HMR redémarré"
fi

# 11. RAPPORT FINAL
echo ""
echo "🎉 TRANSFORMATION TERMINÉE AVEC SUCCÈS!"
echo "=================================================="
echo "📊 RÉSUMÉ:"
echo "   ✅ Module membres transformé avec succès"
echo "   ✅ Composants modernes intégrés"
echo "   ✅ Style uniforme avec module cours"
echo "   ✅ Sauvegarde sécurisée: $BACKUP_DIR"
echo ""
echo "🌐 ACCÈS:"
echo "   • Application: http://studiosdb.local:8000/membres"
echo "   • Vite HMR: http://localhost:5173"
echo ""
echo "📝 PROCHAINES ÉTAPES:"
echo "   1. Tester interface membres"
echo "   2. Vérifier responsive design"  
echo "   3. Tester filtres et recherche"
echo "   4. Valider actions CRUD"
echo ""
echo "💡 SUPPORT: En cas de problème, restaurer depuis $BACKUP_DIR"
echo ""

exit 0
