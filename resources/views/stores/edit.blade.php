<x-dashboard-layout>
    <x-slot:title>Edit Store</x-slot:title>

    <div class="max-w-2xl mx-auto">
        <div class="mb-8">
            <x-back-link :route="route('stores.index')" label="Back to Stores" />
            <h1 class="text-2xl font-bold text-gray-900">Edit Store: {{ $store->name }}</h1>
        </div>

        <x-card>
            <form action="{{ route('stores.update', $store) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="space-y-6">
                    <x-input 
                        label="Store Name" 
                        name="name" 
                        required 
                        :value="old('name', $store->name)" 
                        :error="$errors->first('name')"
                    />

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Store Level</label>
                        <input type="text" value="{{ ucfirst($store->level) }}" disabled class="block w-full px-4 py-2 rounded-lg border-gray-300 bg-gray-100 text-gray-500 sm:text-sm cursor-not-allowed">
                        <p class="mt-1 text-xs text-gray-500">Store level cannot be changed.</p>
                    </div>

                    <x-input 
                        label="Address" 
                        name="address" 
                        :value="old('address', $store->address)"
                        :error="$errors->first('address')"
                    />

                    <x-input 
                        label="Phone Number" 
                        name="phone" 
                        :value="old('phone', $store->phone)"
                        :error="$errors->first('phone')"
                    />
                </div>

                <x-form-actions submitLabel="Update Store" />
            </form>
        </x-card>
    </div>
</x-dashboard-layout>
