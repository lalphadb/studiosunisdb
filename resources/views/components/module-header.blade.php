@props([
    'module',
    'title', 
    'subtitle',
    'createRoute' => null,
    'createPermission' => null
])

@php
$colors = [
    'dashboard' => ['primary' => 'blue-500', 'secondary' => 'cyan-600', 'icon' => '📊'],
    'ecole' => ['primary' => 'green-500', 'secondary' => 'emerald-600', 'icon' => '🏫'],
    'user' => ['primary' => 'blue-500', 'secondary' => 'cyan-600', 'icon' => '👤'],
    'ceinture' => ['primary' => 'orange-500', 'secondary' => 'red-600', 'icon' => '🥋'],
    'cours' => ['primary' => 'purple-500', 'secondary' => 'indigo-600', 'icon' => '📚'],
    'seminaire' => ['primary' => 'pink-500', 'secondary' => 'purple-600', 'icon' => '🎯'],
    'paiement' => ['primary' => 'yellow-500', 'secondary' => 'orange-600', 'icon' => '💰'],
    'presence' => ['primary' => 'teal-500', 'secondary' => 'green-600', 'icon' => '✅']
];
$moduleConfig = $colors[$module] ?? $colors['dashboard'];
@endphp

<header class="bg-gradient-to-r from-{{ $moduleConfig['primary'] }} via-{{ $moduleConfig['secondary'] }} to-transparent rounded-xl p-6 text-white relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-transparent to-slate-900 opacity-60"></div>
    
    <div class="relative z-10 flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <span class="text-3xl">{{ $moduleConfig['icon'] }}</span>
            <div>
                <h1 class="text-2xl font-bold">{{ $title }}</h1>
                <p class="text-white/80">{{ $subtitle }}</p>
            </div>
        </div>
        
        @if($createRoute)
            @if($createPermission)
                @can($createPermission)
                    <a href="{{ $createRoute }}" 
                       class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg transition-colors duration-200 flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        <span>Nouveau</span>
                    </a>
                @endcan
            @else
                <a href="{{ $createRoute }}" 
                   class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg transition-colors duration-200 flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span>Nouveau</span>
                </a>
            @endif
        @endif
    </div>
</header>
