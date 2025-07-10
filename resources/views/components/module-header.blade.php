@props([
    'title' => 'Module',
    'subtitle' => null,
    'icon' => 'heroicon-o-squares-2x2',
    'actions' => null
])

<div class="bg-white shadow-sm border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="py-6">
            <div class="md:flex md:items-center md:justify-between">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center">
                        @if($icon)
                            <div class="flex-shrink-0">
                                <x-heroicon-o-squares-2x2 class="h-8 w-8 text-gray-400" />
                            </div>
                        @endif
                        <div class="ml-4">
                            <h1 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                                {{ $title }}
                            </h1>
                            @if($subtitle)
                                <p class="mt-1 text-sm text-gray-500">
                                    {{ $subtitle }}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
                @if($actions)
                    <div class="mt-4 flex md:mt-0 md:ml-4">
                        {{ $actions }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
