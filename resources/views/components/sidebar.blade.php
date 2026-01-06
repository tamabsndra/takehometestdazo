<div x-show="sidebarOpen" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in duration-300 transform" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full" class="fixed inset-y-0 left-0 flex flex-col z-50 w-64 bg-white border-r border-gray-200 lg:static lg:inset-auto lg:translate-x-0 lg:flex" x-cloak>
    
    <!-- Logo -->
    <div class="flex items-center justify-center h-16 border-b border-gray-200 bg-white">
        <h1 class="text-2xl font-bold text-primary-800 font-display">DAZO</h1>
    </div>

    <!-- Menu Items -->
    <div class="flex-1 flex flex-col overflow-y-auto pt-5 pb-4 px-4 bg-white space-y-1">
        
        <!-- Super Admin Menu -->
        @if(auth()->user()->isSuperAdmin())
            <x-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                <svg class="mr-3 h-5 w-5 {{ request()->routeIs('dashboard') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Dashboard
            </x-nav-link>
            
            <div class="pt-4 pb-2">
                <p class="px-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Management</p>
            </div>

            <x-nav-link href="{{ route('stores.index') }}" :active="request()->routeIs('stores.*')">
                <svg class="mr-3 h-5 w-5 {{ request()->routeIs('stores.*') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                Stores
            </x-nav-link>
            
            <x-nav-link href="{{ route('users.index') }}" :active="request()->routeIs('users.*')">
                <svg class="mr-3 h-5 w-5 {{ request()->routeIs('users.*') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                Users
            </x-nav-link>

            <x-nav-link href="{{ route('products.index') }}" :active="request()->routeIs('products.*')">
                <svg class="mr-3 h-5 w-5 {{ request()->routeIs('products.*') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                Products
            </x-nav-link>

            <x-nav-link href="{{ route('sales.index') }}" :active="request()->routeIs('sales.*')">
                <svg class="mr-3 h-5 w-5 {{ request()->routeIs('sales.*') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                Sales
            </x-nav-link>
        @endif

        <!-- Admin Menu -->
        @if(auth()->user()->isAdmin())
            <x-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                <svg class="mr-3 h-5 w-5 {{ request()->routeIs('dashboard') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                Dashboard
            </x-nav-link>

            <div class="pt-4 pb-2">
                <p class="px-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Store</p>
            </div>

            <x-nav-link href="{{ route('products.index') }}" :active="request()->routeIs('products.*')">
                <svg class="mr-3 h-5 w-5 {{ request()->routeIs('products.*') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                Products
            </x-nav-link>

            <x-nav-link href="{{ route('sales.index') }}" :active="request()->routeIs('sales.*')">
                <svg class="mr-3 h-5 w-5 {{ request()->routeIs('sales.*') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                Sales
            </x-nav-link>

            <x-nav-link href="{{ route('pos') }}" :active="request()->routeIs('pos')">
                <svg class="mr-3 h-5 w-5 {{ request()->routeIs('pos') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                POS System
            </x-nav-link>
        @endif

        <!-- Cashier Menu -->
        @if(auth()->user()->isCashier())
             <x-nav-link href="{{ route('pos') }}" :active="request()->routeIs('pos')">
                <svg class="mr-3 h-5 w-5 {{ request()->routeIs('pos') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                POS System
            </x-nav-link>

            <x-nav-link href="{{ route('sales.index') }}" :active="request()->routeIs('sales.*')">
                <svg class="mr-3 h-5 w-5 {{ request()->routeIs('sales.*') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                Sales History
            </x-nav-link>
        @endif
    </div>

    <!-- User Profile Link -->
    <div class="border-t border-gray-200 p-4">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="h-8 w-8 rounded-full bg-primary-100 flex items-center justify-center text-primary-700 font-bold">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-gray-700 group-hover:text-gray-900">
                    {{ auth()->user()->name }}
                </p>
                <p class="text-xs font-medium text-gray-500 group-hover:text-gray-700">
                    {{ ucfirst(auth()->user()->role) }}
                </p>
            </div>
        </div>
    </div>
</div>
