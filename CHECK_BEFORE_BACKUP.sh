#!/bin/bash
echo "🔍 VÉRIFICATION PRÉREQUIS SAUVEGARDE"
cd /home/studiosdb/studiosunisdb

ERRORS=0

echo ""
echo "📋 VÉRIFICATIONS SYSTÈME:"

# Vérifier qu'on est dans le bon répertoire
if [ ! -f "artisan" ]; then
    echo "❌ Pas dans un projet Laravel (artisan manquant)"
    ERRORS=$((ERRORS + 1))
else
    echo "✅ Projet Laravel détecté"
fi

# Vérifier composer.json
if [ ! -f "composer.json" ]; then
    echo "❌ composer.json manquant"
    ERRORS=$((ERRORS + 1))
else
    echo "✅ composer.json présent"
fi

# Vérifier .env
if [ ! -f ".env" ]; then
    echo "⚠️ .env manquant (normal si pas configuré)"
else
    echo "✅ .env présent"
fi

echo ""
echo "🔧 VÉRIFICATIONS TECHNIQUE:"

# PHP
if command -v php >/dev/null 2>&1; then
    PHP_VERSION=$(php --version | head -n1 | cut -d' ' -f2 | cut -d'.' -f1,2)
    echo "✅ PHP $PHP_VERSION disponible"
else
    echo "❌ PHP non disponible"
    ERRORS=$((ERRORS + 1))
fi

# Composer  
if command -v composer >/dev/null 2>&1; then
    echo "✅ Composer disponible"
else
    echo "❌ Composer non disponible"
    ERRORS=$((ERRORS + 1))
fi

# Git
if command -v git >/dev/null 2>&1; then
    if [ -d ".git" ]; then
        echo "✅ Git repository initialisé"
    else
        echo "⚠️ Git disponible mais pas de repository"
    fi
else
    echo "⚠️ Git non disponible"
fi

echo ""
echo "📊 VÉRIFICATIONS PROJET:"

# Laravel fonctionnel
if php artisan --version >/dev/null 2>&1; then
    LARAVEL_VERSION=$(php artisan --version | cut -d' ' -f3)
    echo "✅ Laravel $LARAVEL_VERSION fonctionnel"
else
    echo "❌ Laravel non fonctionnel"
    ERRORS=$((ERRORS + 1))
fi

# Base de données
php artisan tinker --execute="
try {
    \$tables = DB::select('SHOW TABLES');
    echo 'DB_STATUS: ' . count(\$tables) . ' tables' . PHP_EOL;
} catch (Exception \$e) {
    echo 'DB_ERROR: ' . \$e->getMessage() . PHP_EOL;
}
" 2>/dev/null | grep -q "DB_STATUS"

if [ $? -eq 0 ]; then
    DB_TABLES=$(php artisan tinker --execute="
    try {
        \$tables = DB::select('SHOW TABLES');
        echo count(\$tables);
    } catch (Exception \$e) {
        echo '0';
    }
    " 2>/dev/null | tail -n1)
    echo "✅ Base de données accessible ($DB_TABLES tables)"
else
    echo "⚠️ Base de données inaccessible"
fi

echo ""
echo "📁 VÉRIFICATIONS STRUCTURE:"

# Dossiers critiques
DIRS=("app" "database" "resources" "routes" "config")
for dir in "${DIRS[@]}"; do
    if [ -d "$dir" ]; then
        echo "✅ Dossier $dir présent"
    else
        echo "❌ Dossier $dir manquant"
        ERRORS=$((ERRORS + 1))
    fi
done

# Fichiers critiques
FILES=("app/Http/Controllers/CoursController.php" "resources/js/Pages/Dashboard/Index.vue")
for file in "${FILES[@]}"; do
    if [ -f "$file" ]; then
        echo "✅ $file présent"
    else
        echo "⚠️ $file manquant (modules pas encore développés ?)"
    fi
done

echo ""
echo "💾 VÉRIFICATIONS ESPACE DISQUE:"

# Espace disque disponible
DISK_AVAILABLE=$(df -h . | awk 'NR==2{print $4}')
echo "💾 Espace disponible: $DISK_AVAILABLE"

if [ -d "backups" ]; then
    BACKUP_SIZE=$(du -sh backups 2>/dev/null | cut -f1)
    echo "📁 Taille backups existants: ${BACKUP_SIZE:-0}"
else
    echo "📁 Aucun backup existant"
fi

echo ""
echo "🔐 VÉRIFICATIONS PERMISSIONS:"

# Permissions écriture
if [ -w "." ]; then
    echo "✅ Permissions écriture répertoire courant"
else
    echo "❌ Pas de permissions écriture"
    ERRORS=$((ERRORS + 1))
fi

# Créer dossier test
mkdir -p "test_permissions" 2>/dev/null && rmdir "test_permissions" 2>/dev/null
if [ $? -eq 0 ]; then
    echo "✅ Création/suppression dossiers OK"
else
    echo "❌ Problème création/suppression dossiers"
    ERRORS=$((ERRORS + 1))
fi

echo ""
echo "📋 RÉSUMÉ VÉRIFICATIONS:"

if [ $ERRORS -eq 0 ]; then
    echo "🎯 ✅ TOUS LES PRÉREQUIS SONT REMPLIS"
    echo ""
    echo "🚀 PRÊT POUR SAUVEGARDE COMPLÈTE"
    echo "   Commande: ./SAUVEGARDE_COMPLETE_PROJET.sh"
    echo ""
    
    # Estimer temps et taille
    echo "📊 ESTIMATIONS:"
    
    PROJECT_SIZE=$(du -sh . --exclude=node_modules --exclude=vendor 2>/dev/null | cut -f1)
    echo "📁 Taille projet (sans node_modules/vendor): ${PROJECT_SIZE:-N/A}"
    
    if [ -d "vendor" ]; then
        VENDOR_SIZE=$(du -sh vendor 2>/dev/null | cut -f1)
        echo "📦 Taille vendor: ${VENDOR_SIZE:-N/A}"
    fi
    
    echo "⏱️ Temps estimé: 2-5 minutes"
    echo "💾 Espace requis: ~100-500MB"
    
    exit 0
else
    echo "🚨 ❌ $ERRORS PROBLÈME(S) DÉTECTÉ(S)"
    echo ""
    echo "⚠️ SAUVEGARDE RISQUÉE - Corriger d'abord les erreurs"
    echo ""
    echo "🔧 ACTIONS SUGGÉRÉES:"
    echo "- Vérifier installation PHP/Composer/Laravel"
    echo "- Configurer base de données (.env)"
    echo "- Vérifier permissions dossier"
    echo ""
    exit $ERRORS
fi
