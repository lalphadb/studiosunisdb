@props(['model', 'module'])

<div class="relative" x-data="{ open: false }">
    <button @click="open = !open" class="text-gray-400 hover:text-gray-600">
        <i class="fas fa-ellipsis-v"></i>
    </button>
    <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-10">
        <div class="py-1">
            {{ $slot }}
        </div>
    </div>
</div>
