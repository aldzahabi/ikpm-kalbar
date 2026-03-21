@extends('layouts.app')

@section('title', 'Keuangan - IKPM Gontor Pontianak')
@section('page-title', 'Keuangan')
@section('page-subtitle', 'Manajemen keuangan multi-account')

@push('styles')
<style>
    /* Hide scrollbar but allow scrolling */
    .scrollbar-hide {
        -ms-overflow-style: none;  /* IE and Edge */
        scrollbar-width: none;  /* Firefox */
    }
    .scrollbar-hide::-webkit-scrollbar {
        display: none;  /* Chrome, Safari and Opera */
    }
</style>
@endpush


@section('content')
    <!-- Flash Messages -->
    @if(session('success'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-lg">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-lg">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        </div>
    @endif

    <!-- Header dengan Tombol Tambah -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 sm:mb-6 gap-3 sm:gap-4">
        <div>
            <h2 class="text-lg sm:text-2xl font-bold text-gray-800">Dashboard Keuangan</h2>
            <p class="text-xs sm:text-sm text-gray-600 mt-0.5 sm:mt-1">Multi-Account Financial Management</p>
        </div>
        @can('canManageFinance')
        <div class="flex items-center space-x-2 sm:space-x-3 w-full sm:w-auto overflow-x-auto pb-2 sm:pb-0">
            <!-- Export Buttons -->
            <div class="flex items-center space-x-2 flex-shrink-0">
                <a 
                    href="{{ route('keuangan.export.excel', array_merge(request()->all(), ['tab' => $activeTab])) }}" 
                    class="inline-flex items-center space-x-1.5 sm:space-x-2 px-3 sm:px-4 py-2 sm:py-2.5 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:ring-offset-2 text-xs sm:text-sm"
                    title="Export Excel"
                >
                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span class="hidden sm:inline">Excel</span>
                </a>
                <a 
                    href="{{ route('keuangan.export.pdf', array_merge(request()->all(), ['tab' => $activeTab])) }}" 
                    class="inline-flex items-center space-x-1.5 sm:space-x-2 px-3 sm:px-4 py-2 sm:py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors focus:outline-none focus:ring-2 focus:ring-red-200 focus:ring-offset-2 text-xs sm:text-sm"
                    title="Export PDF"
                    target="_blank"
                >
                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                    <span class="hidden sm:inline">PDF</span>
                </a>
            </div>
            <a 
                href="{{ route('keuangan.create') }}" 
                class="inline-flex items-center space-x-1.5 sm:space-x-2 px-3 sm:px-4 py-2 sm:py-2.5 bg-brand-primary text-white rounded-lg hover:bg-green-700 transition-colors focus:outline-none focus:ring-2 focus:ring-green-200 focus:ring-offset-2 text-xs sm:text-sm flex-shrink-0"
            >
                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span class="whitespace-nowrap">Catat Transaksi</span>
            </a>
        </div>
        @endcan
    </div>

    <!-- Kartu Saldo per Akun -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4 lg:gap-6 mb-4 sm:mb-6">
        @foreach($accounts as $account)
            <div class="bg-white rounded-xl shadow-sm border border-green-100 p-4 sm:p-6 hover:shadow-md transition-shadow">
                <div class="flex items-start justify-between mb-3 sm:mb-4">
                    <div class="min-w-0 flex-1 pr-2">
                        <h3 class="text-sm sm:text-lg font-semibold text-gray-800 mb-0.5 sm:mb-1 truncate">{{ $account->name }}</h3>
                        <p class="text-xs text-gray-500 line-clamp-2">{{ Str::limit($account->description, 50) }}</p>
                    </div>
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-brand-bg rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-brand-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div class="pt-3 sm:pt-4 border-t border-gray-100">
                    <p class="text-xs sm:text-sm text-gray-600 mb-0.5 sm:mb-1">Saldo Saat Ini</p>
                    <p class="text-lg sm:text-2xl font-bold {{ $account->current_balance >= 0 ? 'text-emerald-600' : 'text-red-600' }}">
                        Rp {{ number_format($account->current_balance, 0, ',', '.') }}
                    </p>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Tab Navigation -->
    <div x-data="{ activeTab: '{{ $activeTab }}' }" class="bg-white rounded-xl shadow-sm border border-green-100 overflow-hidden">
        <!-- Tab Headers -->
        <div class="border-b border-gray-200">
            <nav class="flex -mb-px overflow-x-auto scrollbar-hide" aria-label="Tabs" style="-webkit-overflow-scrolling: touch;">
                <button 
                    @click="activeTab = 'ringkasan'; window.location.href = '{{ route('keuangan.index', ['tab' => 'ringkasan']) }}'"
                    :class="activeTab === 'ringkasan' ? 'border-brand-primary text-brand-primary bg-brand-bg/50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="flex-shrink-0 min-w-[100px] sm:flex-1 px-4 sm:px-6 py-3 sm:py-4 text-xs sm:text-sm font-medium text-center border-b-2 transition-colors"
                >
                    <div class="flex items-center justify-center space-x-1.5 sm:space-x-2">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        <span>Ringkasan</span>
                    </div>
                </button>
                <button 
                    @click="activeTab = 'kas-ikpm'; window.location.href = '{{ route('keuangan.index', ['tab' => 'kas-ikpm']) }}'"
                    :class="activeTab === 'kas-ikpm' ? 'border-brand-primary text-brand-primary bg-brand-bg/50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="flex-shrink-0 min-w-[100px] sm:flex-1 px-4 sm:px-6 py-3 sm:py-4 text-xs sm:text-sm font-medium text-center border-b-2 transition-colors"
                >
                    <div class="flex items-center justify-center space-x-1.5 sm:space-x-2">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <span class="whitespace-nowrap">Kas IKPM</span>
                    </div>
                </button>
                <button 
                    @click="activeTab = 'perpulangan'; window.location.href = '{{ route('keuangan.index', ['tab' => 'perpulangan']) }}'"
                    :class="activeTab === 'perpulangan' ? 'border-brand-primary text-brand-primary bg-brand-bg/50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="flex-shrink-0 min-w-[100px] sm:flex-1 px-4 sm:px-6 py-3 sm:py-4 text-xs sm:text-sm font-medium text-center border-b-2 transition-colors"
                >
                    <div class="flex items-center justify-center space-x-1.5 sm:space-x-2">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                        </svg>
                        <span>Perpulangan</span>
                    </div>
                </button>
                <button 
                    @click="activeTab = 'forbis'; window.location.href = '{{ route('keuangan.index', ['tab' => 'forbis']) }}'"
                    :class="activeTab === 'forbis' ? 'border-brand-primary text-brand-primary bg-brand-bg/50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="flex-shrink-0 min-w-[100px] sm:flex-1 px-4 sm:px-6 py-3 sm:py-4 text-xs sm:text-sm font-medium text-center border-b-2 transition-colors"
                >
                    <div class="flex items-center justify-center space-x-1.5 sm:space-x-2">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <span>Forbis</span>
                    </div>
                </button>
            </nav>
        </div>

        <!-- Tab Content -->
        <div class="p-3 sm:p-6">
            <!-- Tab 1: Ringkasan -->
            <div x-show="activeTab === 'ringkasan'" x-transition style="display: {{ $activeTab === 'ringkasan' ? 'block' : 'none' }};">
                <div class="mb-4 sm:mb-6">
                    <h3 class="text-base sm:text-xl font-semibold text-gray-800 mb-3 sm:mb-4">Ringkasan Keuangan Total</h3>
                    
                    <!-- Summary Cards -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4 lg:gap-6 mb-4 sm:mb-6">
                        <div class="bg-green-50 rounded-lg p-4 sm:p-6 border border-green-200">
                            <div class="flex items-center justify-between mb-1.5 sm:mb-2">
                                <span class="text-xs sm:text-sm font-medium text-green-800">Total Pemasukan</span>
                                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                </svg>
                            </div>
                            <p class="text-lg sm:text-2xl font-bold text-green-700">Rp {{ number_format($totalIncome, 0, ',', '.') }}</p>
                        </div>
                        <div class="bg-red-50 rounded-lg p-4 sm:p-6 border border-red-200">
                            <div class="flex items-center justify-between mb-1.5 sm:mb-2">
                                <span class="text-xs sm:text-sm font-medium text-red-800">Total Pengeluaran</span>
                                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" />
                                </svg>
                            </div>
                            <p class="text-lg sm:text-2xl font-bold text-red-700">Rp {{ number_format($totalExpense, 0, ',', '.') }}</p>
                        </div>
                        <div class="bg-blue-50 rounded-lg p-4 sm:p-6 border border-blue-200">
                            <div class="flex items-center justify-between mb-1.5 sm:mb-2">
                                <span class="text-xs sm:text-sm font-medium text-blue-800">Saldo Bersih</span>
                                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                            </div>
                            <p class="text-lg sm:text-2xl font-bold {{ $netBalance >= 0 ? 'text-blue-700' : 'text-red-700' }}">
                                Rp {{ number_format($netBalance, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>

                    <!-- Simple Bar Chart -->
                    <div class="bg-gray-50 rounded-lg p-4 sm:p-6 mb-4 sm:mb-6">
                        <h4 class="text-sm sm:text-lg font-semibold text-gray-800 mb-3 sm:mb-4">Grafik Pemasukan vs Pengeluaran</h4>
                        <div class="space-y-3 sm:space-y-4">
                            <div>
                                <div class="flex justify-between items-center mb-1.5 sm:mb-2">
                                    <span class="text-xs sm:text-sm font-medium text-gray-700">Pemasukan</span>
                                    <span class="text-xs sm:text-sm font-semibold text-green-700">Rp {{ number_format($totalIncome, 0, ',', '.') }}</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-5 sm:h-6">
                                    <div class="bg-green-500 h-5 sm:h-6 rounded-full flex items-center justify-end pr-2" style="width: {{ $totalIncome > 0 && ($totalIncome + $totalExpense) > 0 ? ($totalIncome / ($totalIncome + $totalExpense) * 100) : 0 }}%">
                                        <span class="text-xs text-white font-medium">{{ $totalIncome > 0 && ($totalIncome + $totalExpense) > 0 ? number_format(($totalIncome / ($totalIncome + $totalExpense) * 100), 1) : 0 }}%</span>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="flex justify-between items-center mb-1.5 sm:mb-2">
                                    <span class="text-xs sm:text-sm font-medium text-gray-700">Pengeluaran</span>
                                    <span class="text-xs sm:text-sm font-semibold text-red-700">Rp {{ number_format($totalExpense, 0, ',', '.') }}</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-5 sm:h-6">
                                    <div class="bg-red-500 h-5 sm:h-6 rounded-full flex items-center justify-end pr-2" style="width: {{ $totalExpense > 0 && ($totalIncome + $totalExpense) > 0 ? ($totalExpense / ($totalIncome + $totalExpense) * 100) : 0 }}%">
                                        <span class="text-xs text-white font-medium">{{ $totalExpense > 0 && ($totalIncome + $totalExpense) > 0 ? number_format(($totalExpense / ($totalIncome + $totalExpense) * 100), 1) : 0 }}%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filter & Table (All Transactions) -->
                @include('keuangan.partials.filter-table')
            </div>

            <!-- Tab 2: Kas IKPM -->
            <div x-show="activeTab === 'kas-ikpm'" x-transition style="display: {{ $activeTab === 'kas-ikpm' ? 'block' : 'none' }};">
                <div class="mb-3 sm:mb-4">
                    <h3 class="text-base sm:text-xl font-semibold text-gray-800 mb-1 sm:mb-2">Kas Operasional IKPM</h3>
                    <p class="text-xs sm:text-sm text-gray-600">Transaksi khusus untuk operasional organisasi IKPM</p>
                    @if($accountKasIKPM)
                        <div class="mt-3 sm:mt-4 inline-flex items-center px-3 sm:px-4 py-1.5 sm:py-2 bg-brand-bg rounded-lg">
                            <span class="text-xs sm:text-sm font-medium text-gray-700">Saldo: </span>
                            <span class="text-sm sm:text-lg font-bold text-brand-primary ml-1.5 sm:ml-2">Rp {{ number_format($accountKasIKPM->current_balance, 0, ',', '.') }}</span>
                        </div>
                    @endif
                </div>
                @include('keuangan.partials.filter-table')
            </div>

            <!-- Tab 3: Perpulangan -->
            <div x-show="activeTab === 'perpulangan'" x-transition style="display: {{ $activeTab === 'perpulangan' ? 'block' : 'none' }};">
                <div class="mb-3 sm:mb-4">
                    <h3 class="text-base sm:text-xl font-semibold text-gray-800 mb-1 sm:mb-2">Kas Perpulangan</h3>
                    <p class="text-xs sm:text-sm text-gray-600">Dana khusus untuk manajemen perpulangan santri</p>
                    @if($accountPerpulangan)
                        <div class="mt-3 sm:mt-4 inline-flex items-center px-3 sm:px-4 py-1.5 sm:py-2 bg-brand-bg rounded-lg">
                            <span class="text-xs sm:text-sm font-medium text-gray-700">Saldo: </span>
                            <span class="text-sm sm:text-lg font-bold text-brand-primary ml-1.5 sm:ml-2">Rp {{ number_format($accountPerpulangan->current_balance, 0, ',', '.') }}</span>
                        </div>
                    @endif
                </div>
                @include('keuangan.partials.filter-table')
            </div>

            <!-- Tab 4: Forbis -->
            <div x-show="activeTab === 'forbis'" x-transition style="display: {{ $activeTab === 'forbis' ? 'block' : 'none' }};">
                <div class="mb-3 sm:mb-4">
                    <h3 class="text-base sm:text-xl font-semibold text-gray-800 mb-1 sm:mb-2">Kas Forbis</h3>
                    <p class="text-xs sm:text-sm text-gray-600">Dana khusus untuk kegiatan Forum Bisnis dan Usaha</p>
                    @if($accountForbis)
                        <div class="mt-3 sm:mt-4 inline-flex items-center px-3 sm:px-4 py-1.5 sm:py-2 bg-brand-bg rounded-lg">
                            <span class="text-xs sm:text-sm font-medium text-gray-700">Saldo: </span>
                            <span class="text-sm sm:text-lg font-bold text-brand-primary ml-1.5 sm:ml-2">Rp {{ number_format($accountForbis->current_balance, 0, ',', '.') }}</span>
                        </div>
                    @endif
                </div>
                @include('keuangan.partials.filter-table')
            </div>
        </div>
    </div>
@endsection
