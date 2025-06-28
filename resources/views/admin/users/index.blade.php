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
