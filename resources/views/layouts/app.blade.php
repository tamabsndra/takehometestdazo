<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dazo Store Management')</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="min-h-screen">
    <!-- Navigation -->
    @auth
    <nav class="bg-white shadow-lg border-b border-gray-200" x-data="{ mobileMenuOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <!-- Logo -->
                    <div class="flex-shrink-0 flex items-center">
                        <h1 class="text-2xl font-bold text-gradient">Dazo</h1>
                    </div>
                    
                    <!-- Desktop Navigation -->
                    <div class="hidden sm:ml-8 sm:flex sm:space-x-4">
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center px-3 py-2 text-sm font-medium {{ request()->routeIs('dashboard') ? 'text-primary-600 border-b-2 border-primary-600' : 'text-gray-700 hover:text-primary-600' }}">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            Dashboard
                        </a>
                        
                        @if(auth()->user()->isSuperAdmin())
                        <a href="{{ route('stores.index') }}" class="inline-flex items-center px-3 py-2 text-sm font-medium {{ request()->routeIs('stores.*') ? 'text-primary-600 border-b-2 border-primary-600' : 'text-gray-700 hover:text-primary-600' }}">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            Stores
                        </a>
                        <a href="{{ route('users.index') }}" class="inline-flex items-center px-3 py-2 text-sm font-medium {{ request()->routeIs('users.*') ? 'text-primary-600 border-b-2 border-primary-600' : 'text-gray-700 hover:text-primary-600' }}">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            Users
                        </a>
                        @endif
                        
                        @if(auth()->user()->isAdmin() || auth()->user()->isSuperAdmin())
                        <a href="{{ route('products.index') }}" class="inline-flex items-center px-3 py-2 text-sm font-medium {{ request()->routeIs('products.*') ? 'text-primary-600 border-b-2 border-primary-600' : 'text-gray-700 hover:text-primary-600' }}">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                            Products
                        </a>
                        @endif
                        
                        <a href="{{ route('sales.index') }}" class="inline-flex items-center px-3 py-2 text-sm font-medium {{ request()->routeIs('sales.*') ? 'text-primary-600 border-b-2 border-primary-600' : 'text-gray-700 hover:text-primary-600' }}">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            Sales
                        </a>
                        
                        @if(!auth()->user()->isSuperAdmin())
                        <a href="{{ route('pos') }}" class="inline-flex items-center px-3 py-2 text-sm font-medium {{ request()->routeIs('pos') ? 'text-primary-600 border-b-2 border-primary-600' : 'text-gray-700 hover:text-primary-600' }}">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            POS
                        </a>
                        @endif
                    </div>
                </div>
                
                <div class="hidden sm:ml-6 sm:flex sm:items-center">
                    <!-- User dropdown -->
                    <div class="ml-3 relative" x-data="{ open: false }">
                        <div>
                            <button @click="open = !open" type="button" class="flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                <div class="h-10 w-10 rounded-full bg-gradient-primary flex items-center justify-center text-white font-semibold">
                                    {{ substr(auth()->user()->name, 0, 1) }}
                                </div>
                                <div class="ml-3 text-left">
                                    <div class="text-sm font-medium text-gray-700">{{ auth()->user()->name }}</div>
                                    <div class="text-xs text-gray-500">{{ ucfirst(auth()->user()->role) }}</div>
                                </div>
                                <svg class="ml-2 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                        </div>
                        
                        <div x-show="open" @click.away="open = false" x-cloak class="origin-top-right absolute right-0 mt-2 w-48 rounded-lg shadow-lg bg-white ring-1 ring-black ring-opacity-5 animate-slide-down">
                            <div class="py-1">
                                <div class="px-4 py-2 border-b">
                                    <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                                    @if(auth()->user()->store)
                                    <p class="text-xs text-gray-500 mt-1">{{ auth()->user()->store->name }}</p>
                                    @endif
                                </div>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile Settings</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Mobile menu button -->
                <div class="flex items-center sm:hidden">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" type="button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-700 hover:text-primary-600 focus:outline-none">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path x-show="mobileMenuOpen" x-cloak stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Mobile menu -->
        <div x-show="mobileMenuOpen" x-cloak class="sm:hidden border-t border-gray-200">
            <div class="pt-2 pb-3 space-y-1">
                <a href="{{ route('dashboard') }}" class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('dashboard') ? 'border-primary-600 text-primary-600 bg-primary-50' : 'border-transparent text-gray-700 hover:bg-gray-50' }} text-base font-medium">
                    Dashboard
                </a>
                <!-- Add other mobile menu items here -->
            </div>
        </div>
    </nav>
    @endauth
    
    <!-- Main Content -->
    <div class="{{ auth()->check() ? 'py-6' : '' }}">
        @yield('content')
    </div>
    
    <!-- Notification Container -->
    <div id="notification-container" class="fixed top-4 right-4 z-50 space-y-2"></div>
</body>
</html>
