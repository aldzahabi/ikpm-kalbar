@extends('layouts.app')

@section('title', 'Detail Santri - ' . $santri->nama)
@section('page-title', 'Detail Santri')
@section('page-subtitle', 'Informasi lengkap data santri')

@php
    $pondokCabangList = \App\Models\Santri::getPondokCabangList();
    $pondokName = $santri->pondok_cabang ? ($pondokCabangList[$santri->pondok_cabang] ?? 'Gontor ' . $santri->pondok_cabang) : null;
@endphp

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Back Button -->
    <div class="mb-4 sm:mb-6">
        <a href="{{ route('santri.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-brand-primary transition-colors">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Kembali ke Daftar
        </a>
    </div>

    <!-- ID Card Style -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden border-2 border-green-200">
        <!-- Card Header with Gradient -->
        <div class="relative bg-gradient-to-r from-green-600 via-green-500 to-emerald-500 px-4 sm:px-8 py-6 sm:py-8">
            <!-- Pattern Overlay -->
            <div class="absolute inset-0 opacity-10">
                <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <pattern id="pattern" x="0" y="0" width="40" height="40" patternUnits="userSpaceOnUse">
                            <circle cx="20" cy="20" r="2" fill="white"/>
                        </pattern>
                    </defs>
                    <rect width="100%" height="100%" fill="url(#pattern)"/>
                </svg>
            </div>
            
            <div class="relative flex flex-col sm:flex-row items-center gap-4 sm:gap-6">
                <!-- Photo -->
                <div class="flex-shrink-0">
                    @if($santri->foto_diri)
                        <div class="w-28 h-28 sm:w-36 sm:h-36 rounded-xl border-4 border-white shadow-lg overflow-hidden bg-white">
                            <img src="{{ asset('storage/' . $santri->foto_diri) }}" alt="Foto {{ $santri->nama }}" class="w-full h-full object-cover">
                        </div>
                    @else
                        <div class="w-28 h-28 sm:w-36 sm:h-36 rounded-xl border-4 border-white shadow-lg bg-white/20 backdrop-blur flex items-center justify-center">
                            <div class="text-center">
                                <svg class="w-12 h-12 sm:w-16 sm:h-16 mx-auto text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <span class="text-[10px] text-white/60 mt-1">No Photo</span>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Basic Info -->
                <div class="text-center sm:text-left flex-1">
                    <div class="mb-2">
                        @if($santri->status == 'santri')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-white/20 text-white border border-white/30">
                                <span class="w-2 h-2 bg-green-300 rounded-full mr-2 animate-pulse"></span>
                                SANTRI AKTIF
                            </span>
                        @elseif($santri->status == 'ustad')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-white/20 text-white border border-white/30">
                                <span class="w-2 h-2 bg-indigo-300 rounded-full mr-2"></span>
                                USTAD (THN {{ $santri->ustadTahunKe() ?? '—' }})
                            </span>
                        @elseif($santri->status == 'alumni')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-white/20 text-white border border-white/30">
                                <span class="w-2 h-2 bg-blue-300 rounded-full mr-2"></span>
                                ALUMNI
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-white/20 text-white border border-white/30">
                                <span class="w-2 h-2 bg-gray-300 rounded-full mr-2"></span>
                                {{ strtoupper($santri->status) }}
                            </span>
                        @endif
                    </div>
                    <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-white mb-1 tracking-wide">
                        {{ strtoupper($santri->nama) }}
                    </h1>
                    <div class="flex flex-col sm:flex-row items-center gap-2 sm:gap-4 text-white/90 text-sm">
                        <span class="font-mono bg-white/20 px-3 py-1 rounded-lg text-lg sm:text-xl font-bold tracking-widest">
                            {{ $santri->stambuk }}
                        </span>
                        @if($santri->kelas)
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                                @if($santri->status === 'ustad')
                                    Tahun ustad ke-{{ $santri->kelas }}
                                @else
                                    Kelas {{ $santri->kelas }}
                                @endif
                            </span>
                        @endif
                    </div>
                    @if($pondokName)
                        <div class="mt-2 text-white/80 text-xs sm:text-sm flex items-center justify-center sm:justify-start">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            {{ $pondokName }}
                        </div>
                    @endif
                </div>

                <!-- Logo IKPM -->
                <div class="hidden lg:flex flex-shrink-0 flex-col items-center">
                    <div class="w-20 h-20 bg-white/20 backdrop-blur rounded-xl p-2 border border-white/30">
                        <div class="w-full h-full bg-white rounded-lg flex items-center justify-center">
                            <span class="text-green-600 font-bold text-xs text-center leading-tight">IKPM<br>GONTOR<br>PONTIANAK</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Body - Data Details -->
        <div class="p-4 sm:p-6 lg:p-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-8">
                <!-- Left Column - Personal Data -->
                <div>
                    <h3 class="flex items-center text-sm font-bold text-gray-700 mb-4 pb-2 border-b border-gray-200">
                        <span class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center mr-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </span>
                        Data Pribadi
                    </h3>
                    
                    <div class="space-y-3">
                        <div class="flex items-start">
                            <span class="w-28 flex-shrink-0 text-xs text-gray-500">NIK</span>
                            <span class="text-sm font-medium text-gray-800 font-mono">{{ $santri->nik ?? '-' }}</span>
                        </div>
                        <div class="flex items-start">
                            <span class="w-28 flex-shrink-0 text-xs text-gray-500">Tempat Lahir</span>
                            <span class="text-sm font-medium text-gray-800">{{ $santri->tempat_lahir ?? '-' }}</span>
                        </div>
                        <div class="flex items-start">
                            <span class="w-28 flex-shrink-0 text-xs text-gray-500">Tanggal Lahir</span>
                            <span class="text-sm font-medium text-gray-800">
                                {{ $santri->tanggal_lahir ? $santri->tanggal_lahir->format('d F Y') : '-' }}
                            </span>
                        </div>
                        <div class="flex items-start">
                            <span class="w-28 flex-shrink-0 text-xs text-gray-500">Nama Ayah</span>
                            <span class="text-sm font-medium text-gray-800">{{ $santri->nama_ayah ?? '-' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Address & Academic -->
                <div>
                    <h3 class="flex items-center text-sm font-bold text-gray-700 mb-4 pb-2 border-b border-gray-200">
                        <span class="w-8 h-8 rounded-lg bg-green-100 text-green-600 flex items-center justify-center mr-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </span>
                        Alamat & Akademik
                    </h3>
                    
                    <div class="space-y-3">
                        <div class="flex items-start">
                            <span class="w-28 flex-shrink-0 text-xs text-gray-500">Provinsi</span>
                            <span class="text-sm font-medium text-gray-800">{{ $santri->provinsi }}</span>
                        </div>
                        <div class="flex items-start">
                            <span class="w-28 flex-shrink-0 text-xs text-gray-500">Kab/Kota</span>
                            <span class="text-sm font-medium text-gray-800">{{ $santri->daerah }}</span>
                        </div>
                        <div class="flex items-start">
                            <span class="w-28 flex-shrink-0 text-xs text-gray-500">Alamat</span>
                            <span class="text-sm font-medium text-gray-800 flex-1">{{ $santri->alamat ?? '-' }}</span>
                        </div>
                        <div class="flex items-start">
                            <span class="w-28 flex-shrink-0 text-xs text-gray-500">Kenaikan Kelas</span>
                            <span class="text-sm font-medium text-gray-800">
                                @if($santri->kenaikan_kelas == 'naik')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs bg-green-100 text-green-700">Naik</span>
                                @elseif($santri->kenaikan_kelas == 'tidak_naik')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs bg-red-100 text-red-700">Tidak Naik</span>
                                @elseif($santri->kenaikan_kelas == 'lulus')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs bg-blue-100 text-blue-700">Lulus</span>
                                @elseif($santri->kenaikan_kelas == 'baru')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs bg-purple-100 text-purple-700">Baru</span>
                                @else
                                    -
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Foto KK Section -->
            @if($santri->foto_kk)
            <div class="mt-6 lg:mt-8 pt-6 border-t border-gray-200">
                <h3 class="flex items-center text-sm font-bold text-gray-700 mb-4">
                    <span class="w-8 h-8 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center mr-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </span>
                    Dokumen Kartu Keluarga
                </h3>
                <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                    <a href="{{ asset('storage/' . $santri->foto_kk) }}" target="_blank" class="block">
                        <img src="{{ asset('storage/' . $santri->foto_kk) }}" alt="Foto KK" class="max-w-full h-auto max-h-64 mx-auto rounded-lg shadow-sm hover:shadow-md transition-shadow">
                    </a>
                    <p class="text-xs text-gray-500 text-center mt-2">Klik untuk melihat ukuran penuh</p>
                </div>
            </div>
            @endif

            <!-- Timestamps -->
            <div class="mt-6 lg:mt-8 pt-6 border-t border-gray-200">
                <div class="flex flex-wrap gap-4 text-xs text-gray-500">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Ditambahkan: {{ $santri->created_at->format('d M Y, H:i') }}
                    </div>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Terakhir diubah: {{ $santri->updated_at->format('d M Y, H:i') }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Footer - Actions -->
        @if(auth()->user() && auth()->user()->canManageSantri($santri))
        <div class="px-4 sm:px-6 lg:px-8 py-4 bg-gray-50 border-t border-gray-200 flex flex-col sm:flex-row items-stretch sm:items-center justify-end gap-2 sm:gap-3">
            <a 
                href="{{ route('santri.edit', $santri->stambuk) }}" 
                class="inline-flex items-center justify-center px-4 py-2.5 bg-amber-500 text-white rounded-lg hover:bg-amber-600 transition-colors text-sm font-medium"
            >
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit Data
            </a>
            <form 
                action="{{ route('santri.destroy', $santri->stambuk) }}" 
                method="POST" 
                class="inline"
                onsubmit="return confirm('Apakah Anda yakin ingin menghapus data {{ $santri->nama }}?')"
            >
                @csrf
                @method('DELETE')
                <button 
                    type="submit" 
                    class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2.5 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors text-sm font-medium"
                >
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Hapus
                </button>
            </form>
        </div>
        @endif
    </div>

    <!-- Quick Info Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4 mt-6">
        <div class="bg-white rounded-xl p-4 border border-green-100 shadow-sm text-center">
            <div class="w-10 h-10 mx-auto rounded-full bg-green-100 flex items-center justify-center mb-2">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <p class="text-xs text-gray-500">Status</p>
            <p class="text-sm font-bold text-gray-800 capitalize">{{ $santri->status }}</p>
        </div>
        <div class="bg-white rounded-xl p-4 border border-blue-100 shadow-sm text-center">
            <div class="w-10 h-10 mx-auto rounded-full bg-blue-100 flex items-center justify-center mb-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
            </div>
            <p class="text-xs text-gray-500">Kelas</p>
            <p class="text-sm font-bold text-gray-800">{{ $santri->kelas ?? '-' }}</p>
        </div>
        <div class="bg-white rounded-xl p-4 border border-purple-100 shadow-sm text-center">
            <div class="w-10 h-10 mx-auto rounded-full bg-purple-100 flex items-center justify-center mb-2">
                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
            <p class="text-xs text-gray-500">Pondok</p>
            <p class="text-sm font-bold text-gray-800">{{ $santri->pondok_cabang ? 'Gontor ' . $santri->pondok_cabang : '-' }}</p>
        </div>
        <div class="bg-white rounded-xl p-4 border border-amber-100 shadow-sm text-center">
            <div class="w-10 h-10 mx-auto rounded-full bg-amber-100 flex items-center justify-center mb-2">
                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
            <p class="text-xs text-gray-500">Asal</p>
            <p class="text-sm font-bold text-gray-800 truncate">{{ $santri->daerah }}</p>
        </div>
    </div>
</div>
@endsection
