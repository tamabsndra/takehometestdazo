<x-dashboard-layout>
    <x-slot:title>Edit User</x-slot:title>

    <div class="max-w-2xl mx-auto">
        <div class="mb-8">
            <x-back-link :route="route('users.index')" label="Back to Users" />
            <h1 class="text-2xl font-bold text-gray-900">Edit User: {{ $user->name }}</h1>
        </div>

        <x-card>
            <form action="{{ route('users.update', $user) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="space-y-6">
                    <x-input 
                        label="Full Name" 
                        name="name" 
                        required 
                        :value="old('name', $user->name)" 
                        :error="$errors->first('name')"
                    />

                    <x-input 
                        label="Email Address" 
                        name="email" 
                        type="email"
                        required 
                        :value="old('email', $user->email)" 
                        :error="$errors->first('email')"
                    />

                    <x-input 
                        label="Password (Leave blank to keep current)" 
                        name="password" 
                        type="password"
                        placeholder="••••••••"
                        :error="$errors->first('password')"
                    />

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                        <input type="text" value="{{ ucfirst($user->role) }}" disabled class="block w-full px-4 py-2 rounded-lg border-gray-300 bg-gray-100 text-gray-500 sm:text-sm cursor-not-allowed">
                        <p class="mt-1 text-xs text-gray-500">Role cannot be changed.</p>
                    </div>

                    @if($user->store)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Assigned Store</label>
                        <input type="text" value="{{ $user->store->name }}" disabled class="block w-full px-4 py-2 rounded-lg border-gray-300 bg-gray-100 text-gray-500 sm:text-sm cursor-not-allowed">
                    </div>
                    @endif
                </div>

                <x-form-actions submitLabel="Update User" />
            </form>
        </x-card>
    </div>
</x-dashboard-layout>
