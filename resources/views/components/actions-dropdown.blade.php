@props([
    'model',
    'module',
    'canEdit' => true,
    'canDelete' => true,
    'canView' => true
])

{{-- Actions visibles au lieu du dropdown caché --}}
<div class="flex items-center justify-end space-x-2">
    @if($canView && auth()->user()->can('view', $model))
        <a href="{{ route("admin.{$module}.show", $model) }}" 
           class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-blue-400 bg-blue-500/10 border border-blue-500/20 rounded-lg hover:bg-blue-500/20 hover:text-blue-300 transition-colors"
           title="Voir les détails">
            <x-admin-icon name="eye" size="w-4 h-4" />
            <span class="ml-1.5 hidden lg:block">Voir</span>
        </a>
    @endif

    @if($canEdit && auth()->user()->can('update', $model))
        <a href="{{ route("admin.{$module}.edit", $model) }}" 
           class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 rounded-lg hover:bg-emerald-500/20 hover:text-emerald-300 transition-colors"
           title="Modifier">
            <x-admin-icon name="edit" size="w-4 h-4" />
            <span class="ml-1.5 hidden lg:block">Modifier</span>
        </a>
    @endif

    @if($canDelete && auth()->user()->can('delete', $model))
        <form method="POST" action="{{ route("admin.{$module}.destroy", $model) }}" 
              class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet élément ?')">
            @csrf @method('DELETE')
            <button type="submit" 
                    class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-red-400 bg-red-500/10 border border-red-500/20 rounded-lg hover:bg-red-500/20 hover:text-red-300 transition-colors"
                    title="Supprimer">
                <x-admin-icon name="trash" size="w-4 h-4" />
                <span class="ml-1.5 hidden lg:block">Supprimer</span>
            </button>
        </form>
    @endif
</div>
