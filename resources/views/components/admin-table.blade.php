@props([
    'headers' => [],
    'searchable' => true,
    'filterable' => true,
    'exportable' => false
])

<div class="studiosdb-card">
    @if($searchable || $filterable || $exportable)
        <div class="flex items-center justify-between mb-6 pb-6 border-b border-slate-700/30">
            <div class="flex-1 max-w-lg">
                @if($searchable)
                    <div class="relative">
                        <input type="text" 
                               placeholder="Rechercher..." 
                               class="studiosdb-input w-full pl-12">
                        <div class="absolute left-4 top-1/2 transform -translate-y-1/2">
                            <x-admin-icon name="search" size="w-5 h-5" color="text-slate-400" />
                        </div>
                    </div>
                @endif
            </div>
            
            <div class="flex items-center space-x-3 ml-6">
                @if($filterable)
                    <select class="studiosdb-select">
                        <option value="">Tous les statuts</option>
                        <option value="actif">Actif</option>
                        <option value="inactif">Inactif</option>
                    </select>
                @endif
                
                @if($exportable)
                    <button class="studiosdb-btn studiosdb-btn-users">
                        <x-admin-icon name="download" size="w-4 h-4" />
                        <span class="ml-2">Exporter</span>
                    </button>
                @endif
                
                <button class="studiosdb-btn studiosdb-btn-users">
                    <x-admin-icon name="filter" size="w-4 h-4" />
                    <span class="ml-2">Filtrer</span>
                </button>
            </div>
        </div>
    @endif
    
    <div class="overflow-x-auto">
        <table class="studiosdb-table">
            @if(!empty($headers))
                <thead>
                    <tr>
                        <th class="w-4">
                            <input type="checkbox" class="w-4 h-4 text-blue-500 bg-slate-700/50 border-slate-600/50 rounded focus:ring-blue-500">
                        </th>
                        @foreach($headers as $header)
                            <th>{{ $header }}</th>
                        @endforeach
                    </tr>
                </thead>
            @endif
            <tbody class="divide-y divide-slate-700/30">
                {{ $slot }}
            </tbody>
        </table>
    </div>
</div>
