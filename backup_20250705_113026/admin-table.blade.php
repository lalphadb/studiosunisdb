@props([
    'headers' => [],
    'searchable' => true,
    'filterable' => false,
    'exportable' => false
])

<div class="bg-slate-800/50 border border-slate-700/50 rounded-xl shadow-lg overflow-hidden">
    @if($searchable || $filterable || $exportable)
        <div class="flex items-center justify-between p-6 border-b border-slate-700/30">
            <div class="flex-1 max-w-lg">
                @if($searchable)
                    <div class="relative">
                        <input type="text" 
                               placeholder="Rechercher..." 
                               class="w-full pl-12 pr-4 py-2.5 bg-slate-700/50 border border-slate-600/50 rounded-lg text-slate-300 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <div class="absolute left-4 top-1/2 transform -translate-y-1/2">
                            <x-admin-icon name="search" size="w-5 h-5" color="text-slate-400" />
                        </div>
                    </div>
                @endif
            </div>
            
            <div class="flex items-center space-x-3 ml-6">
                @if($filterable)
                    <select class="px-3 py-2 bg-slate-700/50 border border-slate-600/50 rounded-lg text-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Tous les statuts</option>
                        <option value="actif">Actif</option>
                        <option value="inactif">Inactif</option>
                    </select>
                @endif
                
                @if($exportable)
                    <button class="inline-flex items-center px-3 py-2 text-sm font-medium text-blue-400 bg-blue-500/10 border border-blue-500/20 rounded-lg hover:bg-blue-500/20 hover:text-blue-300 transition-colors">
                        <x-admin-icon name="download" size="w-4 h-4" />
                        <span class="ml-2">Exporter</span>
                    </button>
                @endif
                
                <button class="inline-flex items-center px-3 py-2 text-sm font-medium text-slate-400 bg-slate-700/50 border border-slate-600/50 rounded-lg hover:bg-slate-600/50 hover:text-slate-300 transition-colors">
                    <x-admin-icon name="filter" size="w-4 h-4" />
                    <span class="ml-2">Filtrer</span>
                </button>
            </div>
        </div>
    @endif
    
    <div class="overflow-x-auto">
        <table class="w-full text-slate-300">
            @if(!empty($headers))
                <thead class="bg-slate-700/30">
                    <tr>
                        <th class="w-8 px-6 py-4">
                            <input type="checkbox" class="w-4 h-4 text-blue-500 bg-slate-700/50 border-slate-600/50 rounded focus:ring-blue-500">
                        </th>
                        @foreach($headers as $header)
                            <th class="px-6 py-4 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">
                                {{ $header }}
                            </th>
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
