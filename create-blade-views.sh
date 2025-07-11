#!/bin/bash

echo "=== CRÉATION DES VUES BLADE ADMIN ==="
echo

# 1. Créer la structure des dossiers
echo "📁 Création de la structure des dossiers..."
mkdir -p resources/views/admin/{layouts,dashboard,users,cours,ecoles,presences,components}
mkdir -p resources/views/auth
mkdir -p resources/views/components

# 2. Layout principal admin
echo "📄 Création du layout admin..."
cat > resources/views/admin/layouts/app.blade.php << 'BLADE'
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'StudiosDB') }} - Admin</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        <!-- Navigation -->
        <nav class="bg-white border-b border-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <!-- Logo -->
                        <div class="shrink-0 flex items-center">
                            <a href="{{ route('admin.dashboard') }}">
                                <h2 class="font-bold text-xl">StudiosDB Admin</h2>
                            </a>
                        </div>

                        <!-- Navigation Links -->
                        <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                            <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                                Dashboard
                            </x-nav-link>
                            
                            @can('users.index')
                            <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                                Utilisateurs
                            </x-nav-link>
                            @endcan
                            
                            @can('cours.index')
                            <x-nav-link :href="route('admin.cours.index')" :active="request()->routeIs('admin.cours.*')">
                                Cours
                            </x-nav-link>
                            @endcan
                            
                            @can('presences.index')
                            <x-nav-link :href="route('admin.presences.index')" :active="request()->routeIs('admin.presences.*')">
                                Présences
                            </x-nav-link>
                            @endcan
                            
                            @role('super-admin')
                            <x-nav-link :href="route('admin.ecoles.index')" :active="request()->routeIs('admin.ecoles.*')">
                                Écoles
                            </x-nav-link>
                            @endrole
                        </div>
                    </div>

                    <!-- Settings Dropdown -->
                    <div class="hidden sm:flex sm:items-center sm:ml-6">
                        <div class="ml-3 relative">
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                        <div>{{ Auth::user()->nom_complet }}</div>
                                        <div class="ml-1">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </button>
                                </x-slot>

                                <x-slot name="content">
                                    <x-dropdown-link :href="route('profile.edit')">
                                        Profil
                                    </x-dropdown-link>

                                    <!-- Authentication -->
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <x-dropdown-link :href="route('logout')"
                                                onclick="event.preventDefault();
                                                        this.closest('form').submit();">
                                            Déconnexion
                                        </x-dropdown-link>
                                    </form>
                                </x-slot>
                            </x-dropdown>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main>
            @if(session('success'))
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            {{ $slot }}
        </main>
    </div>
</body>
</html>
BLADE

# 3. Dashboard
echo "📊 Création du dashboard..."
cat > resources/views/admin/dashboard/index.blade.php << 'BLADE'
<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Statistiques -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-2xl font-bold text-gray-900">{{ $stats['users'] ?? 0 }}</div>
                        <div class="text-sm text-gray-600">Utilisateurs</div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-2xl font-bold text-gray-900">{{ $stats['cours'] ?? 0 }}</div>
                        <div class="text-sm text-gray-600">Cours</div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-2xl font-bold text-gray-900">{{ $stats['sessions'] ?? 0 }}</div>
                        <div class="text-sm text-gray-600">Sessions ce mois</div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-2xl font-bold text-gray-900">{{ $stats['ecoles'] ?? 0 }}</div>
                        <div class="text-sm text-gray-600">Écoles</div>
                    </div>
                </div>
            </div>

            <!-- Activité récente -->
            <div class="mt-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Activité récente</h3>
                        <div class="space-y-3">
                            @forelse($recentActivity ?? [] as $activity)
                                <div class="flex items-center text-sm">
                                    <span class="text-gray-600">{{ $activity->description }}</span>
                                    <span class="ml-auto text-gray-400">{{ $activity->created_at->diffForHumans() }}</span>
                                </div>
                            @empty
                                <p class="text-gray-500">Aucune activité récente</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
BLADE

