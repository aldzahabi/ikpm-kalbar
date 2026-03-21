<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Halaman Tidak Ditemukan | IKPM Gontor Pontianak</title>
    
    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-brand-bg min-h-screen flex items-center justify-center px-4">
    <div class="max-w-2xl w-full text-center">
        <!-- Icon Masjid/Santri Bingung -->
        <div class="mb-8 flex justify-center">
            <div class="w-48 h-48 bg-white rounded-full shadow-lg flex items-center justify-center border-4 border-brand-primary">
                <svg class="w-32 h-32 text-brand-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
        
        <!-- Error Code -->
        <div class="mb-6">
            <h1 class="text-8xl font-bold text-brand-primary mb-2">404</h1>
            <div class="w-24 h-1 bg-brand-primary mx-auto rounded-full"></div>
        </div>
        
        <!-- Title -->
        <h2 class="text-3xl font-bold text-gray-800 mb-4">
            Afwan, Halaman Tidak Ditemukan
        </h2>
        
        <!-- Message -->
        <p class="text-lg text-gray-600 mb-8 max-w-md mx-auto">
            Mungkin tautan yang Antum tuju salah atau sudah dihapus.
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
                href="{{ url('/') }}" 
                class="inline-flex items-center space-x-2 px-8 py-3 bg-white text-brand-primary border-2 border-brand-primary rounded-lg hover:bg-brand-bg transition-colors focus:outline-none focus:ring-2 focus:ring-green-200 focus:ring-offset-2 font-medium"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span>Kembali ke Beranda</span>
            </a>
        </div>
        
        <!-- Decorative Elements -->
        <div class="mt-12 flex justify-center space-x-2">
            <div class="w-2 h-2 bg-brand-primary rounded-full animate-pulse"></div>
            <div class="w-2 h-2 bg-brand-primary rounded-full animate-pulse" style="animation-delay: 0.2s;"></div>
            <div class="w-2 h-2 bg-brand-primary rounded-full animate-pulse" style="animation-delay: 0.4s;"></div>
        </div>
    </div>
</body>
</html>
