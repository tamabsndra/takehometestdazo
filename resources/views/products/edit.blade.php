<x-dashboard-layout>
    <x-slot:title>Edit Product</x-slot:title>

    <div class="max-w-2xl mx-auto">
        <div class="mb-8">
            <x-back-link :route="route('products.index')" label="Back to Products" />
            <h1 class="text-2xl font-bold text-gray-900">Edit Product: {{ $product->name }}</h1>
        </div>

        <x-card>
            <form action="{{ route('products.update', $product) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="space-y-6">
                    @if(auth()->user()->isSuperAdmin())
                    <div>
                        <label for="store_id" class="block text-sm font-medium text-gray-700 mb-1">Store <span class="text-danger">*</span></label>
                        <select name="store_id" id="store_id" required class="block w-full px-4 py-2 rounded-lg border-gray-300 focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                            @foreach($stores as $store)
                                <option value="{{ $store->id }}" {{ old('store_id', $product->store_id) == $store->id ? 'selected' : '' }}>
                                    {{ $store->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('store_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    @endif

                    <x-input 
                        label="Product Name" 
                        name="name" 
                        required 
                        :value="old('name', $product->name)" 
                        :error="$errors->first('name')"
                    />

                    <x-input 
                        label="Price (Rp)" 
                        name="price" 
                        type="number"
                        required 
                        :value="old('price', $product->price)" 
                        :error="$errors->first('price')"
                    />

                    <x-input 
                        label="SKU (Optional)" 
                        name="sku" 
                        :value="old('sku', $product->sku)" 
                        :error="$errors->first('sku')"
                    />

                    <div class="mb-4">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea name="description" id="description" rows="3" class="block w-full px-4 py-2 rounded-lg border-gray-300 focus:ring-primary-500 focus:border-primary-500 sm:text-sm">{{ old('description', $product->description) }}</textarea>
                    </div>
                </div>

                <x-form-actions submitLabel="Update Product" />
            </form>
        </x-card>
    </div>
</x-dashboard-layout>
