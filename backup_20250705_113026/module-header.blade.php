@props([
    'module' => 'default',
    'title' => '',
    'subtitle' => '',
    'createRoute' => null,
    'createText' => 'Nouveau',
    'createPermission' => null,
    'breadcrumbs' => []
])

@php
$moduleColors = [
    'users' => ['primary' => 'cyan-500', 'secondary' => 'blue-600'],
    'ecoles' => ['primary' => 'emerald-500', 'secondary' => 'green-600'],
    'cours' => ['primary' => 'violet-500', 'secondary' => 'purple-600'],
    'sessions' => ['primary' => 'indigo-500', 'secondary' => 'blue-600'],
    'ceintures' => ['primary' => 'orange-500', 'secondary' => 'red-600'],
    'seminaires' => ['primary' => 'pink-500', 'secondary' => 'purple-600'],
    'paiements' => ['primary' => 'yellow-500', 'secondary' => 'orange-600'],
    'presences' => ['primary' => 'green-500', 'secondary' => 'emerald-600'],
    'dashboard' => ['primary' => 'blue-500', 'secondary' => 'indigo-600'],
    'default' => ['primary' => 'slate-500', 'secondary' => 'gray-600']
];

$colors = $moduleColors[$module] ?? $moduleColors['default'];

$iconMap = [
    'users' => 'fa-users',
    'ecoles' => 'fa-school',
    'cours' => 'fa-book-open',
    'sessions' => 'fa-calendar-alt',
    'ceintures' => 'fa-medal',
    'seminaires' => 'fa-graduation-cap',
    'paiements' => 'fa-credit-card',
    'presences' => 'fa-check-circle',
    'dashboard' => 'fa-chart-pie',
    'default' => 'fa-folder'
];
$icon = $iconMap[$module] ?? $iconMap['default'];
@endphp

<!-- Breadcrumb moderne -->
@if(!empty($breadcrumbs))
<nav class="studiosdb-breadcrumb mb-6" aria-label="Breadcrumb">
    <div class="flex items-center space-x-2 text-sm text-slate-400">
        @foreach($breadcrumbs as $index => $crumb)
            @if($index > 0)
                <i class="fas fa-chevron-right text-slate-600 text-xs"></i>
            @endif
            
            @if(isset($crumb['url']) && $crumb['url'])
                <a href="{{ $crumb['url'] }}" 
                   class="hover:text-{{ $colors['primary'] }} transition-colors duration-200 font-medium">
                    {{ $crumb['name'] }}
                </a>
            @else
                <span class="text-{{ $colors['primary'] }} font-semibold">{{ $crumb['name'] }}</span>
            @endif
        @endforeach
    </div>
</nav>
@endif

<!-- Header du module -->
<div class="studiosdb-module-header bg-gradient-to-r from-{{ $colors['primary'] }} via-{{ $colors['secondary'] }} to-slate-800 rounded-xl p-6 mb-6 text-white relative overflow-hidden shadow-lg">
    <!-- Pattern arrière-plan -->
    <div class="absolute inset-0 opacity-10">
        <div class="absolute inset-0" style="background-image: radial-gradient(circle at 25% 25%, white 2px, transparent 2px); background-size: 24px 24px;"></div>
    </div>
    
    <!-- Contenu -->
    <div class="relative z-10 flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <!-- Icône animée -->
            <div class="studiosdb-module-icon bg-white bg-opacity-20 backdrop-blur-sm rounded-xl p-3 transform hover:scale-105 transition-transform duration-200">
                <i class="fas {{ $icon }} text-2xl text-white drop-shadow-md"></i>
            </div>
            
            <!-- Titres -->
            <div>
                <h1 class="studiosdb-module-title text-3xl font-bold text-white tracking-tight drop-shadow-sm">
                    {{ $title }}
                </h1>
                @if($subtitle)
                    <p class="studiosdb-module-subtitle text-white text-opacity-90 text-base font-medium mt-1">
                        {{ $subtitle }}
                    </p>
                @endif
            </div>
        </div>
        
        <!-- Actions -->
        <div class="flex items-center space-x-3">
            @if($createRoute && (!$createPermission || auth()->user()->can($createPermission)))
                <a href="{{ $createRoute }}"
                   class="studiosdb-btn-create bg-white bg-opacity-20 hover:bg-opacity-30 backdrop-blur-sm text-white border border-white border-opacity-30 px-6 py-3 rounded-lg font-semibold transition-all duration-200 flex items-center space-x-2 group">
                    <i class="fas fa-plus text-sm group-hover:rotate-90 transition-transform duration-200"></i>
                    <span>{{ $createText }}</span>
                </a>
            @endif
            
            <!-- Actions supplémentaires via slot -->
            {{ $actions ?? '' }}
        </div>
    </div>
    
    <!-- Gradient overlay -->
    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-transparent to-slate-900 opacity-30 pointer-events-none"></div>
</div>

<!-- Section pour les métriques du module -->
{{ $metrics ?? '' }}

<!-- Section pour les filtres/recherche -->
{{ $filters ?? '' }}
