<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Akses Ditolak | IKPM Kalbar</title>
    
    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-brand-bg min-h-screen flex items-center justify-center px-4">
    <div class="max-w-2xl w-full text-center">
        <!-- Icon Lock/Shield -->
        <div class="mb-8 flex justify-center">
            <div class="w-48 h-48 bg-white rounded-full shadow-lg flex items-center justify-center border-4 border-amber-500">
                <svg class="w-32 h-32 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
            </div>
        </div>
        
        <!-- Error Code -->
        <div class="mb-6">
            <h1 class="text-8xl font-bold text-amber-500 mb-2">403</h1>
            <div class="w-24 h-1 bg-amber-500 mx-auto rounded-full"></div>
        </div>
        
        <!-- Title -->
        <h2 class="text-3xl font-bold text-gray-800 mb-4">
            Afwan, Akses Dibatasi
        </h2>
        
        <!-- Message -->
        <p class="text-lg text-gray-600 mb-8 max-w-md mx-auto">
            Antum tidak memiliki izin untuk mengakses halaman ini. Hanya untuk Admin/Role tertentu.
        </p>
        
        <!-- Action Button -->
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            @auth
            <a 
                href="{{ route('dashboard') }}" 
                class="inline-flex items-center space-x-2 px-8 py-3 bg-brand-primary text-white rounded-lg hover:bg-green-700 transition-colors focus:outline-none focus:ring-2 focus:ring-green-200 focus:ring-offset-2 font-medium shadow-md"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span>Kembali ke Dashboard</span>
            </a>
            @else
            <a 
                href="{{ route('login') }}" 
                class="inline-flex items-center space-x-2 px-8 py-3 bg-brand-primary text-white rounded-lg hover:bg-green-700 transition-colors focus:outline-none focus:ring-2 focus:ring-green-200 focus:ring-offset-2 font-medium shadow-md"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                </svg>
                <span>Masuk ke Sistem</span>
            </a>
            @endauth
            
            <a 
                href="{{ url()->previous() }}" 
                class="inline-flex items-center space-x-2 px-8 py-3 bg-white text-gray-700 border-2 border-gray-300 rounded-lg hover:bg-gray-50 transition-colors focus:outline-none focus:ring-2 focus:ring-gray-200 focus:ring-offset-2 font-medium"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>Kembali</span>
            </a>
        </div>
        
        <!-- Info Box -->
        <div class="mt-8 p-4 bg-amber-50 border border-amber-200 rounded-lg max-w-md mx-auto">
            <p class="text-sm text-amber-800">
                <strong>Catatan:</strong> Jika Antum merasa ini adalah kesalahan, silakan hubungi Administrator sistem.
            </p>
        </div>
        
        <!-- Decorative Elements -->
        <div class="mt-12 flex justify-center space-x-2">
            <div class="w-2 h-2 bg-amber-500 rounded-full animate-pulse"></div>
            <div class="w-2 h-2 bg-amber-500 rounded-full animate-pulse" style="animation-delay: 0.2s;"></div>
            <div class="w-2 h-2 bg-amber-500 rounded-full animate-pulse" style="animation-delay: 0.4s;"></div>
        </div>
    </div>
</body>
</html>
