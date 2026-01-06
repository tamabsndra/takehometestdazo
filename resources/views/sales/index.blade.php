<x-dashboard-layout>
    <x-slot:title>Sales History</x-slot:title>

    <x-page-header 
        title="Sales History" 
        description="View all transactions and sales details."
    />

    @if(auth()->user()->isSuperAdmin())
    <!-- Store Filter for Super Admin -->
    <div class="bg-white p-4 rounded-lg shadow-sm mb-6 border border-gray-200">
        <form action="{{ route('sales.index') }}" method="GET" class="flex gap-4">
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
                <a href="{{ route('sales.index') }}" class="btn-secondary flex items-center justify-center">Reset</a>
            @endif
        </form>
    </div>
    @endif

    <x-card class="bg-white" padding="p-0">
        <x-table :headers="auth()->user()->isSuperAdmin() ? ['ID', 'Date', 'Store', 'Total', 'Payment', 'Actions'] : ['ID', 'Date', 'Total', 'Payment', 'Actions']">
            @forelse($sales as $sale)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-primary-600">
                    #{{ is_object($sale) ? $sale->id : $sale['id'] }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ is_object($sale) ? (is_object($sale->transaction_date) ? $sale->transaction_date->format('d M Y, H:i') : \Carbon\Carbon::parse($sale->transaction_date)->format('d M Y, H:i')) : \Carbon\Carbon::parse($sale['transaction_date'])->format('d M Y, H:i') }}
                </td>
                @if(auth()->user()->isSuperAdmin())
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ is_object($sale) ? (is_object($sale->store) ? $sale->store->name : $sale->store['name']) : $sale['store']['name'] }}
                </td>
                @endif
                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                    Rp {{ number_format(is_object($sale) ? $sale->total_amount : $sale['total_amount'], 0, ',', '.') }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <x-badge :status="is_object($sale) ? $sale->payment_method : $sale['payment_method']" />
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <a href="{{ route('sales.show', is_object($sale) ? $sale->id : $sale['id']) }}" class="text-primary-600 hover:text-primary-900">
                        View Details
                    </a>
                </td>
            </tr>
            @empty
             <tr>
                <td colspan="{{ auth()->user()->isSuperAdmin() ? '6' : '5' }}" class="px-6 py-10 text-center text-gray-500">
                    <p class="text-lg font-medium">No sales found</p>
                </td>
            </tr>
            @endforelse
        </x-table>
        
        <x-slot:footer>
            {{ $sales->links() }}
        </x-slot:footer>
    </x-card>
</x-dashboard-layout>
