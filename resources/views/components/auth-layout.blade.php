<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Login' }} - Dazo Store Management</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3, h4, h5, h6 { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-primary-800 to-indigo-900 flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <!-- Logo & Title -->
        <div class="text-center mb-8 animate-fade-in">
            <h1 class="text-5xl font-bold text-white mb-2 tracking-tight">Dazo</h1>
            <p class="text-primary-100 text-lg font-light">Store Management System</p>
        </div>
        
        <!-- Main Card -->
        <div class="bg-surface/95 backdrop-blur-sm rounded-2xl shadow-2xl p-8 border border-white/10 animate-slide-up">
            {{ $slot }}
        </div>
        
        <!-- Footer -->
        <div class="mt-8 text-center text-primary-200 text-sm animate-fade-in">
            <p>&copy; 2026 Dazo Store Management.</p>
        </div>
    </div>
</body>
</html>
