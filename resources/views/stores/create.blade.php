<x-dashboard-layout>
    <x-slot:title>Add New Store</x-slot:title>

    <div class="max-w-2xl mx-auto">
        <div class="mb-8">
            <x-back-link :route="route('stores.index')" label="Back to Stores" />
            <h1 class="text-2xl font-bold text-gray-900">Add New Store</h1>
            <p class="mt-1 text-sm text-gray-500">Create a new store and auto-generate admin/cashier accounts.</p>
        </div>

        <x-card>
            <form action="{{ route('stores.store') }}" method="POST">
                @csrf
                
                <div class="space-y-6">
                    <x-input 
                        label="Store Name" 
                        name="name" 
                        placeholder="e.g., Dazo Cabang Surabaya" 
                        required 
                        :value="old('name')" 
                        :error="$errors->first('name')"
                    />

                    <div>
                        <label for="level" class="block text-sm font-medium text-gray-700 mb-1">Store Level <span class="text-danger">*</span></label>
                        <select name="level" id="level" class="block w-full px-4 py-2 rounded-lg border-gray-300 focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                            <option value="pusat" {{ old('level') == 'pusat' ? 'selected' : '' }}>Pusat (Headquarter)</option>
                            <option value="cabang" {{ old('level') == 'cabang' ? 'selected' : '' }}>Cabang (Branch)</option>
                            <option value="retail" {{ old('level') == 'retail' ? 'selected' : '' }}>Retail</option>
                        </select>
                        @error('level')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <x-input 
                        label="Address" 
                        name="address" 
                        placeholder="Full address" 
                        :value="old('address')"
                        :error="$errors->first('address')"
                    />

                    <x-input 
                        label="Phone Number" 
                        name="phone" 
                        placeholder="+62..." 
                        :value="old('phone')"
                        :error="$errors->first('phone')"
                    />
                </div>

                <x-form-actions submitLabel="Create Store" />
            </form>
        </x-card>
    </div>
</x-dashboard-layout>
