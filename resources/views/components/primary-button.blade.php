@props([
    'type' => 'submit',
    'href' => null,
    'disabled' => false,
    'size' => 'md',
    'variant' => 'blue'
])

@php
$sizeClasses = [
    'sm' => 'px-3 py-1.5 text-xs',
    'md' => 'px-4 py-2 text-sm',
    'lg' => 'px-6 py-3 text-base',
];

$variantClasses = [
    'blue' => 'bg-blue-600 border-blue-600 text-white hover:bg-blue-700 focus:ring-blue-500',
    'green' => 'bg-green-600 border-green-600 text-white hover:bg-green-700 focus:ring-green-500',
    'purple' => 'bg-purple-600 border-purple-600 text-white hover:bg-purple-700 focus:ring-purple-500',
    'indigo' => 'bg-indigo-600 border-indigo-600 text-white hover:bg-indigo-700 focus:ring-indigo-500',
];

$baseClasses = 'inline-flex items-center justify-center border rounded-lg font-medium tracking-wide transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed shadow-sm hover:shadow-md transform hover:-translate-y-0.5';

$classes = $baseClasses . ' ' . ($sizeClasses[$size] ?? $sizeClasses['md']) . ' ' . ($variantClasses[$variant] ?? $variantClasses['blue']);
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
