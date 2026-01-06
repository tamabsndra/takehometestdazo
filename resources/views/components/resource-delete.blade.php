@props(['resource', 'item' => null, 'warning' => null])

<div x-data="{ showDelete: false, deleteItem: null }">
    {{ $slot }}
    
    <!-- Delete Confirmation Modal -->
    <x-confirmation-modal 
        show="showDelete" 
        :title="'Delete ' . ucfirst($resource)"
        onClose="showDelete = false"
    >
        <p class="text-sm text-gray-500">
            Are you sure you want to delete 
            <span class="font-bold text-gray-900" x-text="deleteItem?.name"></span>?
            @if($warning)
                {{ $warning }}
            @else
                This action cannot be undone.
            @endif
        </p>

        <x-slot:footer>
            <form x-bind:action="deleteItem?.deleteUrl" method="POST" class="w-full sm:w-auto">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-danger w-full justify-center">
                    Delete
                </button>
            </form>
            <button 
                @click="showDelete = false" 
                type="button" 
                class="btn-secondary w-full sm:w-auto mt-3 sm:mt-0"
            >
                Cancel
            </button>
        </x-slot:footer>
    </x-confirmation-modal>
</div>
