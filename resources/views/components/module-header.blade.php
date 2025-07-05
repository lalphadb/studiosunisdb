@props([
    'module' => 'default',
    'title' => '',
    'subtitle' => '',
    'createRoute' => null,
    'breadcrumbs' => []
])

<div class="bg-white shadow">
    <div class="px-4 py-6 sm:px-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $title }}</h1>
                @if($subtitle)
                <p class="text-gray-600">{{ $subtitle }}</p>
                @endif
            </div>
            @if($createRoute)
            <a href="{{ $createRoute }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                Ajouter
            </a>
            @endif
        </div>
    </div>
</div>
