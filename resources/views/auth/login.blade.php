<x-auth-layout>
    <x-slot:title>Login</x-slot:title>

    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-1">Welcome Back</h2>
        <p class="text-gray-500">Sign in to your account to continue</p>
    </div>

    <!-- Error Messages -->
    @if ($errors->any())
    <div class="mb-6 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-lg flex items-start">
        <svg class="w-5 h-5 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
        </svg>
        <span class="text-sm font-medium">{{ $errors->first() }}</span>
    </div>
    @endif

    <form method="POST" action="/login" class="space-y-4">
        @csrf
        
        <x-input 
            label="Email Address" 
            name="email" 
            type="email" 
            placeholder="admin@dazo.com" 
            required 
            :value="old('email')"
        />

        <div class="mb-4">
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                Password
            </label>
            <x-input 
                name="password" 
                type="password" 
                placeholder="••••••••" 
                required 
                class="mb-0"
            />
        </div>

        <div class="flex items-center justify-between pt-2">
            <label class="flex items-center">
                <input type="checkbox" name="remember" class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                <span class="ml-2 text-sm text-gray-600">Remember me</span>
            </label>
        </div>

        <x-button class="w-full justify-center mt-6" size="lg" type="submit">
            Sign In
        </x-button>
    </form>

    <!-- Demo Credentials -->
    <div class="mt-8 pt-6 border-t border-gray-100">
        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Demo Credentials</p>
        <div class="grid grid-cols-1 gap-2 text-xs">
            <div class="flex justify-between items-center p-2 bg-gray-50 rounded border border-gray-100">
                <span class="font-medium text-gray-700">Super Admin</span>
                <span class="text-gray-500 font-mono">admin@dazo.com</span>
            </div>
            <div class="flex justify-between items-center p-2 bg-gray-50 rounded border border-gray-100">
                <span class="font-medium text-gray-700">Admin</span>
                <span class="text-gray-500 font-mono">admin.dazopusatjogja@dazo.com</span>
            </div>
            <div class="flex justify-between items-center p-2 bg-gray-50 rounded border border-gray-100">
                <span class="font-medium text-gray-700">Cashier</span>
                <span class="text-gray-500 font-mono">kasir.dazopusatjogja@dazo.com</span>
            </div>
        </div>
        <p class="text-center text-xs text-gray-400 mt-2">Password for all: <strong>password</strong></p>
    </div>
</x-auth-layout>
