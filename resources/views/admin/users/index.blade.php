@extends('layouts.admin')

@section('title', 'Gestion des Utilisateurs')
@section('subtitle', 'Liste et administration des membres')

@section('content')
<div class="space-y-6">
    <!-- En-tête avec actions -->
    <div class="flex flex-col lg:flex-flex flex-wrap -mx-2 lg:items-center lg:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-white mb-2">Gestion des Utilisateurs</h1>
            <p class="text-slate-400">{{ $users->total() }} utilisateur(s) trouvé(s)</p>
        </div>
        
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.users.create') }}" 
               class="studiosdb-btn-users">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Nouvel Utilisateur
            </a>
            
            <button onclick="exportUsers()" 
                    class="studiosdb-btn bg-slate-600 text-white hover:bg-slate-700">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Export
            </button>
        </div>
    </div>

    <!-- Filtres et recherche -->
    <div class="studiosdb-bg-white shadow-md rounded-lg overflow-hidden">
        <form method="GET" action="{{ route('admin.users.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Recherche -->
                <div>
                    <label for="search" class="block text-sm font-medium text-slate-300 mb-2">Rechercher</label>
                    <input type="text" 
                           id="search" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Nom ou email..."
                           class="studiosdb-input">
                </div>
                
                <!-- Filtre par rôle -->
                <div>
                    <label for="role" class="block text-sm font-medium text-slate-300 mb-2">Rôle</label>
                    <select id="role" name="role" class="studiosdb-input">
                        <option value="">Tous les rôles</option>
                        @if(isset($filterData['roles']))
                            @foreach($filterData['roles'] as $role => $label)
                                <option value="{{ $role }}" {{ request('role') === $role ? 'selected' : '' }}>
                                    {{ ucfirst($label) }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>
                
                <!-- Filtre par école (superadmin seulement) -->
                @if(auth()->user()->hasRole('superadmin') && isset($filterData['ecoles']) && $filterData['ecoles']->count() > 0)
                <div>
                    <label for="ecole_id" class="block text-sm font-medium text-slate-300 mb-2">École</label>
                    <select id="ecole_id" name="ecole_id" class="studiosdb-input">
                        <option value="">Toutes les écoles</option>
                        @foreach($filterData['ecoles'] as $id => $nom)
                            <option value="{{ $id }}" {{ request('ecole_id') == $id ? 'selected' : '' }}>
                                {{ $nom }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif
                
                <!-- Filtre par statut -->
                <div>
                    <label for="status" class="block text-sm font-medium text-slate-300 mb-2">Statut</label>
                    <select id="status" name="status" class="studiosdb-input">
                        <option value="">Tous les statuts</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Actifs</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactifs</option>
                    </select>
                </div>
            </div>
            
            <div class="flex items-center space-x-3">
                <button type="submit" class="studiosdb-btn-primary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Filtrer
                </button>
                
                <a href="{{ route('admin.users.index') }}" class="studiosdb-btn-cancel">
                    Réinitialiser
                </a>
            </div>
        </form>
    </div>

    <!-- Table des utilisateurs -->
    <div class="studiosdb-bg-white shadow-md rounded-lg overflow-hidden overflow-hidden">
        <div class="overflow-x-auto">
            <min-w-full divide-y divide-gray-200 class="studiosdb-min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="text-left">Utilisateur</th>
                        <th class="text-left">École</th>
                        <th class="text-left">Rôle</th>
                        <th class="text-left">Statut</th>
                        <th class="text-left">Inscription</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700/30">
                    @forelse($users as $user)
                    <tr class="hover:bg-slate-700/20">
                        <td class="studiosdb-min-w-full divide-y divide-gray-200 td">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center">
                                    <span class="text-white font-medium">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </span>
                                </div>
                                <div>
                                    <div class="font-medium text-white">{{ $user->name }}</div>
                                    <div class="text-sm text-slate-400">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        
                        <td class="studiosdb-min-w-full divide-y divide-gray-200 td">
                            @if($user->ecole)
                                <div class="text-white">{{ $user->ecole->nom }}</div>
                                <div class="text-xs text-slate-400">ID: {{ $user->ecole->id }}</div>
                            @else
                                <span class="text-slate-500">Aucune école</span>
                            @endif
                        </td>
                        
                        <td class="studiosdb-min-w-full divide-y divide-gray-200 td">
                            @if($user->roles->count() > 0)
                                @foreach($user->roles as $role)
                                    <span class="studiosdb-badge 
                                        @if($role->name === 'superadmin') bg-red-500/20 text-red-400 border-red-500/30
                                        @elseif($role->name === 'admin_ecole') bg-blue-500/20 text-blue-400 border-blue-500/30
                                        @elseif($role->name === 'instructeur') bg-purple-500/20 text-purple-400 border-purple-500/30
                                        @elseif($role->name === 'membre') bg-green-500/20 text-green-400 border-green-500/30
                                        @else bg-slate-500/20 text-slate-400 border-slate-500/30
                                        @endif">
                                        {{ ucfirst($role->name) }}
                                    </span>
                                @endforeach
                            @else
                                <span class="text-slate-500">Aucun rôle</span>
                            @endif
                        </td>
                        
                        <td class="studiosdb-min-w-full divide-y divide-gray-200 td">
                            @if($user->email_verified_at)
                                <span class="studiosdb-badge-active">Actif</span>
                            @else
                                <span class="studiosdb-badge-inactive">Inactif</span>
                            @endif
                        </td>
                        
                        <td class="studiosdb-min-w-full divide-y divide-gray-200 td">
                            <div class="text-white">{{ $user->created_at->format('d/m/Y') }}</div>
                            <div class="text-xs text-slate-400">{{ $user->created_at->format('H:i') }}</div>
                        </td>
                        
                        <td class="studiosdb-min-w-full divide-y divide-gray-200 td text-right">
                            <div class="flex items-center justify-end space-x-2">
                                <a href="{{ route('admin.users.show', $user) }}" 
                                   class="text-slate-400 hover:text-blue-400 transition-colors"
                                   title="Voir">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                                
                                <a href="{{ route('admin.users.edit', $user) }}" 
                                   class="text-slate-400 hover:text-amber-400 transition-colors"
                                   title="Modifier">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                
                                @if($user->id !== auth()->id())
                                <button onclick="toggleUserStatus({{ $user->id }})" 
                                        class="text-slate-400 hover:text-{{ $user->email_verified_at ? 'red' : 'green' }}-400 transition-colors"
                                        title="{{ $user->email_verified_at ? 'Désactiver' : 'Activer' }}">
                                    @if($user->email_verified_at)
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    @endif
                                </button>
                                
                                <button onclick="deleteUser({{ $user->id }}, '{{ $user->name }}')" 
                                        class="text-slate-400 hover:text-red-400 transition-colors"
                                        title="Supprimer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-12 text-slate-400">
                            <svg class="mx-auto h-12 w-12 text-slate-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                            </svg>
                            <p class="text-lg font-medium">Aucun utilisateur trouvé</p>
                            <p class="text-sm">Modifiez vos critères de recherche ou créez un nouvel utilisateur</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </min-w-full divide-y divide-gray-200>
        </div>
        
        <!-- Pagination -->
        @if($users->hasPages())
        <div class="border-t border-slate-700/30 px-6 py-4">
            {{ $users->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Scripts JavaScript -->
<script>
function exportUsers() {
    const form = document.querySelector('form');
    const params = new URLSearchParams(new FormData(form));
    
    fetch('{{ route("admin.users.export") }}?' + params, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Export préparé: ' + data.count + ' utilisateurs');
        } else {
            alert('Erreur: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Erreur export:', error);
        alert('Erreur lors de l\'export');
    });
}

function toggleUserStatus(userId) {
    if (!confirm('Êtes-vous sûr de vouloir changer le statut de cet utilisateur ?')) {
        return;
    }
    
    fetch(`{{ route('admin.users.index') }}/${userId}/toggle-status`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Erreur: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Erreur lors du changement de statut');
    });
}

function deleteUser(userId, userName) {
    if (!confirm(`Êtes-vous sûr de vouloir supprimer l'utilisateur "${userName}" ?\n\nCette action est irréversible.`)) {
        return;
    }
    
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `{{ route('admin.users.index') }}/${userId}`;
    
    const methodInput = document.createElement('input');
    methodInput.type = 'hidden';
    methodInput.name = '_method';
    methodInput.value = 'DELETE';
    
    const tokenInput = document.createElement('input');
    tokenInput.type = 'hidden';
    tokenInput.name = '_token';
    tokenInput.value = document.querySelector('meta[name="csrf-token"]').content;
    
    form.appendChild(methodInput);
    form.appendChild(tokenInput);
    document.body.appendChild(form);
    form.submit();
}
</script>
@endsection