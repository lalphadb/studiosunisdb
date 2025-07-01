@props([
    'type' => 'button',
    'href' => null,
    'disabled' => false,
    'size' => 'md',
    'variant' => 'solid'
])

@php
$sizeClasses = [
    'sm' => 'px-3 py-1.5 text-xs',
    'md' => 'px-4 py-2 text-sm',
    'lg' => 'px-6 py-3 text-base',
];

$variantClasses = [
    'solid' => 'bg-red-600 border-red-600 text-white hover:bg-red-700 focus:ring-red-500',
    'outline' => 'bg-transparent border-red-500 text-red-600 hover:bg-red-50 focus:ring-red-500',
    'ghost' => 'bg-red-50 border-red-200 text-red-700 hover:bg-red-100 focus:ring-red-500',
];

$baseClasses = 'inline-flex items-center justify-center border rounded-lg font-medium tracking-wide transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed shadow-sm hover:shadow-md';

$classes = $baseClasses . ' ' . ($sizeClasses[$size] ?? $sizeClasses['md']) . ' ' . ($variantClasses[$variant] ?? $variantClasses['solid']);
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $disabled ? 'disabled' : '' }} {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif
