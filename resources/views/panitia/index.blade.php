@extends('layouts.app')

@section('title', 'Data Panitia - IKPM Kalbar')
@section('page-title', 'Data Panitia')
@section('page-subtitle', 'Santri Kelas 4 - Calon Panitia')

@section('content')
    <!-- Header dengan Tombol Export -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 sm:mb-6 gap-3 sm:gap-4">
        <div>
            <h2 class="text-lg sm:text-2xl font-bold text-gray-800">Data Panitia (Kelas 4)</h2>
            <p class="text-xs sm:text-sm text-gray-600 mt-0.5 sm:mt-1">Total: {{ $santris->total() }} santri</p>
        </div>
        <a 
            href="{{ route('panitia.export') }}" 
            class="w-full sm:w-auto inline-flex items-center justify-center space-x-1.5 sm:space-x-2 px-4 py-2 sm:py-2.5 bg-brand-accent text-white rounded-lg hover:bg-amber-700 transition-colors focus:outline-none focus:ring-2 focus:ring-amber-200 focus:ring-offset-2 text-sm"
        >
            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <span>Export Excel</span>
        </a>
    </div>
    
    <!-- Filter Bar -->
    <div class="bg-white rounded-xl shadow-sm border border-green-100 p-3 sm:p-4 mb-4 sm:mb-6">
        <form action="{{ route('panitia.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-2 sm:gap-3 lg:gap-4">
            <div class="sm:col-span-2 lg:col-span-2">
                <label for="search" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Cari</label>
                <input 
                    type="text" 
                    name="search" 
                    id="search"
                    value="{{ request('search') }}"
                    placeholder="Nama, Stambuk, atau Daerah..."
                    class="w-full px-3 sm:px-4 py-2 sm:py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-200 focus:border-brand-primary transition-colors text-gray-800 placeholder-gray-400 text-sm"
                >
            </div>
            <div>
                <label for="provinsi" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Provinsi</label>
                <select 
                    id="provinsi" 
                    name="provinsi" 
                    class="w-full px-3 sm:px-4 py-2 sm:py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-200 focus:border-brand-primary transition-colors text-gray-800 bg-white text-sm"
                >
                    <option value="">Semua</option>
                    @foreach($provinsis as $prov)
                        <option value="{{ $prov }}" {{ request('provinsi') == $prov ? 'selected' : '' }}>
                            {{ $prov }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end space-x-2">
                <button 
                    type="submit" 
                    class="flex-1 px-4 sm:px-6 py-2 sm:py-2.5 bg-brand-primary text-white rounded-lg hover:bg-green-700 transition-colors focus:outline-none focus:ring-2 focus:ring-green-200 focus:ring-offset-2 text-sm font-medium"
                >
                    Filter
                </button>
                @if(request('search') || request('provinsi'))
                    <a 
                        href="{{ route('panitia.index') }}" 
                        class="px-4 py-2 sm:py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors text-sm font-medium"
                    >
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Mobile Card View -->
    <div class="block sm:hidden space-y-3 mb-4">
        @forelse($santris as $index => $santri)
        <div class="bg-white rounded-xl shadow-sm border border-green-100 p-4">
            <div class="flex items-start justify-between mb-2">
                <div>
                    <h3 class="text-sm font-semibold text-gray-800">{{ $santri->nama }}</h3>
                    <p class="text-xs text-gray-500">{{ $santri->stambuk }}</p>
                </div>
                <span class="text-xs text-gray-500">#{{ $santris->firstItem() + $index }}</span>
            </div>
            <div class="grid grid-cols-2 gap-2 text-xs">
                <div>
                    <span class="text-gray-500">Provinsi:</span>
                    <span class="text-gray-800 font-medium ml-1">{{ $santri->provinsi }}</span>
                </div>
                <div>
                    <span class="text-gray-500">Daerah:</span>
                    <span class="text-gray-800 font-medium ml-1">{{ $santri->daerah }}</span>
                </div>
            </div>
            @if($santri->alamat)
            <p class="text-xs text-gray-500 mt-2 pt-2 border-t border-gray-100">{{ $santri->alamat }}</p>
            @endif
        </div>
        @empty
        <div class="bg-white rounded-xl shadow-sm border border-green-100 p-8 text-center">
            <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            <p class="text-sm font-medium text-gray-600">
                @if(request('search') || request('provinsi'))
                    Tidak ada data yang sesuai dengan filter
                @else
                    Belum ada santri kelas 4
                @endif
            </p>
        </div>
        @endforelse
    </div>
    
    <!-- Desktop Table View -->
    <div class="hidden sm:block bg-white rounded-xl shadow-sm border border-green-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-brand-bg">
                    <tr>
                        <th class="px-4 lg:px-6 py-3 text-left text-xs font-semibold text-brand-primary uppercase tracking-wider">No</th>
                        <th class="px-4 lg:px-6 py-3 text-left text-xs font-semibold text-brand-primary uppercase tracking-wider">Stambuk</th>
                        <th class="px-4 lg:px-6 py-3 text-left text-xs font-semibold text-brand-primary uppercase tracking-wider">Nama</th>
                        <th class="px-4 lg:px-6 py-3 text-left text-xs font-semibold text-brand-primary uppercase tracking-wider hidden lg:table-cell">Provinsi</th>
                        <th class="px-4 lg:px-6 py-3 text-left text-xs font-semibold text-brand-primary uppercase tracking-wider">Daerah</th>
                        <th class="px-4 lg:px-6 py-3 text-left text-xs font-semibold text-brand-primary uppercase tracking-wider hidden lg:table-cell">Alamat</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($santris as $index => $santri)
                    <tr class="hover:bg-brand-bg transition-colors {{ $index % 2 == 0 ? 'bg-white' : 'bg-gray-50' }}">
                        <td class="px-4 lg:px-6 py-3 lg:py-4 whitespace-nowrap text-xs lg:text-sm text-gray-700">
                            {{ $santris->firstItem() + $index }}
                        </td>
                        <td class="px-4 lg:px-6 py-3 lg:py-4 whitespace-nowrap">
                            <span class="text-xs lg:text-sm font-medium text-gray-800">{{ $santri->stambuk }}</span>
                        </td>
                        <td class="px-4 lg:px-6 py-3 lg:py-4">
                            <div class="text-xs lg:text-sm font-medium text-gray-800 truncate max-w-[150px] lg:max-w-none">{{ $santri->nama }}</div>
                        </td>
                        <td class="px-4 lg:px-6 py-3 lg:py-4 whitespace-nowrap text-xs lg:text-sm text-gray-700 hidden lg:table-cell">
                            {{ $santri->provinsi }}
                        </td>
                        <td class="px-4 lg:px-6 py-3 lg:py-4 whitespace-nowrap text-xs lg:text-sm text-gray-700">
                            <div class="truncate max-w-[100px] lg:max-w-none">{{ $santri->daerah }}</div>
                        </td>
                        <td class="px-4 lg:px-6 py-3 lg:py-4 text-xs lg:text-sm text-gray-700 hidden lg:table-cell">
                            <div class="truncate max-w-[200px]">{{ $santri->alamat ?? '-' }}</div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center">
                            <div class="text-gray-500">
                                <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                <p class="text-sm font-medium">
                                    @if(request('search') || request('provinsi'))
                                        Tidak ada santri kelas 4 yang sesuai dengan filter
                                    @else
                                        Belum ada santri kelas 4 yang terdaftar
                                    @endif
                                </p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($santris->hasPages())
        <div class="px-4 lg:px-6 py-3 lg:py-4 border-t border-gray-100 bg-brand-bg">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-2">
                <div class="text-xs sm:text-sm text-gray-600">
                    {{ $santris->firstItem() }}-{{ $santris->lastItem() }} dari {{ $santris->total() }}
                </div>
                <div class="flex items-center space-x-2">
                    {{ $santris->links() }}
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Mobile Pagination -->
    @if($santris->hasPages())
    <div class="block sm:hidden mt-4 px-2">
        {{ $santris->links() }}
    </div>
    @endif
@endsection
