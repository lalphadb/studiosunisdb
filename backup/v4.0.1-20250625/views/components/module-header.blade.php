@props(['title', 'subtitle', 'icon', 'gradient' => 'from-purple-600 to-blue-600', 'createRoute' => null, 'createText' => 'Nouveau'])

<div class="bg-gradient-to-r {{ $gradient }} rounded-xl p-6 text-white shadow-xl mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold flex items-center">
                <span class="mr-3">{{ $icon }}</span>
                {{ $title }}
            </h1>
            <p class="text-blue-100 text-lg mt-2">{{ $subtitle }}</p>
        </div>
        @if($createRoute)
            <div class="flex space-x-3">
                <a href="{{ $createRoute }}" 
                   class="bg-white bg-opacity-20 hover:bg-opacity-30 px-6 py-3 rounded-lg font-medium transition-all flex items-center space-x-2">
                    <span>➕</span>
                    <span>{{ $createText }}</span>
                </a>
            </div>
        @endif
    </div>
</div>
