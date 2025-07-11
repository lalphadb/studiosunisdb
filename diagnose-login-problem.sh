#!/bin/bash

echo "=== CONFIGURATION FINALE StudiosDB ==="
echo

# 1. Corriger les soft deletes
echo "🔧 Correction des soft deletes..."
php artisan tinker << 'TINKER'
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

$tables = ['cours', 'session_cours', 'presences', 'inscriptions_cours', 'paiements', 'seminaires', 'inscriptions_seminaires'];

foreach ($tables as $table) {
    if (Schema::hasTable($table) && !Schema::hasColumn($table, 'deleted_at')) {
        Schema::table($table, function (Blueprint $table) {
            $table->softDeletes();
        });
        echo "✓ Ajouté deleted_at à: $table\n";
    }
}
exit
TINKER

# 2. Configurer Fortify si nécessaire
echo -e "\n🔐 Configuration de l'authentification..."
if [ ! -f "app/Providers/FortifyServiceProvider.php" ]; then
    php artisan vendor:publish --provider="Laravel\Fortify\FortifyServiceProvider"
fi

# Activer Fortify dans config/app.php si nécessaire
if ! grep -q "FortifyServiceProvider" config/app.php; then
    echo "Ajout de FortifyServiceProvider..."
    sed -i '/App\\Providers\\RouteServiceProvider::class,/a\        App\\Providers\\FortifyServiceProvider::class,' config/app.php
fi

# 3. Créer les vues d'authentification de base
echo -e "\n📄 Création des vues d'authentification..."
mkdir -p resources/views/auth

# Login
cat > resources/views/auth/login.blade.php << 'BLADE'
<x-guest-layout>
    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Mot de passe')" />
            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ml-2 text-sm text-gray-600">{{ __('Se souvenir de moi') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button class="ml-3">
                {{ __('Connexion') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
BLADE

# 4. Créer le layout guest si manquant
if [ ! -f "resources/views/layouts/guest.blade.php" ]; then
    echo "📄 Création du layout guest..."
    cat > resources/views/layouts/guest.blade.php << 'BLADE'
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'StudiosDB') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
            <div>
                <a href="/">
                    <h1 class="text-4xl font-bold">StudiosDB</h1>
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
BLADE
fi

# 5. Créer les composants Blade manquants
echo -e "\n🧩 Création des composants Blade..."
mkdir -p resources/views/components

# nav-link
cat > resources/views/components/nav-link.blade.php << 'BLADE'
@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-indigo-400 text-sm font-medium leading-5 text-gray-900 focus:outline-none focus:border-indigo-700 transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
BLADE

# dropdown
cat > resources/views/components/dropdown.blade.php << 'BLADE'
@props(['align' => 'right', 'width' => '48', 'contentClasses' => 'py-1 bg-white'])

@php
switch ($align) {
    case 'left':
        $alignmentClasses = 'origin-top-left left-0';
        break;
    case 'top':
        $alignmentClasses = 'origin-top';
        break;
    case 'right':
    default:
        $alignmentClasses = 'origin-top-right right-0';
        break;
}

switch ($width) {
    case '48':
        $width = 'w-48';
        break;
}
@endphp

<div class="relative" x-data="{ open: false }" @click.outside="open = false" @close.stop="open = false">
    <div @click="open = ! open">
        {{ $trigger }}
    </div>

    <div x-show="open"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="transform opacity-0 scale-95"
            x-transition:enter-end="transform opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-75"
            x-transition:leave-start="transform opacity-100 scale-100"
            x-transition:leave-end="transform opacity-0 scale-95"
            class="absolute z-50 mt-2 {{ $width }} rounded-md shadow-lg {{ $alignmentClasses }}"
            style="display: none;"
            @click="open = false">
        <div class="rounded-md ring-1 ring-black ring-opacity-5 {{ $contentClasses }}">
            {{ $content }}
        </div>
    </div>
</div>
BLADE

# 6. Compiler les assets
echo -e "\n📦 Compilation des assets..."
if [ -f "package.json" ]; then
    npm install
    npm run build
else
    echo "⚠️  package.json non trouvé - assets non compilés"
fi

# 7. Tester l'accès
echo -e "\n✅ Configuration terminée!"
echo
echo "🔍 Vérification finale:"
php artisan route:list --path=admin --columns=method,uri,name,action | head -20

echo -e "\n🚀 Pour tester:"
echo "1. php artisan serve"
echo "2. Ouvrir http://localhost:8000/login"
echo "3. Se connecter avec un utilisateur admin"
echo
echo "👤 Utilisateurs de test disponibles:"
php artisan tinker --execute="
\$users = App\Models\User::with('roles')->get();
foreach(\$users as \$user) {
    if(\$user->hasRole(['super-admin', 'admin'])) {
        echo \$user->email . ' - Rôle: ' . \$user->roles->pluck('name')->implode(', ') . PHP_EOL;
    }
}
"

echo -e "\n📝 Mot de passe par défaut: password"
