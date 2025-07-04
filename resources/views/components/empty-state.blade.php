@props([
    'icon' => 'default',
    'title' => 'Aucun élément',
    'description' => 'Aucun élément à afficher pour le moment.',
    'actionLabel' => null,
    'actionRoute' => null,
    'actionColor' => 'blue'
])

<div class="text-center py-16">
    <div class="w-20 h-20 bg-{{ $actionColor }}-500/10 rounded-2xl flex items-center justify-center mx-auto mb-6 border border-{{ $actionColor }}-500/20">
        <x-admin-icon :name="$icon" size="w-10 h-10" :color="'text-' . $actionColor . '-400'" />
    </div>
    
    <h3 class="text-2xl font-bold text-white mb-2">{{ $title }}</h3>
    <p class="text-slate-400 mb-8 text-lg max-w-md mx-auto">{{ $description }}</p>
    
    @if($actionLabel && $actionRoute)
        <div class="flex justify-center space-x-4">
            <a href="{{ $actionRoute }}" 
               class="inline-flex items-center px-6 py-3 text-sm font-medium text-{{ $actionColor }}-400 bg-{{ $actionColor }}-500/10 border border-{{ $actionColor }}-500/20 rounded-lg hover:bg-{{ $actionColor }}-500/20 hover:text-{{ $actionColor }}-300 transition-colors">
                <x-admin-icon name="plus" size="w-5 h-5" />
                <span class="ml-2">{{ $actionLabel }}</span>
            </a>
        </div>
    @endif
</div>
