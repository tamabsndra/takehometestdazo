@props(['title', 'description' => null, 'action' => null])

<div class="sm:flex sm:items-center sm:justify-between mb-8">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">{{ $title }}</h1>
        @if($description)
            <p class="mt-2 text-sm text-gray-700">{{ $description }}</p>
        @endif
    </div>
    @if($action)
        <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
            {{ $action }}
        </div>
    @endif
</div>
