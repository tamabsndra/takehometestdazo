<x-dashboard-layout>
    <x-slot:title>Manage Users</x-slot:title>

    <x-page-header 
        title="Users" 
        description="Manage all system users including Admins and Cashiers."
    />

    <x-resource-delete resource="user">
        <!-- Filters -->
        <div class="bg-white p-4 rounded-lg shadow-sm mb-6 border border-gray-200">
            <form action="{{ route('users.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4">
                <div class="flex-1">
                     <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or email..." class="block w-full px-4 py-2 rounded-lg border-gray-300 focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                </div>
                <div class="sm:w-48">
                    <select name="role" class="block w-full px-4 py-2 rounded-lg border-gray-300 focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                        <option value="">All Roles</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="cashier" {{ request('role') == 'cashier' ? 'selected' : '' }}>Cashier</option>
                        <option value="super_admin" {{ request('role') == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                    </select>
                </div>
                <button type="submit" class="btn-primary">Filter</button>
                @if(request()->has('search') || request()->has('role'))
                    <a href="{{ route('users.index') }}" class="btn-secondary flex items-center justify-center">Reset</a>
                @endif
            </form>
        </div>

        <x-card class="bg-white" padding="p-0">
            <x-table :headers="['Name', 'Role', 'Store', 'Joined Date', 'Actions']">
                @foreach($users as $user)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                <span class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-primary-100">
                                    <span class="text-sm font-medium leading-none text-primary-700">{{ substr($user->name, 0, 1) }}</span>
                                </span>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                <div class="text-sm text-gray-500">{{ $user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <x-badge :status="$user->role" />
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $user->store->name ?? '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ is_string($user->created_at) ? \Carbon\Carbon::parse($user->created_at)->format('d M Y') : $user->created_at->format('d M Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex justify-end space-x-2">
                            <x-button variant="outline" size="sm" onclick="window.location='{{ route('users.edit', $user->id) }}'">
                                Edit
                            </x-button>
                            @if($user->id !== auth()->id())
                                <x-button 
                                    variant="danger" 
                                    size="sm" 
                                    @click="showDelete = true; deleteItem = { name: '{{ $user->name }}', deleteUrl: '{{ route('users.destroy', $user->id) }}' }"
                                >
                                    Delete
                                </x-button>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </x-table>
            
            <x-slot:footer>
                {{ $users->links() }}
            </x-slot:footer>
        </x-card>
    </x-resource-delete>
</x-dashboard-layout>

