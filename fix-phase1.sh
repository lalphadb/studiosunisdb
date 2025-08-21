#!/bin/bash

# =====================================================
# STUDIOSDB - SCRIPT DE CORRECTION PHASE 1
# Infrastructure ecoles + Composants UI
# =====================================================

echo "╔══════════════════════════════════════════╗"
echo "║   STUDIOSDB - CORRECTION PHASE 1          ║"
echo "║   Infrastructure Multi-École + UI         ║"
echo "╚══════════════════════════════════════════╝"
echo ""

# Changement vers le répertoire du projet
cd /home/studiosdb/studiosunisdb

# 1. Clear des caches
echo "🧹 Nettoyage des caches..."
php artisan optimize:clear

# 2. Exécution des migrations
echo ""
echo "📦 Exécution des nouvelles migrations..."
php artisan migrate --force

# 3. Exécution du seeder pour l'école
echo ""
echo "🏢 Création de l'école par défaut..."
php artisan db:seed --class=EcoleSeeder --force

# 4. Mise à jour des permissions
echo ""
echo "🔐 Vérification des permissions..."
php artisan permission:cache-reset

# 5. Compilation des assets
echo ""
echo "📦 Compilation des assets Vue/Tailwind..."
npm run build

# 6. Cache de configuration
echo ""
echo "⚡ Optimisation des caches..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 7. Vérifications finales
echo ""
echo "✅ Vérifications finales..."
echo ""

# Vérifier que la table ecoles existe
php artisan tinker --execute="echo 'Table ecoles: ' . (\Schema::hasTable('ecoles') ? '✅ OK' : '❌ ERREUR');"

# Vérifier qu'il y a au moins une école
php artisan tinker --execute="echo 'École par défaut: ' . (\App\Models\Ecole::count() > 0 ? '✅ ' . \App\Models\Ecole::first()->nom : '❌ AUCUNE');"

# Vérifier les composants UI
echo ""
echo "Composants UI:"
for file in AnimatedNumber ModernButton ModernNotification ModernStatsCard; do
    if [ -s "resources/js/Components/UI/$file.vue" ]; then
        echo "  ✅ $file.vue ($(wc -c < resources/js/Components/UI/$file.vue) bytes)"
    else
        echo "  ❌ $file.vue manquant ou vide"
    fi
done

echo ""
echo "╔══════════════════════════════════════════╗"
echo "║   ✅ PHASE 1 TERMINÉE !                   ║"
echo "╚══════════════════════════════════════════╝"
echo ""
echo "Prochaines étapes:"
echo "  1. Tester la compilation: npm run dev"
echo "  2. Vérifier les pages: php artisan serve"
echo "  3. Continuer avec Phase 2 (Membres) et Phase 3 (Cours)"
echo ""
