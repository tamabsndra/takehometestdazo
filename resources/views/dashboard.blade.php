<x-dashboard-layout>
    <x-slot:title>Dashboard</x-slot:title>

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Dashboard Overview</h1>
        <p class="text-sm text-gray-500">Welcome back, {{ auth()->user()->name }}!</p>
    </div>
    
    @if(auth()->user()->isSuperAdmin())
        <!-- Super Admin Dashboard -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <x-stats-card 
                title="Total Stores" 
                value="{{ $stats['total_stores'] }}" 
                color="blue"
                icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>'
            />
            
            <x-stats-card 
                title="Total Users" 
                value="{{ $stats['total_users'] }}" 
                color="purple"
                icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>'
            />
            
            <x-stats-card 
                title="Total Products" 
                value="{{ $stats['total_products'] }}" 
                color="yellow"
                icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>'
            />
            
            <x-stats-card 
                title="Total Revenue" 
                value="Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}" 
                color="green"
                icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
            />
        </div>

        <!-- Quick Actions -->
        <div class="my-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @if(auth()->user()->isSuperAdmin())
                    <a href="{{ route('stores.index') }}" class="flex flex-col items-center justify-center p-6 bg-white border border-gray-200 rounded-xl hover:shadow-md hover:border-primary-500 hover:text-primary-600 transition-all group">
                        <div class="p-3 rounded-full bg-primary-50 text-primary-600 mb-3 group-hover:bg-primary-600 group-hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                        <span class="text-sm font-medium">Manage Stores</span>
                    </a>
                    
                    <a href="{{ route('users.index') }}" class="flex flex-col items-center justify-center p-6 bg-white border border-gray-200 rounded-xl hover:shadow-md hover:border-primary-500 hover:text-primary-600 transition-all group">
                        <div class="p-3 rounded-full bg-purple-50 text-purple-600 mb-3 group-hover:bg-purple-600 group-hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        </div>
                        <span class="text-sm font-medium">Manage Users</span>
                    </a>
                @endif
                
                @if(auth()->user()->isAdmin() || auth()->user()->isSuperAdmin())
                    <a href="{{ route('products.create') }}" class="flex flex-col items-center justify-center p-6 bg-white border border-gray-200 rounded-xl hover:shadow-md hover:border-primary-500 hover:text-primary-600 transition-all group">
                        <div class="p-3 rounded-full bg-yellow-50 text-yellow-600 mb-3 group-hover:bg-yellow-600 group-hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        </div>
                        <span class="text-sm font-medium">Add Product</span>
                    </a>
                @endif
                
                @if(!auth()->user()->isSuperAdmin())
                    <a href="{{ route('pos') }}" class="flex flex-col items-center justify-center p-6 bg-white border border-gray-200 rounded-xl hover:shadow-md hover:border-primary-500 hover:text-primary-600 transition-all group">
                        <div class="p-3 rounded-full bg-green-50 text-green-600 mb-3 group-hover:bg-green-600 group-hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </div>
                        <span class="text-sm font-medium">Open POS</span>
                    </a>
                @endif
                
                <a href="{{ route('sales.index') }}" class="flex flex-col items-center justify-center p-6 bg-white border border-gray-200 rounded-xl hover:shadow-md hover:border-primary-500 hover:text-primary-600 transition-all group">
                    <div class="p-3 rounded-full bg-blue-50 text-blue-600 mb-3 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    </div>
                    <span class="text-sm font-medium">View Sales</span>
                </a>
            </div>
        </div>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <x-card title="Recent Stores">
                <div class="flow-root">
                    <ul class="-my-5 divide-y divide-gray-200">
                        @foreach($recentStores as $store)
                        <li class="py-4">
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    <span class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-primary-100">
                                        <span class="text-sm font-medium leading-none text-primary-700">{{ substr($store->name, 0, 2) }}</span>
                                    </span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">
                                        {{ $store->name }}
                                    </p>
                                    <p class="text-sm text-gray-500 truncate">
                                        {{ is_array($store->users) ? count($store->users) : $store->users->count() }} users
                                    </p>
                                </div>
                                <div>
                                    <x-badge :status="$store->level" />
                                </div>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
                <x-slot:footer>
                    <div class="text-right">
                        <a href="{{ route('stores.index') }}" class="text-sm font-medium text-primary-600 hover:text-primary-500">View all stores &rarr;</a>
                    </div>
                </x-slot:footer>
            </x-card>
            
            <x-card title="Top Performing Stores">
                 <div class="flow-root">
                    <ul class="-my-5 divide-y divide-gray-200">
                        @foreach($salesByStore as $data)
                        <li class="py-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                         <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-green-100 text-green-600">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                                        </span>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-900">{{ $data->store->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $data->total_sales }} sales</p>
                                    </div>
                                </div>
                                <div class="text-sm font-bold text-green-600">
                                    Rp {{ number_format($data->revenue, 0, ',', '.') }}
                                </div>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </x-card>
        </div>
        
    @else
        <!-- Admin/Cashier Dashboard -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
             <x-stats-card 
                title="Your Store" 
                value="{{ $stats['store_name'] }}" 
                color="blue"
                icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>'
            />

            <x-stats-card 
                title="Total Products" 
                value="{{ $stats['total_products'] }}" 
                color="purple"
                icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>'
            />
            
            <x-stats-card 
                title="Sales Today" 
                value="{{ $stats['total_sales_today'] }}" 
                color="yellow"
                icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
            />
            
            <div class="col-span-1 md:col-span-2 lg:col-span-1 grid grid-cols-2 gap-4">
                <div class="bg-green-50 p-4 rounded-xl border border-green-100">
                    <p class="text-xs font-medium text-green-600 uppercase">Revenue Today</p>
                    <p class="text-lg font-bold text-green-700">Rp {{ number_format($stats['revenue_today'], 0, ',', '.') }}</p>
                </div>
                <div class="bg-blue-50 p-4 rounded-xl border border-blue-100">
                    <p class="text-xs font-medium text-blue-600 uppercase">Revenue Month</p>
                    <p class="text-lg font-bold text-blue-700">Rp {{ number_format($stats['revenue_month'], 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

         
        <!-- Quick Actions -->
        <div class="my-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @if(auth()->user()->isSuperAdmin())
                    <a href="{{ route('stores.index') }}" class="flex flex-col items-center justify-center p-6 bg-white border border-gray-200 rounded-xl hover:shadow-md hover:border-primary-500 hover:text-primary-600 transition-all group">
                        <div class="p-3 rounded-full bg-primary-50 text-primary-600 mb-3 group-hover:bg-primary-600 group-hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                        <span class="text-sm font-medium">Manage Stores</span>
                    </a>
                    
                    <a href="{{ route('users.index') }}" class="flex flex-col items-center justify-center p-6 bg-white border border-gray-200 rounded-xl hover:shadow-md hover:border-primary-500 hover:text-primary-600 transition-all group">
                        <div class="p-3 rounded-full bg-purple-50 text-purple-600 mb-3 group-hover:bg-purple-600 group-hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        </div>
                        <span class="text-sm font-medium">Manage Users</span>
                    </a>
                @endif
                
                @if(auth()->user()->isAdmin() || auth()->user()->isSuperAdmin())
                    <a href="{{ route('products.create') }}" class="flex flex-col items-center justify-center p-6 bg-white border border-gray-200 rounded-xl hover:shadow-md hover:border-primary-500 hover:text-primary-600 transition-all group">
                        <div class="p-3 rounded-full bg-yellow-50 text-yellow-600 mb-3 group-hover:bg-yellow-600 group-hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        </div>
                        <span class="text-sm font-medium">Add Product</span>
                    </a>
                @endif
                
                @if(!auth()->user()->isSuperAdmin())
                    <a href="{{ route('pos') }}" class="flex flex-col items-center justify-center p-6 bg-white border border-gray-200 rounded-xl hover:shadow-md hover:border-primary-500 hover:text-primary-600 transition-all group">
                        <div class="p-3 rounded-full bg-green-50 text-green-600 mb-3 group-hover:bg-green-600 group-hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </div>
                        <span class="text-sm font-medium">Open POS</span>
                    </a>
                @endif
                
                <a href="{{ route('sales.index') }}" class="flex flex-col items-center justify-center p-6 bg-white border border-gray-200 rounded-xl hover:shadow-md hover:border-primary-500 hover:text-primary-600 transition-all group">
                    <div class="p-3 rounded-full bg-blue-50 text-blue-600 mb-3 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    </div>
                    <span class="text-sm font-medium">View Sales</span>
                </a>
            </div>
        </div>
        
        <!-- Recent Sales & Top Products -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <x-card title="Recent Sales">
                 <div class="flow-root">
                    <ul class="-my-5 divide-y divide-gray-200">
                        @foreach($recentSales as $sale)
                        <li class="py-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-blue-100">
                                            <span class="text-xs font-medium leading-none text-blue-700">
                                                {{ is_object($sale) ? (is_object($sale->cashier) ? substr($sale->cashier->name, 0, 1) : substr($sale->cashier['name'], 0, 1)) : substr($sale['cashier']['name'], 0, 1) }}
                                            </span>
                                        </span>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-900">
                                            {{ is_object($sale) ? (is_object($sale->cashier) ? $sale->cashier->name : $sale->cashier['name']) : $sale['cashier']['name'] }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            {{ is_object($sale) ? (is_object($sale->transaction_date) ? $sale->transaction_date->format('d M, H:i') : \Carbon\Carbon::parse($sale->transaction_date)->format('d M, H:i')) : \Carbon\Carbon::parse($sale['transaction_date'])->format('d M, H:i') }}
                                        </p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm font-bold text-gray-900">Rp {{ number_format(is_object($sale) ? $sale->total_amount : $sale['total_amount'], 0, ',', '.') }}</div>
                                    <x-badge :status="is_object($sale) ? $sale->payment_method : $sale['payment_method']" />
                                </div>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
                <x-slot:footer>
                     <div class="text-right">
                        <a href="{{ route('sales.index') }}" class="text-sm font-medium text-primary-600 hover:text-primary-500">View all sales &rarr;</a>
                    </div>
                </x-slot:footer>
            </x-card>
            
            <x-card title="Top Selling Products">
                <div class="flow-root">
                    <ul class="-my-5 divide-y divide-gray-200">
                        @foreach($topProducts as $product)
                        <li class="py-4">
                             <div class="flex items-center justify-between">
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">
                                        {{ $product->name }}
                                    </p>
                                    <p class="text-sm text-gray-500 truncate">
                                        {{ $product->total_sold }} sold
                                    </p>
                                </div>
                                <div class="text-sm font-bold text-green-600">
                                    Rp {{ number_format($product->revenue, 0, ',', '.') }}
                                </div>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </x-card>
        </div>
    @endif

</x-dashboard-layout>
