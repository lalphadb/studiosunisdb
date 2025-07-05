@props([
    'icon' => 'fa-folder-open',
    'title' => 'Aucune donnée',
    'description' => 'Il n\'y a rien à afficher pour le moment.',
    'action' => null
])

<div class="text-center py-12">
    <i class="fas {{ $icon }} text-gray-400 text-6xl mb-4"></i>
    <h3 class="text-lg font-medium text-gray-900 mb-2">{{ $title }}</h3>
    <p class="text-gray-500 mb-4">{{ $description }}</p>
    @if($action)
        {{ $action }}
    @endif
</div>
