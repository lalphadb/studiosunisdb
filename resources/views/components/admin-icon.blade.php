@props([
    'name' => 'default',
    'size' => 'w-6 h-6',
    'color' => 'text-current'
])

<i class="fas fa-{{ $name }} {{ $size }} {{ $color }}" {{ $attributes }}></i>
