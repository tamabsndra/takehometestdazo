@props(['title' => null, 'footer' => null, 'padding' => 'p-6'])

<div {{ $attributes->merge(['class' => 'bg-surface border border-border rounded-xl shadow-sm overflow-hidden']) }}>
    @if($title)
        <div class="px-6 py-4 border-b border-border bg-gray-50/50">
            <h3 class="text-base font-semibold text-gray-900 leading-6">{{ $title }}</h3>
            @if(isset($headerActions))
                <div class="ml-auto">
                    {{ $headerActions }}
                </div>
            @endif
        </div>
    @endif

    <div class="{{ $padding }}">
        {{ $slot }}
    </div>

    @if($footer)
        <div class="px-6 py-4 bg-gray-50 border-t border-border">
            {{ $footer }}
        </div>
    @endif
</div>
