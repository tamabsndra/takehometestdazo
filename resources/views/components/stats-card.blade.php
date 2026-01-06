@props(['title', 'value', 'icon' => null, 'color' => 'blue', 'trend' => null, 'trendUp' => true])

@php
    $colorClasses = [
        'blue' => 'bg-blue-50 text-blue-600',
        'green' => 'bg-green-50 text-green-600',
        'purple' => 'bg-purple-50 text-purple-600',
        'yellow' => 'bg-yellow-50 text-yellow-600',
        'red' => 'bg-red-50 text-red-600',
    ][$color] ?? 'bg-blue-50 text-blue-600';
@endphp

<div class="bg-surface rounded-xl border border-border shadow-sm p-6">
    <div class="flex items-center">
        @if($icon)
            <div class="flex-shrink-0 p-3 rounded-lg {{ $colorClasses }}">
                {!! $icon !!}
            </div>
        @endif
        
        <div class="{{ $icon ? 'ml-4' : '' }} flex-1">
            <p class="text-sm font-medium text-gray-500 truncate">{{ $title }}</p>
            <div class="flex items-baseline">
                <p class="text-2xl font-semibold text-gray-900">{{ $value }}</p>
                @if($trend)
                    <p class="ml-2 flex items-baseline text-sm font-semibold {{ $trendUp ? 'text-green-600' : 'text-red-600' }}">
                        @if($trendUp)
                            <svg class="self-center flex-shrink-0 h-4 w-4 text-green-500" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        @else
                            <svg class="self-center flex-shrink-0 h-4 w-4 text-red-500" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                <path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        @endif
                        <span class="sr-only">{{ $trendUp ? 'Increased' : 'Decreased' }} by</span>
                        {{ $trend }}
                    </p>
                @endif
            </div>
        </div>
    </div>
</div>
