@extends('layouts.app')

@section('title', 'Manajemen Perpulangan - IKPM Kalbar')
@section('page-title', 'Manajemen Perpulangan')
@section('page-subtitle', 'Kelola rombongan perpulangan santri')

@section('content')
    <!-- Header dengan Tombol Tambah -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 sm:mb-6 gap-3 sm:gap-4">
        <div>
            <h2 class="text-lg sm:text-2xl font-bold text-gray-800">Daftar Rombongan</h2>
            <p class="text-xs sm:text-sm text-gray-600 mt-0.5 sm:mt-1">Total: {{ $rombongans->total() }} rombongan</p>
        </div>
        @can('canManageRombongan')
        <a 
            href="{{ route('rombongan.create') }}" 
            class="w-full sm:w-auto inline-flex items-center justify-center space-x-1.5 sm:space-x-2 px-4 py-2 sm:py-2.5 bg-brand-primary text-white rounded-lg hover:bg-green-700 transition-colors focus:outline-none focus:ring-2 focus:ring-green-200 focus:ring-offset-2 text-sm"
        >
            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            <span>Tambah Rombongan</span>
        </a>
        @endcan
    </div>

    <!-- Grid Rombongan Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4 lg:gap-6">
        @forelse($rombongans as $rombongan)
        <div class="bg-white rounded-xl shadow-sm border border-green-100 p-4 sm:p-6 hover:shadow-md transition-shadow">
            <!-- Header Card -->
            <div class="flex items-start justify-between mb-3 sm:mb-4">
                <div class="flex-1 min-w-0 pr-2">
                    <h3 class="text-base sm:text-lg font-bold text-gray-800 mb-1 truncate">{{ $rombongan->nama }}</h3>
                    <div class="flex flex-wrap items-center gap-1.5 sm:gap-2 text-sm text-gray-600">
                        <span class="px-2 py-0.5 bg-emerald-100 text-emerald-700 rounded-md text-xs font-medium">
                            {{ $rombongan->moda_transportasi }}
                        </span>
                        <span class="px-2 py-0.5 {{ $rombongan->status === 'open' ? 'bg-green-100 text-green-700' : ($rombongan->status === 'departed' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700') }} rounded-md text-xs font-medium">
                            {{ ucfirst($rombongan->status) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Info Rombongan -->
            <div class="space-y-1.5 sm:space-y-2 mb-3 sm:mb-4 text-xs sm:text-sm text-gray-600">
                <div class="flex items-center space-x-2">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span>{{ $rombongan->waktu_berangkat->format('d M Y, H:i') }}</span>
                </div>
                <div class="flex items-center space-x-2">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Rp {{ number_format($rombongan->biaya_per_orang, 0, ',', '.') }} / santri</span>
                </div>
            </div>

            <!-- Progress Bar -->
            <div class="mb-3 sm:mb-4">
                <div class="flex items-center justify-between text-xs sm:text-sm mb-1.5 sm:mb-2">
                    <span class="text-gray-700 font-medium">Kapasitas</span>
                    <span class="text-gray-600">
                        {{ $rombongan->santris_count }} / {{ $rombongan->kapasitas }}
                    </span>
                </div>
                @php
                    $persentase = $rombongan->kapasitas > 0 ? ($rombongan->santris_count / $rombongan->kapasitas) * 100 : 0;
                    $colorClass = $persentase >= 100 ? 'bg-red-400' : ($persentase >= 80 ? 'bg-yellow-400' : 'bg-emerald-400');
                @endphp
                <div class="w-full bg-gray-200 rounded-full h-2.5 sm:h-3 overflow-hidden">
                    <div 
                        class="h-2.5 sm:h-3 rounded-full transition-all duration-300 {{ $colorClass }}"
                        style="width: {{ min(100, $persentase) }}%"
                    ></div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center space-x-2">
                <a 
                    href="{{ route('rombongan.show', $rombongan) }}" 
                    class="flex-1 text-center px-3 sm:px-4 py-2 bg-brand-bg text-brand-primary rounded-lg hover:bg-green-100 transition-colors text-xs sm:text-sm font-medium"
                >
                    Detail & Manifest
                </a>
                @can('canManageRombongan')
                <a 
                    href="{{ route('rombongan.edit', $rombongan) }}" 
                    class="p-2 bg-amber-100 text-amber-700 rounded-lg hover:bg-amber-200 transition-colors"
                    title="Edit"
                >
                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </a>
                <form 
                    action="{{ route('rombongan.destroy', $rombongan) }}" 
                    method="POST" 
                    onsubmit="return confirm('Hapus rombongan ini?')"
                    class="inline"
                >
                    @csrf
                    @method('DELETE')
                    <button 
                        type="submit" 
                        class="p-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors"
                        title="Hapus"
                    >
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </form>
                @endcan
            </div>
        </div>
        @empty
        <div class="col-span-full">
            <div class="bg-white rounded-xl shadow-sm border border-green-100 p-8 sm:p-12 text-center">
                <div class="w-16 h-16 sm:w-20 sm:h-20 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-3 sm:mb-4">
                    <svg class="w-8 h-8 sm:w-10 sm:h-10 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                    </svg>
                </div>
                <h3 class="text-lg sm:text-xl font-semibold text-gray-800 mb-2">Belum Ada Rombongan</h3>
                <p class="text-sm text-gray-600 mb-4 sm:mb-6">Mulai dengan membuat rombongan perpulangan pertama.</p>
                @can('canManageRombongan')
                <a 
                    href="{{ route('rombongan.create') }}" 
                    class="inline-flex items-center space-x-2 px-4 sm:px-6 py-2.5 sm:py-3 bg-brand-primary text-white rounded-lg hover:bg-green-700 transition-colors text-sm"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Tambah Rombongan</span>
                </a>
                @endcan
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($rombongans->hasPages())
    <div class="mt-4 sm:mt-6">
        {{ $rombongans->links() }}
    </div>
    @endif
@endsection
