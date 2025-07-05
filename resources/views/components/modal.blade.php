@props(['name', 'show' => false, 'maxWidth' => '2xl'])

<div x-data="{ show: @js($show) }" x-show="show" x-cloak class="fixed inset-0 z-50">
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75" x-show="show"></div>
    <div class="fixed inset-0 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div x-show="show" class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-{{ $maxWidth }}">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>
