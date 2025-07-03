@props([
    'item', 
    'module', 
    'canView' => true, 
    'canEdit' => true, 
    'canDelete' => true,
    'extraActions' => []
])

<div class="flex items-center justify-end space-x-1">
    @if($canView)
    <a href="{{ route("admin.{$module}.show", $item) }}" 
       class="inline-flex items-center p-2 text-blue-400 hover:text-blue-300 hover:bg-blue-500/20 rounded-md transition-all duration-200 group"
       title="Voir les détails">
        <x-admin-icon name="eye" class="w-4 h-4" />
        <span class="ml-1 text-xs opacity-0 group-hover:opacity-100 transition-opacity">Voir</span>
    </a>
    @endif

    @if($canEdit)
    <a href="{{ route("admin.{$module}.edit", $item) }}" 
       class="inline-flex items-center p-2 text-emerald-400 hover:text-emerald-300 hover:bg-emerald-500/20 rounded-md transition-all duration-200 group"
       title="Modifier">
        <x-admin-icon name="edit" class="w-4 h-4" />
        <span class="ml-1 text-xs opacity-0 group-hover:opacity-100 transition-opacity">Modifier</span>
    </a>
    @endif

    @if($canDelete)
    <button onclick="confirmDelete{{ Str::studly($module) }}('{{ $item->id }}', '{{ $item->name ?? $item->nom ?? $item->titre }}')"
            class="inline-flex items-center p-2 text-red-400 hover:text-red-300 hover:bg-red-500/20 rounded-md transition-all duration-200 group"
            title="Supprimer">
        <x-admin-icon name="trash" class="w-4 h-4" />
        <span class="ml-1 text-xs opacity-0 group-hover:opacity-100 transition-opacity">Supprimer</span>
    </button>
    @endif

    {{-- Actions supplémentaires par module --}}
    @foreach($extraActions as $action)
    <a href="{{ $action['url'] }}" 
       class="inline-flex items-center p-2 text-{{ $action['color'] ?? 'gray' }}-400 hover:text-{{ $action['color'] ?? 'gray' }}-300 hover:bg-{{ $action['color'] ?? 'gray' }}-500/20 rounded-md transition-all duration-200 group"
       title="{{ $action['title'] }}">
        <x-admin-icon name="{{ $action['icon'] }}" class="w-4 h-4" />
        <span class="ml-1 text-xs opacity-0 group-hover:opacity-100 transition-opacity">{{ $action['label'] }}</span>
    </a>
    @endforeach
</div>

{{-- Scripts de confirmation par module --}}
@push('scripts')
<script>
function confirmDelete{{ Str::studly($module) }}(itemId, itemName) {
    if (confirm(`Êtes-vous sûr de vouloir supprimer "${itemName}" ?`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/{{ $module }}/${itemId}`;
        form.innerHTML = `
            @csrf
            @method('DELETE')
        `;
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endpush
