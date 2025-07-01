@props([
    'title',
    'value',
    'icon',
    'color' => 'blue',
    'trend' => null,
    'description' => null,
    'link' => null
])

@php
$cardClass = "studiosdb-card hover:scale-105 cursor-pointer" . ($link ? " group" : "");
$colorClasses = [
    'blue' => 'text-blue-400 bg-blue-500/20 border-blue-500/30',
    'emerald' => 'text-emerald-400 bg-emerald-500/20 border-emerald-500/30',
    'violet' => 'text-violet-400 bg-violet-500/20 border-violet-500/30',
    'rose' => 'text-rose-400 bg-rose-500/20 border-rose-500/30',
    'orange' => 'text-orange-400 bg-orange-500/20 border-orange-500/30',
    'amber' => 'text-amber-400 bg-amber-500/20 border-amber-500/30',
    'cyan' => 'text-cyan-400 bg-cyan-500/20 border-cyan-500/30',
];
$iconBgClass = $colorClasses[$color] ?? $colorClasses['blue'];
@endphp

@if($link)
    <a href="{{ $link }}" class="{{ $cardClass }}">
@else
    <div class="{{ $cardClass }}">
@endif
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <p class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-2">{{ $title }}</p>
                <p class="text-3xl font-bold text-white group-hover:text-{{ $color }}-400 transition-colors">{{ $value }}</p>
                
                @if($description)
                    <p class="text-xs text-slate-500 mt-1">{{ $description }}</p>
                @endif
                
                @if($trend)
                    <div class="flex items-center mt-3 text-xs">
                        @if($trend['direction'] === 'up')
                            <svg class="w-4 h-4 text-green-400 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                            <span class="text-green-400 font-medium">+{{ $trend['value'] }}%</span>
                        @elseif($trend['direction'] === 'down')
                            <svg class="w-4 h-4 text-red-400 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                            </svg>
                            <span class="text-red-400 font-medium">-{{ $trend['value'] }}%</span>
                        @else
                            <span class="text-slate-400 font-medium">{{ $trend['value'] }}%</span>
                        @endif
                        <span class="text-slate-500 ml-2">{{ $trend['period'] ?? 'ce mois' }}</span>
                    </div>
                @endif
            </div>
            
            <div class="w-14 h-14 {{ $iconBgClass }} rounded-2xl flex items-center justify-center border">
                <x-admin-icon :name="$icon" size="w-7 h-7" :color="'text-' . $color . '-400'" />
            </div>
        </div>
@if($link)
    </a>
@else
    </div>
@endif
