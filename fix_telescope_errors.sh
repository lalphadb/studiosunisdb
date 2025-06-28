#!/bin/bash
echo "🔧 CORRECTION ERREURS TELESCOPE"
echo "==============================="

# 1. Vérifier et corriger la syntaxe Blade dans les vues problématiques
echo "📝 Correction syntaxe Blade..."

# Rechercher les vues avec syntaxe problématique
find resources/views/admin -name "*.blade.php" -exec grep -l ":create-route.*route(" {} \; | while read file; do
    echo "Correction de: $file"
    # Remplacer la syntaxe problématique par la syntaxe correcte
    sed -i 's/:create-route="route(\([^)]*\))"/create-route="{{ route(\1) }}"/g' "$file"
done

# 2. Vérifier les routes manquantes dans admin.php
echo ""
echo "📝 Vérification routes admin.php..."
if ! grep -q "Route::resource('seminaires'" routes/admin.php; then
    echo "Route::resource('seminaires', App\\Http\\Controllers\\Admin\\SeminaireController::class);" >> routes/admin.php
    echo "✅ Route séminaires ajoutée"
fi

if ! grep -q "Route::resource('paiements'" routes/admin.php; then
    echo "Route::resource('paiements', App\\Http\\Controllers\\Admin\\PaiementController::class);" >> routes/admin.php
    echo "✅ Route paiements ajoutée"
fi

# 3. Corriger le composant module-header pour éviter les erreurs
echo ""
echo "📝 Optimisation composant module-header..."
cat > resources/views/components/module-header.blade.php << 'COMPONENT_EOF'
@props([
    'module',
    'title', 
    'subtitle',
    'createRoute' => null,
    'createPermission' => null
])

@php
$colors = [
    'dashboard' => ['primary' => 'blue-500', 'secondary' => 'cyan-600', 'icon' => '📊'],
    'ecole' => ['primary' => 'green-500', 'secondary' => 'emerald-600', 'icon' => '🏫'],
    'user' => ['primary' => 'blue-500', 'secondary' => 'cyan-600', 'icon' => '👤'],
    'ceinture' => ['primary' => 'orange-500', 'secondary' => 'red-600', 'icon' => '🥋'],
    'cours' => ['primary' => 'purple-500', 'secondary' => 'indigo-600', 'icon' => '📚'],
    'seminaire' => ['primary' => 'pink-500', 'secondary' => 'purple-600', 'icon' => '🎯'],
    'paiement' => ['primary' => 'yellow-500', 'secondary' => 'orange-600', 'icon' => '💰'],
    'presence' => ['primary' => 'teal-500', 'secondary' => 'green-600', 'icon' => '✅']
];
$moduleConfig = $colors[$module] ?? $colors['dashboard'];
@endphp

<header class="bg-gradient-to-r from-{{ $moduleConfig['primary'] }} via-{{ $moduleConfig['secondary'] }} to-transparent rounded-xl p-6 text-white relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-transparent to-slate-900 opacity-60"></div>
    
    <div class="relative z-10 flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <span class="text-3xl">{{ $moduleConfig['icon'] }}</span>
            <div>
                <h1 class="text-2xl font-bold">{{ $title }}</h1>
                <p class="text-white/80">{{ $subtitle }}</p>
            </div>
        </div>
        
        @if($createRoute && $createPermission)
            @can($createPermission)
                <a href="{{ $createRoute }}" 
                   class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg transition-colors duration-200 flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span>Nouveau</span>
                </a>
            @endcan
        @endif
    </div>
</header>
COMPONENT_EOF

# 4. Nettoyer le cache Laravel
echo ""
echo "🧹 Nettoyage cache Laravel..."
php artisan config:clear
php artisan route:clear  
php artisan view:clear
php artisan cache:clear

echo ""
echo "✅ CORRECTION TERMINÉE!"
echo "Les erreurs Telescope devraient être résolues."
