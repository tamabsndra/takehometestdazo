@props(['variant' => 'primary', 'size' => 'md', 'type' => 'button', 'fullWidth' => false, 'icon' => null])

@php
    $baseClasses = 'inline-flex items-center justify-center font-medium rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed';
    
    $variants = [
        'primary' => 'bg-primary-600 hover:bg-primary-700 text-white focus:ring-primary-500',
        'secondary' => 'bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 focus:ring-primary-500',
        'danger' => 'bg-danger hover:bg-red-600 text-white focus:ring-red-500',
        'success' => 'bg-success hover:bg-green-600 text-white focus:ring-green-500',
        'warning' => 'bg-warning hover:bg-yellow-500 text-white focus:ring-yellow-500',
        'outline' => 'bg-transparent border border-primary-600 text-primary-600 hover:bg-primary-50 focus:ring-primary-500',
    ];

    $sizes = [
        'sm' => 'px-3 py-1.5 text-xs',
        'md' => 'px-4 py-2 text-sm',
        'lg' => 'px-6 py-3 text-base',
    ];

    $classes = $baseClasses . ' ' . ($variants[$variant] ?? $variants['primary']) . ' ' . ($sizes[$size] ?? $sizes['md']) . ' ' . ($fullWidth ? 'w-full' : '');
@endphp

<button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
    @if($icon)
        <span class="mr-2">{!! $icon !!}</span>
    @endif
    {{ $slot }}
</button>
