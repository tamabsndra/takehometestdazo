<x-dashboard-layout>
    <x-slot:title>Manage Products</x-slot:title>

    <x-resource-delete resource="product">
        <x-page-header 
            title="Products" 
            description="Manage your store's product inventory."
        >
            <x-slot:action>
                <a href="{{ route('products.create') }}" class="btn-primary inline-flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Add Product
                </a>
            </x-slot:action>
        </x-page-header>

        @if(auth()->user()->isSuperAdmin())
        <!-- Store Filter for Super Admin -->
        <div class="bg-white p-4 rounded-lg shadow-sm mb-6 border border-gray-200">
            <form action="{{ route('products.index') }}" method="GET" class="flex gap-4">
                <div class="flex-1">
                    <select name="store_id" class="block w-full px-4 py-2 rounded-lg border-gray-300 focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                        <option value="">All Stores</option>
                        @foreach($stores as $store)
                            <option value="{{ $store->id }}" {{ request('store_id') == $store->id ? 'selected' : '' }}>
                                {{ $store->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn-primary">Filter</button>
                @if(request()->has('store_id'))
                    <a href="{{ route('products.index') }}" class="btn-secondary flex items-center justify-center">Reset</a>
                @endif
            </form>
        </div>
        @endif

        <x-card class="bg-white" padding="p-0">
            <x-table :headers="auth()->user()->isSuperAdmin() ? ['Product Name', 'SKU', 'Store', 'Price', 'Actions'] : ['Product Name', 'SKU', 'Price', 'Actions']">
                @forelse($products as $product)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $product->name }}</div>
                        <div class="text-xs text-gray-500 truncate max-w-xs">{{ $product->description }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $product->sku ?? '-' }}
                    </td>
                    @if(auth()->user()->isSuperAdmin())
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ is_object($product) ? (is_object($product->store) ? $product->store->name : $product->store['name']) : $product['store']['name'] }}
                    </td>
                    @endif
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex justify-end space-x-2">
                            <x-button variant="outline" size="sm" onclick="window.location='{{ route('products.edit', $product->id) }}'">
                                Edit
                            </x-button>
                            <x-button 
                                variant="danger" 
                                size="sm" 
                                @click="showDelete = true; deleteItem = { name: '{{ $product->name }}', deleteUrl: '{{ route('products.destroy', $product->id) }}' }"
                            >
                                Delete
                            </x-button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="{{ auth()->user()->isSuperAdmin() ? '5' : '4' }}" class="px-6 py-10 text-center text-gray-500">
                        <div class="flex flex-col items-center justify-center">
                            <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                            <p class="text-lg font-medium">No products found</p>
                            <p class="text-sm">Get started by creating a new product.</p>
                            <a href="{{ route('products.create') }}" class="mt-4 text-primary-600 hover:text-primary-500 font-medium">Create New Product &rarr;</a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </x-table>
            
            <x-slot:footer>
                {{ $products->links() }}
            </x-slot:footer>
        </x-card>
    </x-resource-delete>
</x-dashboard-layout>

