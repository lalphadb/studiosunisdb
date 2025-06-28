#!/bin/bash
echo "🔧 CORRECTION FINALE SYNTAXE BLADE"
echo "=================================="

# 1. Identifier et corriger le fichier problématique
echo "📝 Correction erreur ligne 26..."

# Le problème est dans un fichier avec une syntaxe de loop/pagination incorrecte
# Chercher les fichiers avec $loop ou pagination problématique
find resources/views/admin -name "*.blade.php" -exec grep -l "\$loop.*first" {} \; | while read file; do
    echo "Correction de: $file"
    
    # Corriger la syntaxe $loop->first problématique  
    sed -i "s/<?php echo e(\$loop->first ? '' : 'border-t'); ?>/{{ \$loop->first ? '' : 'border-t' }}/g" "$file"
    sed -i "s/<?php echo e(\$key); ?>/{{ \$key }}/g" "$file"
    
    # Corriger les échappements incorrects
    sed -i 's/<?php \$__env->addLoop(\$__currentLoopData); \$__env->getLastLoop(); foreach(\$__currentLoopData as \$key => \$item): \$__env->incrementLoopIndices(); \$loop = \$__env->getLastLoop(); ?>/\@foreach(\$items as \$key => \$item)/g' "$file"
    
    echo "✅ $file corrigé"
done

# 2. Corriger spécifiquement la syntaxe de pagination dans les vues index
echo ""
echo "📝 Correction tables et boucles..."

# Chercher les vues avec des tables et corriger la syntaxe
find resources/views/admin -name "index.blade.php" -exec grep -l "table\|tbody" {} \; | while read file; do
    echo "Vérification: $file"
    
    # Remplacer les syntaxes problématiques par du Blade correct
    if grep -q "<?php.*\$loop.*?>" "$file"; then
        # Créer une version corrigée du fichier
        cp "$file" "$file.backup"
        
        # Corriger les syntaxes de boucle Blade
        sed -i 's/<?php echo e(\([^)]*\)); ?>/{{ \1 }}/g' "$file"
        sed -i 's/<?php.*\$loop.*?>/{{ \$loop->first ? "" : "border-t" }}/g' "$file"
        
        echo "✅ Syntaxe Blade corrigée dans $file"
    fi
done

# 3. Créer un template propre pour les vues index qui ont des problèmes
echo ""
echo "📝 Création template index propre..."

cat > /tmp/clean_index_template.blade.php << 'TEMPLATE_EOF'
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
                           placeholder="Rechercher..."
                           class="bg-slate-700 border border-slate-600 text-white rounded-lg px-4 py-2 flex-1">
                    <button type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                        Rechercher
                    </button>
                </div>
            </form>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-900">
                        <tr class="divide-x divide-slate-700">
                            <th class="px-4 py-3 text-left text-white font-semibold">Nom</th>
                            <th class="px-4 py-3 text-left text-white font-semibold">Email</th>
                            <th class="px-4 py-3 text-left text-white font-semibold">École</th>
                            <th class="px-4 py-3 text-left text-white font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-700">
                        @forelse($users as $user)
                            <tr class="hover:bg-slate-700/50">
                                <td class="px-4 py-3 text-white">{{ $user->name }}</td>
                                <td class="px-4 py-3 text-slate-300">{{ $user->email }}</td>
                                <td class="px-4 py-3 text-slate-300">
                                    {{ $user->ecole->nom ?? 'N/A' }}
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.users.show', $user) }}" 
                                           class="text-blue-400 hover:text-blue-300">
                                            Voir
                                        </a>
                                        <a href="{{ route('admin.users.edit', $user) }}" 
                                           class="text-green-400 hover:text-green-300">
                                            Modifier
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-8 text-center text-slate-400">
                                    <div class="flex flex-col items-center space-y-3">
                                        <span class="text-4xl">👤</span>
                                        <p>Aucun utilisateur trouvé</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if(method_exists($users, 'links'))
                <div class="mt-6">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
TEMPLATE_EOF

# 4. Appliquer le template propre si nécessaire
if [ -f "resources/views/admin/users/index.blade.php" ]; then
    if grep -q "<?php.*\$loop.*?>" resources/views/admin/users/index.blade.php; then
        echo "Remplacement de users/index.blade.php par template propre..."
        cp /tmp/clean_index_template.blade.php resources/views/admin/users/index.blade.php
        echo "✅ users/index.blade.php remplacé par template propre"
    fi
fi

# 5. Nettoyer complètement
echo ""
echo "🧹 Nettoyage final complet..."
php artisan config:clear
php artisan route:clear  
php artisan view:clear
php artisan cache:clear

rm -f /tmp/clean_index_template.blade.php

echo ""
echo "✅ CORRECTION SYNTAXE BLADE TERMINÉE!"
echo "L'erreur ligne 26 devrait être résolue."
