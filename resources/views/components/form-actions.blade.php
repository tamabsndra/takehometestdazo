@props(['submitLabel' => 'Save Changes'])

<div class="mt-8 flex justify-end space-x-3">
    <x-button variant="secondary" type="button" onclick="window.history.back()">
        Cancel
    </x-button>
    <x-button type="submit">
        {{ $submitLabel }}
    </x-button>
</div>
