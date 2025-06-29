@props([
    'module',
    'title', 
    'subtitle',
    'createRoute' => null,
    'createPermission' => null,
    'createText' => 'Nouveau'
])

@php
// Configuration des icônes par module selon StudiosDB v5.7.1
$moduleIcons = [
    'dashboard' => '📊',
    'ecoles' => '🏫',
    'ecole' => '🏫',
    'users' => '👤',
    'user' => '👤',
    'ceintures' => '🥋',
    'ceinture' => '🥋',
    'cours' => '📚',
    'seminaires' => '🎯',
    'seminaire' => '🎯',
    'paiements' => '💰',
    'paiement' => '💰',
    'presences' => '✅',
    'presence' => '✅'
];
$icon = $moduleIcons[$module] ?? '📊';
@endphp

<!-- Header avec classes CSS complètes pour chaque module -->
@if($module === 'dashboard')
    <header class="bg-gradient-to-r from-blue-500 via-cyan-600 to-transparent rounded-xl p-6 text-white relative overflow-hidden">
@elseif($module === 'ecoles' || $module === 'ecole')
    <header class="bg-gradient-to-r from-green-500 via-emerald-600 to-transparent rounded-xl p-6 text-white relative overflow-hidden">
@elseif($module === 'users' || $module === 'user')
    <header class="bg-gradient-to-r from-blue-500 via-cyan-600 to-transparent rounded-xl p-6 text-white relative overflow-hidden">
@elseif($module === 'ceintures' || $module === 'ceinture')
    <header class="bg-gradient-to-r from-orange-500 via-red-600 to-transparent rounded-xl p-6 text-white relative overflow-hidden">
@elseif($module === 'cours')
    <header class="bg-gradient-to-r from-purple-500 via-indigo-600 to-transparent rounded-xl p-6 text-white relative overflow-hidden">
@elseif($module === 'seminaires' || $module === 'seminaire')
    <header class="bg-gradient-to-r from-pink-500 via-purple-600 to-transparent rounded-xl p-6 text-white relative overflow-hidden">
@elseif($module === 'paiements' || $module === 'paiement')
    <header class="bg-gradient-to-r from-yellow-500 via-orange-600 to-transparent rounded-xl p-6 text-white relative overflow-hidden">
@elseif($module === 'presences' || $module === 'presence')
    <header class="bg-gradient-to-r from-teal-500 via-green-600 to-transparent rounded-xl p-6 text-white relative overflow-hidden">
@else
    <header class="bg-gradient-to-r from-blue-500 via-cyan-600 to-transparent rounded-xl p-6 text-white relative overflow-hidden">
@endif
    <!-- Overlay pour fade vers le noir/transparent -->
    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-transparent to-slate-900 opacity-60"></div>
    
    <!-- Contenu principal -->
    <div class="relative z-10 flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <!-- Icône du module -->
            <div class="flex-shrink-0">
                <div class="h-16 w-16 bg-white/10 rounded-xl flex items-center justify-center backdrop-blur-sm">
                    <span class="text-3xl">{{ $icon }}</span>
                </div>
            </div>
            
            <!-- Titre et sous-titre -->
            <div>
                <h1 class="text-3xl font-bold text-white">{{ $title }}</h1>
                @if($subtitle)
                    <p class="text-white/80 mt-1 text-lg">{{ $subtitle }}</p>
                @endif
            </div>
        </div>
        
        <!-- Bouton d'action -->
        @if($createRoute)
            @if($createPermission)
                @can($createPermission)
                    <div class="flex-shrink-0">
                        <a href="{{ $createRoute }}" 
                           class="inline-flex items-center px-6 py-3 bg-white/20 hover:bg-white/30 text-white font-medium rounded-lg transition-all duration-200 backdrop-blur-sm border border-white/20 hover:border-white/40">
                            <span class="mr-2">➕</span>
                            <span>{{ $createText }}</span>
                        </a>
                    </div>
                @endcan
            @else
                <div class="flex-shrink-0">
                    <a href="{{ $createRoute }}" 
                       class="inline-flex items-center px-6 py-3 bg-white/20 hover:bg-white/30 text-white font-medium rounded-lg transition-all duration-200 backdrop-blur-sm border border-white/20 hover:border-white/40">
                        <span class="mr-2">➕</span>
                        <span>{{ $createText }}</span>
                    </a>
                </div>
            @endif
        @endif
    </div>
</header>
