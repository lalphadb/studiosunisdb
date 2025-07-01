@props([
    'model',
    'module',
    'canEdit' => true,
    'canDelete' => true,
    'canView' => true,
    'customActions' => []
])

<div class="relative" x-data="{ open: false }">
    <!-- Bouton plus gros et accessible -->
    <button @click="open = !open" 
            class="p-3 text-slate-400 hover:text-white rounded-lg hover:bg-slate-700/50 transition-colors flex items-center justify-center min-w-[40px] min-h-[40px]">
        <x-admin-icon name="menu" size="w-6 h-6" />
    </button>
    
    <div x-show="open" @click.away="open = false"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="absolute right-0 mt-2 w-56 bg-slate-700 rounded-lg shadow-lg border border-slate-600 z-50">
        
        <div class="py-2">
            @if($canView)
                <a href="{{ route('admin.' . $module . '.show', $model) }}" 
                   class="flex items-center px-4 py-3 text-slate-300 hover:bg-slate-600 hover:text-white transition-colors">
                    <x-admin-icon name="view" size="w-5 h-5" />
                    <span class="ml-3 font-medium">Voir les détails</span>
                </a>
            @endif
            
            @if($canEdit)
                <a href="{{ route('admin.' . $module . '.edit', $model) }}" 
                   class="flex items-center px-4 py-3 text-slate-300 hover:bg-slate-600 hover:text-white transition-colors">
                    <x-admin-icon name="edit" size="w-5 h-5" />
                    <span class="ml-3 font-medium">Modifier</span>
                </a>
            @endif
            
            @foreach($customActions as $action)
                <a href="{{ $action['url'] }}" 
                   class="flex items-center px-4 py-3 text-slate-300 hover:bg-slate-600 hover:text-white transition-colors">
                    <x-admin-icon :name="$action['icon']" size="w-5 h-5" />
                    <span class="ml-3 font-medium">{{ $action['label'] }}</span>
                </a>
            @endforeach
            
            @if($canDelete)
                <div class="border-t border-slate-600 my-2"></div>
                <form method="POST" action="{{ route('admin.' . $module . '.destroy', $model) }}" 
                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet élément ?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="flex items-center w-full px-4 py-3 text-slate-300 hover:bg-red-600 hover:text-white transition-colors">
                        <x-admin-icon name="delete" size="w-5 h-5" />
                        <span class="ml-3 font-medium">Supprimer</span>
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>
