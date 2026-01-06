@props(['active' => false])

@php
$classes = ($active ?? false)
            ? 'group flex items-center px-2 py-2 text-sm font-medium bg-gray-900 text-white rounded-md'
            : 'group flex items-center px-2 py-2 text-sm font-medium text-gray-300 rounded-md hover:bg-gray-700 hover:text-white';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
