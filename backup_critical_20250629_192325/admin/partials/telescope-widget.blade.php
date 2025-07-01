{{-- Widget Telescope pour SuperAdmin --}}
@if(auth()->user()->hasRole('superadmin'))
<div class="bg-gray-800 rounded-lg shadow-2xl border border-gray-700 overflow-hidden">
    <div class="bg-gradient-to-r from-red-500 to-orange-500 p-4">
        <h3 class="text-xl font-bold text-white flex items-center">
            <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd"></path>
            </svg>
            🔭 Telescope - Monitoring Système
        </h3>
    </div>
    
    <div class="p-6">
        @if(isset($telescopeStats) && is_array($telescopeStats))
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="text-center">
                    <div class="text-2xl font-bold text-red-400">{{ $telescopeStats['exceptions_count'] ?? 0 }}</div>
                    <div class="text-sm text-gray-400">Exceptions</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-yellow-400">{{ $telescopeStats['logs_count'] ?? 0 }}</div>
                    <div class="text-sm text-gray-400">Logs Erreur</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-orange-400">{{ $telescopeStats['slow_queries'] ?? 0 }}</div>
                    <div class="text-sm text-gray-400">Requêtes Lentes</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-red-400">{{ $telescopeStats['failed_requests'] ?? 0 }}</div>
                    <div class="text-sm text-gray-400">Requêtes Échouées</div>
                </div>
            </div>
            
            <div class="mt-4 flex justify-between items-center">
                <small class="text-gray-500">Dernières 24h</small>
                @if(config('telescope.enabled'))
                    <a href="/telescope" target="_blank" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                        🔭 Ouvrir Telescope
                    </a>
                @else
                    <span class="text-gray-500 text-sm">Telescope désactivé</span>
                @endif
            </div>
        @else
            <div class="text-center py-4">
                <div class="text-4xl mb-2">🔭</div>
                <p class="text-gray-400">Telescope non configuré</p>
                <p class="text-gray-500 text-sm">Les statistiques ne sont pas disponibles</p>
            </div>
        @endif
    </div>
</div>
@endif
