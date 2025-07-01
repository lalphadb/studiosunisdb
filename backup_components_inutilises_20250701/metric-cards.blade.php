@props(['metrics'])

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    @foreach($metrics as $metric)
        <div class="group relative overflow-hidden rounded-2xl bg-slate-800/40 hover:bg-slate-800/60 border border-slate-700/30 hover:border-slate-600/50 transition-all duration-500 backdrop-blur-xl">
            <!-- Effet de lueur -->
            <div class="absolute inset-0 bg-gradient-to-br from-white/[0.02] to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
            
            <!-- Contenu -->
            <div class="relative p-6">
                <div class="flex items-center justify-between">
                    <div class="space-y-2">
                        <p class="text-sm font-medium text-slate-400 tracking-wide uppercase">{{ $metric['label'] }}</p>
                        <p class="text-3xl font-bold text-slate-100 tracking-tight">{{ $metric['value'] }}</p>
                        @if(isset($metric['subtitle']))
                            <p class="text-xs text-slate-500 font-medium">{{ $metric['subtitle'] }}</p>
                        @endif
                    </div>
                    
                    <!-- Icône avec design sophistiqué -->
                    <div class="relative">
                        <div class="h-14 w-14 rounded-2xl bg-gradient-to-br from-{{ $metric['color'] ?? 'slate' }}-500/10 to-{{ $metric['color'] ?? 'slate' }}-600/5 border border-{{ $metric['color'] ?? 'slate' }}-500/20 flex items-center justify-center backdrop-blur-sm group-hover:scale-110 transition-transform duration-300">
                            <span class="text-2xl filter drop-shadow-sm">{{ $metric['icon'] }}</span>
                        </div>
                        <!-- Point lumineux -->
                        <div class="absolute -top-1 -right-1 h-3 w-3 rounded-full bg-{{ $metric['color'] ?? 'slate' }}-500/30 border border-{{ $metric['color'] ?? 'slate' }}-400/40"></div>
                    </div>
                </div>
            </div>
            
            <!-- Bordure inférieure colorée subtile -->
            <div class="absolute bottom-0 left-0 right-0 h-0.5 bg-gradient-to-r from-{{ $metric['color'] ?? 'slate' }}-500/20 via-{{ $metric['color'] ?? 'slate' }}-400/30 to-{{ $metric['color'] ?? 'slate' }}-500/20"></div>
        </div>
    @endforeach
</div>
