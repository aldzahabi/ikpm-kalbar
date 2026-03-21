<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'IKPM Gontor - Pontianak')</title>
    
    <!-- Google Fonts - Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Vite Assets (Tailwind CSS + Alpine.js) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @stack('styles')
</head>
<body class="bg-brand-bg font-sans" x-data="{ sidebarOpen: false }">
    <div class="min-h-screen flex">
        <!-- Mobile Sidebar Overlay -->
        <div 
            x-show="sidebarOpen" 
            x-cloak
            @click="sidebarOpen = false"
            x-transition:enter="transition-opacity ease-linear duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-linear duration-300"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-gray-900/50 z-40 lg:hidden"
        ></div>
        
        <!-- Sidebar -->
        <aside 
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-lg transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:shadow-sm flex flex-col"
        >
            <!-- Logo Section -->
            <div class="p-4 sm:p-6 border-b border-gray-100 flex items-center justify-between">
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-3">
                    <div class="w-9 h-9 sm:w-10 sm:h-10 bg-brand-primary rounded-lg flex items-center justify-center flex-shrink-0">
                        <span class="text-white font-bold text-base sm:text-lg">IK</span>
                    </div>
                    <div>
                        <h1 class="text-brand-primary font-bold text-base sm:text-lg leading-tight">IKPM Gontor</h1>
                        <p class="text-gray-500 text-xs">Pontianak</p>
                    </div>
                </a>
                <!-- Close Button (Mobile) -->
                <button 
                    @click="sidebarOpen = false" 
                    class="lg:hidden p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-colors"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <!-- Navigation Menu -->
            <nav class="flex-1 overflow-y-auto sidebar-scroll p-3 sm:p-4">
                <x-sidebar />
            </nav>
            
            <!-- Footer Sidebar -->
            <div class="p-3 sm:p-4 border-t border-gray-100">
                <p class="text-xs text-gray-500 text-center">© {{ date('Y') }} IKPM Gontor</p>
            </div>
        </aside>
        
        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0">
            <!-- Topbar (Header) -->
            <header class="bg-white shadow-sm sticky top-0 z-30">
                <div class="flex items-center justify-between px-4 sm:px-6 py-3 sm:py-4">
                    <!-- Mobile Menu Button & Page Title -->
                    <div class="flex items-center space-x-3 sm:space-x-4 min-w-0">
                        <!-- Hamburger Button (Mobile) -->
                        <button 
                            @click="sidebarOpen = true"
                            class="lg:hidden p-2 text-gray-600 hover:text-brand-primary hover:bg-brand-bg rounded-lg transition-colors flex-shrink-0"
                        >
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                        
                        <!-- Page Title -->
                        <div class="min-w-0">
                            <h2 class="text-lg sm:text-xl font-semibold text-gray-800 truncate">@yield('page-title', 'Dashboard')</h2>
                            @hasSection('page-subtitle')
                                <p class="text-xs sm:text-sm text-gray-500 mt-0.5 sm:mt-1 truncate hidden sm:block">@yield('page-subtitle')</p>
                            @endif
                        </div>
                    </div>
                    
                    <!-- User Profile Section -->
                    <div class="flex items-center space-x-2 sm:space-x-4 flex-shrink-0">
                        <!-- Notifications -->
                        <button class="relative p-2 text-gray-600 hover:text-brand-primary hover:bg-brand-bg rounded-lg transition-colors">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-brand-accent rounded-full"></span>
                        </button>
                        
                        <!-- User Profile Dropdown -->
                        <div x-data="{ profileOpen: false }" class="relative">
                            <button 
                                @click="profileOpen = !profileOpen"
                                @click.outside="profileOpen = false"
                                class="flex items-center space-x-2 sm:space-x-3 px-2 sm:px-3 py-1.5 sm:py-2 rounded-lg hover:bg-brand-bg transition-colors"
                            >
                                <div class="w-8 h-8 sm:w-10 sm:h-10 bg-brand-primary rounded-full flex items-center justify-center flex-shrink-0">
                                    <span class="text-white font-semibold text-xs sm:text-sm">
                                        {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                                    </span>
                                </div>
                                <div class="text-left hidden md:block">
                                    <p class="text-sm font-medium text-gray-800 truncate max-w-[120px]">{{ auth()->user()->name ?? 'User' }}</p>
                                    <p class="text-xs text-gray-500 truncate max-w-[120px]">{{ auth()->user()->role->name ?? 'User' }}</p>
                                </div>
                                <svg class="w-4 h-4 text-gray-500 hidden sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            
                            <!-- Dropdown Menu -->
                            <div 
                                x-show="profileOpen"
                                x-cloak
                                x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="transform opacity-100 scale-100"
                                x-transition:leave-end="transform opacity-0 scale-95"
                                class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-100 py-1 z-50"
                            >
                                <div class="px-4 py-2 border-b border-gray-100 md:hidden">
                                    <p class="text-sm font-medium text-gray-800">{{ auth()->user()->name ?? 'User' }}</p>
                                    <p class="text-xs text-gray-500">{{ auth()->user()->email ?? 'user@example.com' }}</p>
                                </div>
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-brand-bg transition-colors">
                                    <span class="flex items-center space-x-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        <span>Profil Saya</span>
                                    </span>
                                </a>
                                @can('isAdmin')
                                <a href="{{ route('settings.index') }}" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-brand-bg transition-colors">
                                    <span class="flex items-center space-x-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <span>Pengaturan</span>
                                    </span>
                                </a>
                                @endcan
                                <hr class="my-1 border-gray-100">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                        <span class="flex items-center space-x-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                            </svg>
                                            <span>Keluar</span>
                                        </span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Main Content -->
            <main class="flex-1 overflow-y-auto p-4 sm:p-6">
                <!-- Flash Messages -->
                @if(session('success'))
                    <div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-3 sm:px-4 py-2.5 sm:py-3 rounded-lg flex items-start sm:items-center space-x-2">
                        <svg class="w-5 h-5 flex-shrink-0 mt-0.5 sm:mt-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-sm">{{ session('success') }}</span>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-3 sm:px-4 py-2.5 sm:py-3 rounded-lg flex items-start sm:items-center space-x-2">
                        <svg class="w-5 h-5 flex-shrink-0 mt-0.5 sm:mt-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-sm">{{ session('error') }}</span>
                    </div>
                @endif
                
                @if($errors->any())
                    <div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-3 sm:px-4 py-2.5 sm:py-3 rounded-lg">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($errors->all() as $error)
                                <li class="text-sm">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <!-- Page Content -->
                @yield('content')
            </main>
        </div>
    </div>
    
    @stack('scripts')
</body>
</html>
