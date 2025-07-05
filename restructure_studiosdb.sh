#!/bin/bash

echo "🏗️ RESTRUCTURATION STUDIOSDB - STANDARDS LARAVEL 12"
echo "================================================="

# ÉTAPE 1: Créer la nouvelle structure
echo "1️⃣ Création nouvelle structure..."

mkdir -p resources/views/pages/admin/{dashboard,users,cours,ceintures,presences,paiements,sessions,seminaires,ecoles,exports,logs}
mkdir -p resources/views/components/admin
mkdir -p resources/views/components/ui

# ÉTAPE 2: Déplacer les vues admin vers pages/admin
echo "2️⃣ Migration admin/ vers pages/admin/..."

# Dashboard
if [ -d "resources/views/admin/dashboard" ]; then
    mv resources/views/admin/dashboard/* resources/views/pages/admin/dashboard/ 2>/dev/null
fi

# Modules principaux
for module in users cours ceintures presences paiements sessions seminaires ecoles exports logs; do
    if [ -d "resources/views/admin/$module" ]; then
        echo "  📁 Migration module: $module"
        mv resources/views/admin/$module/* resources/views/pages/admin/$module/ 2>/dev/null || true
    fi
done

# ÉTAPE 3: Convertir partials en components
echo "3️⃣ Conversion partials → components..."

# Déplacer admin-navigation vers components/admin
if [ -f "resources/views/partials/admin-navigation.blade.php" ]; then
    echo "  🔄 Migration: admin-navigation → components/admin/navigation"
    mv resources/views/partials/admin-navigation.blade.php resources/views/components/admin/navigation.blade.php
fi

# Garder footer comme partial (utilisé dans layouts)
echo "  ✅ Footer reste comme partial (utilisé dans layouts)"

# ÉTAPE 4: Créer le component flash-messages manquant
if [ ! -f "resources/views/components/admin/flash-messages.blade.php" ]; then
    echo "4️⃣ Création component flash-messages..."
    cat > resources/views/components/admin/flash-messages.blade.php << 'EOF'
{{-- Flash Messages Component - StudiosDB --}}
<div class="studiosdb-flash-messages">
    @if (session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg mb-4" role="alert">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4" role="alert">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <span>{{ session('error') }}</span>
            </div>
        </div>
    @endif

    @if (session('warning'))
        <div class="bg-yellow-50 border border-yellow-200 text-yellow-700 px-4 py-3 rounded-lg mb-4" role="alert">
            <div class="flex items-center">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                <span>{{ session('warning') }}</span>
            </div>
        </div>
    @endif

    @if (session('info'))
        <div class="bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded-lg mb-4" role="alert">
            <div class="flex items-center">
                <i class="fas fa-info-circle mr-2"></i>
                <span>{{ session('info') }}</span>
            </div>
        </div>
    @endif
</div>
EOF
fi

# ÉTAPE 5: Mettre à jour les références dans les contrôleurs
echo "5️⃣ Mise à jour références contrôleurs..."

# Remplacer admin.* par pages.admin.*
find app/Http/Controllers/Admin -name "*.php" -exec sed -i.bak "s/admin\.dashboard/pages.admin.dashboard/g" {} \;
find app/Http/Controllers/Admin -name "*.php" -exec sed -i.bak "s/admin\.users/pages.admin.users/g" {} \;
find app/Http/Controllers/Admin -name "*.php" -exec sed -i.bak "s/admin\.cours/pages.admin.cours/g" {} \;
find app/Http/Controllers/Admin -name "*.php" -exec sed -i.bak "s/admin\.ceintures/pages.admin.ceintures/g" {} \;
find app/Http/Controllers/Admin -name "*.php" -exec sed -i.bak "s/admin\.presences/pages.admin.presences/g" {} \;
find app/Http/Controllers/Admin -name "*.php" -exec sed -i.bak "s/admin\.paiements/pages.admin.paiements/g" {} \;
find app/Http/Controllers/Admin -name "*.php" -exec sed -i.bak "s/admin\.sessions/pages.admin.sessions/g" {} \;
find app/Http/Controllers/Admin -name "*.php" -exec sed -i.bak "s/admin\.seminaires/pages.admin.seminaires/g" {} \;

# ÉTAPE 6: Mettre à jour le layout admin
echo "6️⃣ Mise à jour layout admin..."

if [ -f "resources/views/layouts/admin.blade.php" ]; then
    sed -i.bak "s/@include('partials\.admin-navigation')/<x-admin.navigation \/>/" resources/views/layouts/admin.blade.php
fi

# ÉTAPE 7: Nettoyer ancien dossier admin
echo "7️⃣ Nettoyage ancien dossier admin..."
if [ -d "resources/views/admin" ]; then
    # Vérifier que le dossier est vide avant suppression
    if [ -z "$(find resources/views/admin -name '*.blade.php')" ]; then
        echo "  🗑️ Suppression dossier admin vide"
        rm -rf resources/views/admin
    else
        echo "  ⚠️ Dossier admin non vide, conservation"
    fi
fi

echo ""
echo "✅ Restructuration terminée!"
echo ""
echo "📊 NOUVELLE STRUCTURE:"
echo "├── resources/views/"
echo "│   ├── pages/admin/     ← Vues migrées"
echo "│   ├── components/admin/ ← Navigation + flash-messages"
echo "│   ├── partials/        ← Footer uniquement"
echo "│   └── layouts/         ← Layouts mis à jour"
