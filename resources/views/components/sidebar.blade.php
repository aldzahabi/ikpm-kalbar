<!-- Sidebar Navigation Menu -->
<ul class="space-y-1">
    <!-- Dashboard -->
    <li>
        <a href="{{ route('dashboard') }}" @click="sidebarOpen = false" class="flex items-center space-x-3 px-3 sm:px-4 py-2.5 sm:py-3 text-gray-700 hover:bg-brand-bg hover:text-brand-primary rounded-lg transition-colors {{ request()->routeIs('dashboard') ? 'bg-brand-bg text-brand-primary font-semibold' : '' }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            <span class="font-medium text-sm sm:text-base">Dashboard</span>
        </a>
    </li>
    
    <!-- Data Santri (Dropdown) -->
    <li x-data="{ open: {{ request()->routeIs('santri.*') ? 'true' : 'false' }} }">
        <button @click="open = !open" class="w-full flex items-center justify-between px-3 sm:px-4 py-2.5 sm:py-3 text-gray-700 hover:bg-brand-bg hover:text-brand-primary rounded-lg transition-colors {{ request()->routeIs('santri.*') ? 'bg-brand-bg text-brand-primary' : '' }}">
            <div class="flex items-center space-x-3">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <span class="font-medium text-sm sm:text-base">Data Santri</span>
            </div>
            <svg :class="open ? 'rotate-180' : ''" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </button>
        
        <!-- Dropdown Menu -->
        <div x-show="open" x-cloak x-collapse class="mt-1 ml-4 space-y-1">
            @can('canManageSantri')
            <a href="{{ route('santri.create') }}" @click="sidebarOpen = false" class="flex items-center space-x-3 px-3 sm:px-4 py-2 text-sm text-gray-600 hover:bg-brand-bg hover:text-brand-primary rounded-lg transition-colors {{ request()->routeIs('santri.create') ? 'bg-brand-bg text-brand-primary' : '' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>Input Baru</span>
            </a>
            @endcan
            <a href="{{ route('santri.index') }}" @click="sidebarOpen = false" class="flex items-center space-x-3 px-3 sm:px-4 py-2 text-sm text-gray-600 hover:bg-brand-bg hover:text-brand-primary rounded-lg transition-colors {{ request()->routeIs('santri.index') ? 'bg-brand-bg text-brand-primary' : '' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span>Data Lengkap</span>
            </a>
        </div>
    </li>
    
    <!-- Perpulangan -->
    <li>
        <a href="{{ route('rombongan.index') }}" @click="sidebarOpen = false" class="flex items-center space-x-3 px-3 sm:px-4 py-2.5 sm:py-3 text-gray-700 hover:bg-brand-bg hover:text-brand-primary rounded-lg transition-colors {{ request()->routeIs('rombongan.*') ? 'bg-brand-bg text-brand-primary font-semibold' : '' }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
            </svg>
            <span class="font-medium text-sm sm:text-base">Perpulangan</span>
        </a>
    </li>
    
    <!-- Keuangan -->
    <li>
        <a href="{{ route('keuangan.index') }}" @click="sidebarOpen = false" class="flex items-center space-x-3 px-3 sm:px-4 py-2.5 sm:py-3 text-gray-700 hover:bg-brand-bg hover:text-brand-primary rounded-lg transition-colors {{ request()->routeIs('keuangan.*') ? 'bg-brand-bg text-brand-primary font-semibold' : '' }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="font-medium text-sm sm:text-base">Keuangan</span>
        </a>
    </li>
    
    <!-- Panitia -->
    <li>
        <a href="{{ route('panitia.index') }}" @click="sidebarOpen = false" class="flex items-center space-x-3 px-3 sm:px-4 py-2.5 sm:py-3 text-gray-700 hover:bg-brand-bg hover:text-brand-primary rounded-lg transition-colors {{ request()->routeIs('panitia.*') ? 'bg-brand-bg text-brand-primary font-semibold' : '' }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <span class="font-medium text-sm sm:text-base">Panitia</span>
        </a>
    </li>
    
    <!-- Manajemen User - Hanya Admin -->
    @can('isAdmin')
    <li>
        <a href="{{ route('users.index') }}" @click="sidebarOpen = false" class="flex items-center space-x-3 px-3 sm:px-4 py-2.5 sm:py-3 text-gray-700 hover:bg-brand-bg hover:text-brand-primary rounded-lg transition-colors {{ request()->routeIs('users.*') ? 'bg-brand-bg text-brand-primary font-semibold' : '' }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            <span class="font-medium text-sm sm:text-base">Manajemen User</span>
        </a>
    </li>
    @endcan
    
    <!-- CMS Landing Page - Hanya Admin -->
    @can('isAdmin')
    <li>
        <a href="{{ route('landing-content.index') }}" @click="sidebarOpen = false" class="flex items-center space-x-3 px-3 sm:px-4 py-2.5 sm:py-3 text-gray-700 hover:bg-brand-bg hover:text-brand-primary rounded-lg transition-colors {{ request()->routeIs('landing-content.*') ? 'bg-brand-bg text-brand-primary font-semibold' : '' }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" />
            </svg>
            <span class="font-medium text-sm sm:text-base">CMS Landing</span>
        </a>
    </li>
    @endcan
    
    <!-- Setting - Hanya Admin -->
    @can('isAdmin')
    <li>
        <a href="{{ route('settings.index') }}" @click="sidebarOpen = false" class="flex items-center space-x-3 px-3 sm:px-4 py-2.5 sm:py-3 text-gray-700 hover:bg-brand-bg hover:text-brand-primary rounded-lg transition-colors {{ request()->routeIs('settings.*') ? 'bg-brand-bg text-brand-primary font-semibold' : '' }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            <span class="font-medium text-sm sm:text-base">Setting</span>
        </a>
    </li>
    @endcan
    
    <!-- Logout -->
    <li class="pt-3 sm:pt-4 mt-3 sm:mt-4 border-t border-gray-100">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center space-x-3 px-3 sm:px-4 py-2.5 sm:py-3 text-gray-700 hover:bg-red-50 hover:text-red-600 rounded-lg transition-colors">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                <span class="font-medium text-sm sm:text-base">Logout</span>
            </button>
        </form>
    </li>
</ul>
