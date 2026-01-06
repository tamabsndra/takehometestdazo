<x-dashboard-layout>
    <x-slot:title>Manage Stores</x-slot:title>

    <x-resource-delete resource="store" warning="This will also delete all associated users and cannot be undone.">
        <x-page-header 
            title="Stores" 
            description="A list of all stores including their name, level, and assigned users."
        >
            <x-slot:action>
                <a href="{{ route('stores.create') }}" class="btn-primary inline-flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Add Store
                </a>
            </x-slot:action>
        </x-page-header>

        <x-card class="bg-white" padding="p-0">
            <x-table :headers="['Name', 'Level', 'Users', 'Status', 'Actions']">
                @foreach($stores as $store)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ is_object($store) ? $store->name : $store['name'] }}</div>
                        <div class="text-sm text-gray-500">{{ is_object($store) ? ($store->address ?? 'No address') : ($store['address'] ?? 'No address') }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <x-badge :status="is_object($store) ? $store->level : $store['level']" />
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ is_object($store) ? (is_array($store->users) ? count($store->users) : $store->users->count()) : count($store['users'] ?? []) }} users
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            Active
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex justify-end space-x-2">
                            <x-button variant="outline" size="sm" onclick="window.location='{{ route('stores.edit', is_object($store) ? $store->id : $store['id']) }}'">
                                Edit
                            </x-button>
                            <x-button 
                                variant="danger" 
                                size="sm" 
                                @click="showDelete = true; deleteItem = { name: '{{ is_object($store) ? $store->name : $store['name'] }}', deleteUrl: '{{ route('stores.destroy', is_object($store) ? $store->id : $store['id']) }}' }"
                            >
                                Delete
                            </x-button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </x-table>
            
            <x-slot:footer>
                {{ $stores->links() }}
            </x-slot:footer>
        </x-card>
    </x-resource-delete>
</x-dashboard-layout>

