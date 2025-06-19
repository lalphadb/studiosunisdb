@extends('layouts.admin')
@section('title', 'Logs d\'Activité')
@section('content')

<div class="flex justify-between items-center mb-8">
    <div>
        <h1 class="text-4xl font-bold text-white mb-2">📋 Logs d'Activité</h1>
        <p class="text-slate-400">Historique des actions du système</p>
    </div>
</div>

<!-- Filtres -->
<div class="card mb-6">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <!-- Filtre Type -->
        <div>
            <label for="type" class="block text-sm font-medium text-slate-300 mb-2">Type</label>
            <select name="type" id="type" class="w-full bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-white">
                <option value="">Tous les types</option>
                @foreach($logTypes as $type)
                    <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                        {{ ucfirst($type) }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Filtre Date -->
        <div>
            <label for="date" class="block text-sm font-medium text-slate-300 mb-2">Date</label>
            <input type="date" name="date" id="date" value="{{ request('date') }}" 
                   class="w-full bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-white">
        </div>

        <!-- Filtre Utilisateur -->
        <div>
            <label for="user" class="block text-sm font-medium text-slate-300 mb-2">Utilisateur</label>
            <select name="user" id="user" class="w-full bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-white">
                <option value="">Tous les utilisateurs</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ request('user') == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Boutons -->
        <div class="flex items-end space-x-2">
            <button type="submit" class="btn-primary">
                🔍 Filtrer
            </button>
            <a href="{{ route('admin.logs.index') }}" class="bg-slate-600 hover:bg-slate-700 text-white px-4 py-2 rounded-lg">
                ↻ Reset
            </a>
        </div>
    </form>
</div>

<!-- Liste des logs -->
<div class="card">
    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead class="bg-slate-700">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">
                        Date/Heure
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">
                        Utilisateur
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">
                        Action
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">
                        Cible
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">
                        Détails
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-700">
                @forelse($logs as $log)
                    <tr class="hover:bg-slate-700">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                            {{ $log->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                            @if($log->causer)
                                <div>
                                    <div class="font-medium">{{ $log->causer->name }}</div>
                                    @if($log->causer->ecole)
                                        <div class="text-xs text-slate-400">{{ $log->causer->ecole->code }}</div>
                                    @endif
                                </div>
                            @else
                                <span class="text-slate-400">Système</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                @if($log->log_name == 'created') bg-green-600 text-green-100
                                @elseif($log->log_name == 'updated') bg-blue-600 text-blue-100
                                @elseif($log->log_name == 'deleted') bg-red-600 text-red-100
                                @else bg-slate-600 text-slate-100
                                @endif">
                                {{ ucfirst($log->log_name) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                            @if($log->subject)
                                <div>
                                    <div class="font-medium">{{ class_basename($log->subject_type) }}</div>
                                    <div class="text-xs text-slate-400">
                                        @if($log->subject_type == 'App\Models\Membre' && $log->subject)
                                            {{ $log->subject->nom }} {{ $log->subject->prenom }}
                                        @elseif($log->subject_type == 'App\Models\Cours' && $log->subject)
                                            {{ $log->subject->nom }}
                                        @elseif($log->subject_type == 'App\Models\Paiement' && $log->subject)
                                            Paiement #{{ $log->subject->id }}
                                        @else
                                            ID: {{ $log->subject_id }}
                                        @endif
                                    </div>
                                </div>
                            @else
                                <span class="text-slate-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-white">
                            <div class="max-w-xs truncate">
                                {{ $log->description }}
                            </div>
                            @if($log->properties && count($log->properties) > 0)
                                <button onclick="toggleDetails('{{ $log->id }}')" 
                                        class="text-blue-400 hover:text-blue-300 text-xs mt-1">
                                    Voir détails
                                </button>
                                <div id="details-{{ $log->id }}" class="hidden mt-2 text-xs bg-slate-700 p-2 rounded max-w-md">
                                    <pre class="whitespace-pre-wrap text-slate-300">{{ json_encode($log->properties, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                </div>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-slate-400">
                            Aucun log trouvé
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4 px-6 pb-4">
        {{ $logs->appends(request()->query())->links() }}
    </div>
</div>

<script>
    function toggleDetails(logId) {
        const details = document.getElementById('details-' + logId);
        details.classList.toggle('hidden');
    }
</script>

@endsection
