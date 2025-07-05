@props([
    'icon' => 'fa-folder-open',
    'title' => 'Aucune donnée',
    'description' => 'Il n\'y a rien à afficher pour le moment.',
    'action' => null
])

<div class="studiosdb-empty-state text-center py-12">
    <!-- Icône principale -->
    <div class="mx-auto w-16 h-16 bg-slate-700 rounded-full flex items-center justify-center mb-4">
        <i class="fas {{ $icon }} text-slate-400 text-2xl"></i>
    </div>
    
    <!-- Titre -->
    <h3 class="text-lg font-semibold text-slate-300 mb-2">{{ $title }}</h3>
    
    <!-- Description -->
    <p class="text-slate-500 mb-6 max-w-md mx-auto">{{ $description }}</p>
    
    <!-- Action optionnelle -->
    @if($action)
        <a href="{{ $action['url'] }}" 
           class="studiosdb-btn-primary inline-flex items-center space-x-2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
            @if(isset($action['icon']))
                <i class="fas {{ $action['icon'] }} text-sm"></i>
            @endif
            <span>{{ $action['text'] }}</span>
        </a>
    @endif
</div>
