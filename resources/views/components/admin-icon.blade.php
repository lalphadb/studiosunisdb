@props([
    'name' => 'heroicon-o-squares-2x2',
    'size' => 'md',
    'color' => 'text-gray-400'
])

@php
$sizeClasses = [
    'xs' => 'h-3 w-3',
    'sm' => 'h-4 w-4',
    'md' => 'h-5 w-5',
    'lg' => 'h-6 w-6',
    'xl' => 'h-8 w-8',
    '2xl' => 'h-10 w-10'
];

$iconClass = $sizeClasses[$size] ?? $sizeClasses['md'];
@endphp

<span {{ $attributes->merge(['class' => "$iconClass $color"]) }}>
    @if($name === 'karate')
        <!-- Icône karaté personnalisée -->
        <svg fill="currentColor" viewBox="0 0 24 24">
            <path d="M12 2C10.34 2 9 3.34 9 5C9 6.66 10.34 8 12 8C13.66 8 15 6.66 15 5C15 3.34 13.66 2 12 2M21 9H15L13.5 7.5C13.1 7.1 12.6 6.9 12 6.9S10.9 7.1 10.5 7.5L9 9H3V11H9.2L10.2 10C10.6 9.6 11 9.4 11.4 9.4S12.4 9.6 12.8 10L13.8 11H21V9M13.5 13.5L12 15.5L10.5 13.5L9 16L10.5 19L12 17L13.5 19L15 16L13.5 13.5Z"/>
        </svg>
    @elseif($name === 'belt')
        <!-- Icône ceinture -->
        <svg fill="currentColor" viewBox="0 0 24 24">
            <path d="M2 17H22V15H2V17M3.5 12H20.5V14H3.5V12M5 7H19V9H5V7Z"/>
        </svg>
    @elseif($name === 'tournament')
        <!-- Icône tournoi -->
        <svg fill="currentColor" viewBox="0 0 24 24">
            <path d="M2 17H6V15H2V17M2 19H8V21H2V19M2 13H4V11H2V13M13 13V21H11V13H5V11H19V13H13M2 7V9H6V7H2M19 17H22V15H19V17M15 19H22V21H15V19M20 13H22V11H20V13M19 7V9H22V7H19Z"/>
        </svg>
    @else
        <!-- Icône par défaut ou Heroicon -->
        <x-dynamic-component :component="$name" />
    @endif
</span>
