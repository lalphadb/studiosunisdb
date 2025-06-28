#!/bin/bash
echo "🔧 CORRECTION RADICALE VUES BLADE"
echo "================================"

# 1. Supprimer complètement le cache des vues compilées
echo "📝 1. Suppression cache vues compilées..."
rm -rf storage/framework/views/*
echo "✅ Cache vues supprimé"

# 2. Identifier et corriger tous les fichiers Blade problématiques
echo ""
echo "📝 2. Correction de tous les fichiers Blade..."

# Chercher tous les fichiers avec des syntaxes PHP problématiques
find resources/views -name "*.blade.php" -exec grep -l "<?php.*echo.*?>" {} \; | while read file; do
    echo "Correction: $file"
    # Supprimer toutes les syntaxes PHP échappées incorrectement
    sed -i 's/<?php echo e(\([^)]*\)); ?>/{{ \1 }}/g' "$file"
    sed -i 's/<?php.*\$__env.*?>//g' "$file"
    sed -i 's/<?php.*foreach.*?>/\@foreach/g' "$file"
    sed -i 's/<?php.*endforeach.*?>/\@endforeach/g' "$file"
    sed -i 's/<?php.*\$loop.*?>/{{ \$loop->first ? "" : "border-t" }}/g' "$file"
done

# 3. Recréer complètement la vue users/index.blade.php
echo ""
echo "📝 3. Recréation vue users/index propre..."

cat > resources/views/admin/users/index.blade.php << 'USERS_INDEX'
@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6">
    <x-module-header 
        module="user"
        title="Gestion des Utilisateurs" 
        subtitle="Gestion des membres du réseau"
        create-route="{{ route('admin.users.create') }}"
        create-permission="create,App\Models\User"
    />

    <div class="mt-6 bg-slate-800 rounded-xl border border-slate-700">
        <div class="p-6">
            <!-- Recherche -->
            <form method="GET" class="mb-6">
                <div class="flex gap-4">
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Rechercher un utilisateur..."
                           class="bg-slate-700 border border-slate-600 text-white rounded-lg px-4 py-2 flex-1 focus:border-blue-500">
                    <button type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors">
                        Rechercher
                    </button>
                </div>
            </form>

            @if($users->count() > 0)
                <!-- Table des utilisateurs -->
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-900">
                            <tr class="divide-x divide-slate-700">
                                <th class="px-4 py-3 text-left text-white font-semibold">Nom</th>
                                <th class="px-4 py-3 text-left text-white font-semibold">Email</th>
                                <th class="px-4 py-3 text-left text-white font-semibold">École</th>
                                <th class="px-4 py-3 text-left text-white font-semibold">Rôle</th>
                                <th class="px-4 py-3 text-left text-white font-semibold">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-700">
                            @foreach($users as $user)
                                <tr class="hover:bg-slate-700/50">
                                    <td class="px-4 py-3 text-white font-medium">
                                        {{ $user->name }}
                                        @if(!$user->active)
                                            <span class="ml-2 text-xs bg-red-600 text-white px-2 py-1 rounded">Inactif</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-slate-300">
                                        {{ $user->email }}
                                    </td>
                                    <td class="px-4 py-3 text-slate-300">
                                        {{ $user->ecole->nom ?? 'Aucune école' }}
                                    </td>
                                    <td class="px-4 py-3">
                                        @if($user->roles->count() > 0)
                                            @foreach($user->roles as $role)
                                                <span class="inline-block text-xs bg-blue-600 text-white px-2 py-1 rounded mr-1">
                                                    {{ $role->name }}
                                                </span>
                                            @endforeach
                                        @else
                                            <span class="text-xs text-slate-400">Aucun rôle</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex space-x-2">
                                            @can('view', $user)
                                                <a href="{{ route('admin.users.show', $user) }}" 
                                                   class="text-blue-400 hover:text-blue-300 text-sm">
                                                    Voir
                                                </a>
                                            @endcan
                                            
                                            @can('update', $user)
                                                <a href="{{ route('admin.users.edit', $user) }}" 
                                                   class="text-green-400 hover:text-green-300 text-sm">
                                                    Modifier
                                                </a>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if(method_exists($users, 'links'))
                    <div class="mt-6">
                        {{ $users->links() }}
                    </div>
                @endif
            @else
                <!-- État vide -->
                <div class="text-center py-12">
                    <div class="text-6xl mb-4">👤</div>
                    <h3 class="text-xl font-semibold text-white mb-2">Aucun utilisateur trouvé</h3>
                    <p class="text-slate-400 mb-6">
                        @if(request('search'))
                            Aucun résultat pour "{{ request('search') }}"
                        @else
                            Commencez par créer votre premier utilisateur
                        @endif
                    </p>
                    
                    @can('create', App\Models\User::class)
                        <a href="{{ route('admin.users.create') }}" 
                           class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition-colors">
                            Créer un utilisateur
                        </a>
                    @endcan
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
USERS_INDEX

echo "✅ Vue users/index recréée"

# 4. Vérifier et corriger le layout admin
echo ""
echo "📝 4. Vérification layout admin..."

if [ ! -f "resources/views/layouts/admin.blade.php" ]; then
    echo "Création layout admin manquant..."
    
    cat > resources/views/layouts/admin.blade.php << 'ADMIN_LAYOUT'
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>StudiosUnisDB - Administration</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-slate-900 text-white">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="bg-slate-800 border-b border-slate-700">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center space-x-4">
                        <span class="text-2xl">🥋</span>
                        <h1 class="text-xl font-bold text-white">StudiosUnisDB</h1>
                        <span class="text-sm text-slate-400">Administration</span>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <span class="text-slate-300">{{ auth()->user()->name }}</span>
                        <span class="text-xs text-slate-400">
                            @if(auth()->user()->roles->count() > 0)
                                {{ auth()->user()->roles->first()->name }}
                            @endif
                        </span>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-slate-300 hover:text-white text-sm">
                                Déconnexion
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>
        
        <!-- Contenu principal -->
        <main>
            @yield('content')
        </main>
    </div>
</body>
</html>
ADMIN_LAYOUT
    
    echo "✅ Layout admin créé"
fi

# 5. Nettoyer complètement tous les caches
echo ""
echo "🧹 5. Nettoyage complet..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Supprimer à nouveau le cache compilé
rm -rf storage/framework/views/*

echo ""
echo "✅ CORRECTION RADICALE TERMINÉE!"
echo ""
echo "🔄 REDÉMARRAGE SERVEUR NÉCESSAIRE..."
