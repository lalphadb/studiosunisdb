@props([
    'module' => 'default',
    'title' => '',
    'subtitle' => '',
    'createRoute' => null,
    'createText' => 'Nouveau',
    'createPermission' => null
])

@php
$moduleColors = [
    'users' => ['primary' => 'blue-500', 'secondary' => 'cyan-600'],
    'ecoles' => ['primary' => 'green-500', 'secondary' => 'emerald-600'],
    'cours' => ['primary' => 'purple-500', 'secondary' => 'indigo-600'],
    'ceintures' => ['primary' => 'orange-500', 'secondary' => 'red-600'],
    'seminaires' => ['primary' => 'pink-500', 'secondary' => 'purple-600'],
    'paiements' => ['primary' => 'yellow-500', 'secondary' => 'orange-600'],
    'presences' => ['primary' => 'teal-500', 'secondary' => 'green-600'],
    'default' => ['primary' => 'blue-500', 'secondary' => 'cyan-600']
];

$colors = $moduleColors[$module] ?? $moduleColors['default'];
$gradient = "from-{$colors['primary']} via-{$colors['secondary']} to-transparent";

$iconMap = [
    'users' => '👤',
    'ecoles' => '🏫',
    'cours' => '📚',
    'ceintures' => '🥋',
    'seminaires' => '🎯',
    'paiements' => '💰',
    'presences' => '✅',
    'default' => '📋'
];
$icon = $iconMap[$module] ?? $iconMap['default'];
@endphp

<div class="bg-gradient-to-r {{ $gradient }} rounded-xl p-6 mb-6 text-white relative overflow-hidden shadow-lg transition-all duration-300 hover:shadow-2xl">
    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-transparent to-slate-900 opacity-60"></div>
    <div class="relative z-10 flex justify-between items-center">
        <div class="flex items-center space-x-4">
            <span class="text-3xl drop-shadow-md animate-pulse" aria-hidden="true">{{ $icon }}</span>
            <div>
                <h1 class="text-2xl font-bold text-white tracking-tight">{{ $title }}</h1>
                @if($subtitle)
                    <p class="text-sm text-slate-300 font-medium">{{ $subtitle }}</p>
                @endif
            </div>
        </div>
        @if($createRoute && (!$createPermission || auth()->user()->can($createPermission)))
            <a href="{{ $createRoute }}"
               class="bg-{{ $colors['primary'] }}-600 hover:bg-{{ $colors['primary'] }}-700 text-white px-5 py-2 rounded-lg font-semibold transition-all duration-200 border border-{{ $colors['primary'] }}-800/30 flex items-center space-x-2"
               aria-label="Créer un nouvel élément pour {{ $module }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span>{{ $createText }}</span>
            </a>
        @endif
    </div>
</div>
