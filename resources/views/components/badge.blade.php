@props(['status', 'type' => 'default'])

@php
    $classes = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium capitalize';
    
    // Status color mapping
    $colors = [
        'active' => 'bg-green-100 text-green-800',
        'inactive' => 'bg-red-100 text-red-800',
        'pusat' => 'bg-purple-100 text-purple-800',
        'cabang' => 'bg-blue-100 text-blue-800',
        'retail' => 'bg-gray-100 text-gray-800',
        'admin' => 'bg-indigo-100 text-indigo-800',
        'cashier' => 'bg-yellow-100 text-yellow-800',
        'pending' => 'bg-yellow-100 text-yellow-800',
        'success' => 'bg-green-100 text-green-800',
        'failed' => 'bg-red-100 text-red-800',
    ];

    $colorClass = $colors[strtolower($status)] ?? 'bg-gray-100 text-gray-800';
@endphp

<span {{ $attributes->merge(['class' => "$classes $colorClass"]) }}>
    {{ $status }}
</span>
