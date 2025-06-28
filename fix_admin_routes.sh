#!/bin/bash
echo "🔧 CORRECTION FINALE ROUTES ADMIN"
echo "================================="

# 1. Le fichier admin.php est parfait, vérifier qu'il est bien inclus dans web.php
echo "📝 1. Vérification inclusion admin.php dans web.php..."
if ! grep -q "require.*admin.php" routes/web.php; then
    echo "" >> routes/web.php
    echo "// Inclusion routes administrateur" >> routes/web.php  
    echo "require __DIR__.'/admin.php';" >> routes/web.php
    echo "✅ Inclusion admin.php ajoutée à web.php"
else
    echo "✅ admin.php déjà inclus dans web.php"
fi

# 2. Corriger spécifiquement la syntaxe Blade problématique
echo ""
echo "📝 2. Correction syntaxe Blade dans les vues admin..."

# Corriger toutes les vues avec syntaxe :create-route incorrecte
find resources/views/admin -name "*.blade.php" -exec grep -l ":create-route.*route(" {} \; | while read file; do
    echo "Correction: $file"
    # Remplacer :create-route="route('...')" par create-route="{{ route('...') }}"
    sed -i 's/:create-route="route(\([^)]*\))"/create-route="{{ route(\1) }}"/g' "$file"
    sed -i 's/:create-route="route(\([^)]*\), \([^)]*\))"/create-route="{{ route(\1, \2) }}"/g' "$file"
done

# 3. Corriger spécifiquement le nom des routes dans les vues
echo ""
echo "📝 3. Correction noms de routes..."

# Corriger admin.user.create -> admin.users.create
find resources/views/admin -name "*.blade.php" -exec sed -i 's/admin\.user\.create/admin.users.create/g' {} \;
find resources/views/admin -name "*.blade.php" -exec sed -i 's/admin\.seminaire\.create/admin.seminaires.create/g' {} \;
find resources/views/admin -name "*.blade.php" -exec sed -i 's/admin\.paiement\.create/admin.paiements.create/g' {} \;
find resources/views/admin -name "*.blade.php" -exec sed -i 's/admin\.presence\.create/admin.presences.create/g' {} \;

# 4. Mettre à jour le composant module-header pour éviter les erreurs de syntaxe
echo ""
echo "📝 4. Optimisation composant module-header final..."
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
        
        @if($createRoute)
            @if($createPermission)
                @can($createPermission)
                    <a href="{{ $createRoute }}" 
                       class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg transition-colors duration-200 flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        <span>Nouveau</span>
                    </a>
                @endcan
            @else
                <a href="{{ $createRoute }}" 
                   class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg transition-colors duration-200 flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span>Nouveau</span>
                </a>
            @endif
        @endif
    </div>
</header>
COMPONENT_EOF

# 5. Vérifier et corriger les utilisations du composant dans les vues
echo ""
echo "📝 5. Correction utilisations composant module-header..."

# Exemple pour users/index.blade.php
if [ -f "resources/views/admin/users/index.blade.php" ]; then
    if grep -q "<x-module-header" resources/views/admin/users/index.blade.php; then
        sed -i 's/<x-module-header[^>]*>/<x-module-header module="user" title="Gestion des Utilisateurs" subtitle="Gestion des membres du réseau" create-route="{{ route('"'"'admin.users.create'"'"') }}" create-permission="create,App\\Models\\User" \/>/g' resources/views/admin/users/index.blade.php
        echo "✅ users/index.blade.php corrigé"
    fi
fi

# Nettoyer tous les caches
echo ""
echo "🧹 6. Nettoyage final..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

echo ""
echo "✅ CORRECTION ROUTES ADMIN TERMINÉE!"