# 4. Index des utilisateurs
echo "👥 Création de la vue users.index..."
cat > resources/views/admin/users/index.blade.php << 'BLADE'
<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Utilisateurs
            </h2>
            @can('users.create')
            <a href="{{ route('admin.users.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Nouvel utilisateur
            </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nom
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Email
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    École
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Rôle
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="relative px-6 py-3">
                                    <span class="sr-only">Actions</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($users as $user)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $user->nom_complet }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $user->email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $user->ecole->nom ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        @foreach($user->roles as $role)
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                {{ $role->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($user->actif)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Actif
                                        </span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Inactif
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('admin.users.show', $user) }}" class="text-indigo-600 hover:text-indigo-900 mr-2">Voir</a>
                                    @can('users.edit')
                                    <a href="{{ route('admin.users.edit', $user) }}" class="text-indigo-600 hover:text-indigo-900 mr-2">Modifier</a>
                                    @endcan
                                    @can('users.delete')
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Êtes-vous sûr?')">
                                            Supprimer
                                        </button>
                                    </form>
                                    @endcan
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
BLADE

# 5. Créer le composant x-admin-layout
echo "🧩 Création du composant admin-layout..."
cat > resources/views/components/admin-layout.blade.php << 'BLADE'
@props(['title' => null])

<x-app-layout>
    @if($title)
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $title }}
            </h2>
        </x-slot>
    @endif

    {{ $slot }}
</x-app-layout>
BLADE

# 6. Créer app-layout si manquant
if [ ! -f "resources/views/layouts/app.blade.php" ]; then
    echo "📄 Création du layout app principal..."
    mkdir -p resources/views/layouts
    cp resources/views/admin/layouts/app.blade.php resources/views/layouts/app.blade.php
fi

# 7. Créer les autres vues de base
echo "📝 Création des formulaires de base..."

# Formulaire de création d'utilisateur
cat > resources/views/admin/users/create.blade.php << 'BLADE'
<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Créer un utilisateur
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('admin.users.store') }}">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nom -->
                            <div>
                                <label for="nom" class="block text-sm font-medium text-gray-700">Nom</label>
                                <input type="text" name="nom" id="nom" value="{{ old('nom') }}" required
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                @error('nom')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Prénom -->
                            <div>
                                <label for="prenom" class="block text-sm font-medium text-gray-700">Prénom</label>
                                <input type="text" name="prenom" id="prenom" value="{{ old('prenom') }}" required
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                @error('prenom')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- École -->
                            <div>
                                <label for="ecole_id" class="block text-sm font-medium text-gray-700">École</label>
                                <select name="ecole_id" id="ecole_id" required
                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="">Sélectionner une école</option>
                                    @foreach($ecoles as $ecole)
                                        <option value="{{ $ecole->id }}" {{ old('ecole_id') == $ecole->id ? 'selected' : '' }}>
                                            {{ $ecole->nom }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('ecole_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Mot de passe -->
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700">Mot de passe</label>
                                <input type="password" name="password" id="password" required
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                @error('password')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Confirmation -->
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirmer le mot de passe</label>
                                <input type="password" name="password_confirmation" id="password_confirmation" required
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>
                        </div>

                        <div class="mt-6 flex items-center justify-end">
                            <a href="{{ route('admin.users.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">
                                Annuler
                            </a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Créer l'utilisateur
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
BLADE

echo -e "\n✅ Structure des vues créée avec succès!"
echo
echo "📁 Structure créée:"
echo "  resources/views/admin/"
echo "  ├── layouts/app.blade.php"
echo "  ├── dashboard/index.blade.php"
echo "  ├── users/"
echo "  │   ├── index.blade.php"
echo "  │   └── create.blade.php"
echo "  └── components/"
echo
echo "🎯 Prochaines étapes:"
echo "1. Installer Tailwind CSS: npm install && npm run build"
echo "2. Tester l'accès admin: php artisan serve"
echo "3. Créer les vues manquantes (edit, show, etc.)"
echo "4. Personnaliser le design selon vos besoins"
