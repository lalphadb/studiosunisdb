@props([
    'type' => 'button',
    'href' => null,
    'disabled' => false,
    'size' => 'md',
    'variant' => 'default'
])

@php
$sizeClasses = [
    'sm' => 'px-3 py-1.5 text-xs',
    'md' => 'px-4 py-2 text-sm',
    'lg' => 'px-6 py-3 text-base',
];

$variantClasses = [
    'default' => 'bg-white border-gray-300 text-gray-700 hover:bg-gray-50 focus:ring-gray-500',
    'light' => 'bg-gray-100 border-gray-200 text-gray-600 hover:bg-gray-200 focus:ring-gray-400',
    'outline' => 'bg-transparent border-gray-400 text-gray-600 hover:bg-gray-50 focus:ring-gray-500',
];

$baseClasses = 'inline-flex items-center justify-center border rounded-lg font-medium tracking-wide transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed shadow-sm hover:shadow-md';

$classes = $baseClasses . ' ' . ($sizeClasses[$size] ?? $sizeClasses['md']) . ' ' . ($variantClasses[$variant] ?? $variantClasses['default']);
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
