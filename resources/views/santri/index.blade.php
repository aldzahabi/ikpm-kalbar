@extends('layouts.app')

@section('title', 'Data Santri & Alumni - IKPM Gontor Pontianak')
@section('page-title', 'Data Santri & Alumni')
@section('page-subtitle', 'Kelola data santri dan alumni')

@push('styles')
<style>
    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }
</style>
@endpush

@section('content')
    <!-- Header dengan Tombol Tambah -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 sm:mb-6 gap-3 sm:gap-4">
        <div>
            <h2 class="text-lg sm:text-2xl font-bold text-gray-800">Data Santri & Alumni</h2>
            <p class="text-xs sm:text-sm text-gray-600 mt-0.5 sm:mt-1">Total: {{ $santris->total() }} data</p>
        </div>
        @can('canManageSantri')
        <div class="flex items-center space-x-2 sm:space-x-3 w-full sm:w-auto">
            @can('isAdmin')
            <button 
                onclick="document.getElementById('importModal').classList.remove('hidden')"
                class="flex-1 sm:flex-none inline-flex items-center justify-center space-x-1.5 sm:space-x-2 px-3 sm:px-4 py-2 sm:py-2.5 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:ring-offset-2 text-xs sm:text-sm"
            >
                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                </svg>
                <span class="whitespace-nowrap">Import</span>
            </button>
            @endcan
            <a 
                href="{{ route('santri.create') }}" 
                class="flex-1 sm:flex-none inline-flex items-center justify-center space-x-1.5 sm:space-x-2 px-3 sm:px-4 py-2 sm:py-2.5 bg-brand-primary text-white rounded-lg hover:bg-green-700 transition-colors focus:outline-none focus:ring-2 focus:ring-green-200 focus:ring-offset-2 text-xs sm:text-sm"
            >
                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span class="whitespace-nowrap">Tambah</span>
            </a>
        </div>
        @endcan
    </div>
    
    <!-- Search Bar & Filter -->
    <div class="bg-white rounded-xl shadow-sm border border-green-100 p-3 sm:p-4 mb-4 sm:mb-6">
        <form action="{{ route('santri.index') }}" method="GET" class="space-y-3">
            <div class="grid grid-cols-1 sm:grid-cols-12 gap-2 sm:gap-4">
                <div class="sm:col-span-5">
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}"
                        placeholder="Cari Nama atau Stambuk..."
                        class="w-full px-3 sm:px-4 py-2 sm:py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-200 focus:border-brand-primary transition-colors text-gray-800 placeholder-gray-400 text-sm"
                    >
                </div>
                <div class="sm:col-span-4">
                    <select 
                        name="pondok_cabang" 
                        class="w-full px-3 sm:px-4 py-2 sm:py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-200 focus:border-brand-primary transition-colors text-gray-800 bg-white text-sm"
                    >
                        <option value="">-- Semua Pondok Cabang --</option>
                        @foreach($filterPondokList ?? $pondokCabangList as $key => $name)
                            <option value="{{ $key }}" {{ request('pondok_cabang') == $key ? 'selected' : '' }}>
                                Gontor {{ $key }} - {{ Str::limit($name, 25) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="sm:col-span-3 flex items-center space-x-2">
                    <button 
                        type="submit" 
                        class="flex-1 px-4 py-2 sm:py-2.5 bg-brand-primary text-white rounded-lg hover:bg-green-700 transition-colors focus:outline-none focus:ring-2 focus:ring-green-200 focus:ring-offset-2 text-sm font-medium"
                    >
                        Filter
                    </button>
                    @if(request('search') || request('pondok_cabang'))
                        <a 
                            href="{{ route('santri.index') }}" 
                            class="flex-1 px-4 py-2 sm:py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors text-sm font-medium text-center"
                        >
                            Reset
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>
    
    @if(auth()->user() && auth()->user()->isUstad())
    <!-- Ustad Info Banner -->
    <div class="bg-blue-50 border border-blue-200 rounded-xl p-3 sm:p-4 mb-4 sm:mb-6">
        <div class="flex items-start space-x-3">
            <div class="flex-shrink-0">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-blue-800">Anda login sebagai Ustad</p>
                <p class="text-xs text-blue-600 mt-1">
                    Akses terbatas pada santri di pondok: 
                    @php $userPondok = auth()->user()->pondokCabangWithNames(); @endphp
                    @if(count($userPondok) > 0)
                        @foreach($userPondok as $code => $name)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs bg-blue-100 text-blue-700 mr-1">
                                Gontor {{ $code }}
                            </span>
                        @endforeach
                    @else
                        <span class="text-red-600">Belum ada pondok yang ditugaskan</span>
                    @endif
                </p>
            </div>
        </div>
    </div>
    @endif
    
    <!-- Floating Action Bar (Muncul jika ada checkbox terpilih) -->
    @can('isAdmin')
    <div id="floatingActionBar" class="hidden fixed bottom-4 sm:bottom-6 left-4 right-4 sm:left-1/2 sm:right-auto sm:transform sm:-translate-x-1/2 z-50 bg-white rounded-xl shadow-lg border border-green-200 px-4 sm:px-6 py-3 sm:py-4">
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 sm:gap-4">
            <span id="selectedCount" class="text-sm font-medium text-gray-700 text-center sm:text-left">0 santri dipilih</span>
            <div class="flex items-center space-x-2">
                <form id="promoteForm" action="{{ route('santri.promote') }}" method="POST" class="flex-1">
                    @csrf
                    <button 
                        type="submit" 
                        class="w-full px-4 sm:px-6 py-2 sm:py-2.5 bg-brand-primary text-white rounded-lg hover:bg-green-700 transition-colors focus:outline-none focus:ring-2 focus:ring-green-200 focus:ring-offset-2 text-xs sm:text-sm font-medium whitespace-nowrap"
                        onclick="return confirm('Apakah Anda yakin ingin menaikkan kelas santri yang dipilih?')"
                    >
                        Naikkan Kelas
                    </button>
                </form>
                <button 
                    onclick="clearSelection()" 
                    class="px-4 py-2 sm:py-2.5 text-xs sm:text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors"
                >
                    Batal
                </button>
            </div>
        </div>
    </div>
    @endcan

    <!-- Mobile Card View -->
    <div class="block sm:hidden space-y-3 mb-4">
        @forelse($santris as $index => $santri)
        <div class="bg-white rounded-xl shadow-sm border border-green-100 p-4">
            <div class="flex items-start justify-between mb-3">
                <div class="flex items-start space-x-3">
                    @can('isAdmin')
                    <input 
                        type="checkbox" 
                        name="santri_ids[]" 
                        value="{{ $santri->stambuk }}"
                        class="santri-checkbox w-5 h-5 mt-0.5 text-brand-primary border-gray-300 rounded focus:ring-brand-primary"
                        onchange="updateFloatingBar()"
                    >
                    @endcan
                    <div>
                        <h3 class="text-sm font-semibold text-gray-800">{{ $santri->nama }}</h3>
                        <p class="text-xs text-gray-500">{{ $santri->stambuk }}</p>
                    </div>
                </div>
                @if($santri->status == 'santri')
                    <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-green-100 text-green-800">
                        Santri
                    </span>
                @elseif($santri->status == 'alumni')
                    <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                        Alumni
                    </span>
                @else
                    <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-gray-100 text-gray-800">
                        Alumnus
                    </span>
                @endif
            </div>
            <div class="grid grid-cols-2 gap-2 text-xs mb-3">
                <div>
                    <span class="text-gray-500">Kelas:</span>
                    <span class="text-gray-800 font-medium ml-1">{{ $santri->kelas ?? '-' }}</span>
                </div>
                <div>
                    <span class="text-gray-500">Daerah:</span>
                    <span class="text-gray-800 font-medium ml-1">{{ $santri->daerah }}</span>
                </div>
            </div>
            <div class="flex items-center justify-end space-x-2 pt-3 border-t border-gray-100">
                <a 
                    href="{{ route('santri.show', $santri->stambuk) }}" 
                    class="p-2 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg transition-colors"
                    title="Lihat Detail"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </a>
                @if(auth()->user() && auth()->user()->canManageSantri($santri))
                <a 
                    href="{{ route('santri.edit', $santri->stambuk) }}" 
                    class="p-2 text-amber-600 hover:text-amber-800 hover:bg-amber-50 rounded-lg transition-colors"
                    title="Edit"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </a>
                <form 
                    action="{{ route('santri.destroy', $santri->stambuk) }}" 
                    method="POST" 
                    class="inline"
                    onsubmit="return confirm('Hapus data {{ $santri->nama }}?')"
                >
                    @csrf
                    @method('DELETE')
                    <button 
                        type="submit" 
                        class="p-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition-colors"
                        title="Hapus"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </form>
                @endif
            </div>
        </div>
        @empty
        <div class="bg-white rounded-xl shadow-sm border border-green-100 p-8 text-center">
            <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
            </svg>
            <p class="text-sm font-medium text-gray-600">
                @if(request('search'))
                    Tidak ada data yang sesuai dengan "{{ request('search') }}"
                @else
                    Belum ada data santri
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
                        @can('isAdmin')
                        <th class="px-4 lg:px-6 py-3 text-left text-xs font-semibold text-brand-primary uppercase tracking-wider w-12">
                            <input 
                                type="checkbox" 
                                id="selectAll" 
                                class="w-4 h-4 text-brand-primary border-gray-300 rounded focus:ring-brand-primary"
                                onchange="toggleSelectAll(this)"
                            >
                        </th>
                        @endcan
                        <th class="px-4 lg:px-6 py-3 text-left text-xs font-semibold text-brand-primary uppercase tracking-wider">No</th>
                        <th class="px-4 lg:px-6 py-3 text-left text-xs font-semibold text-brand-primary uppercase tracking-wider">Stambuk</th>
                        <th class="px-4 lg:px-6 py-3 text-left text-xs font-semibold text-brand-primary uppercase tracking-wider">Nama</th>
                        <th class="px-4 lg:px-6 py-3 text-left text-xs font-semibold text-brand-primary uppercase tracking-wider hidden lg:table-cell">Kelas</th>
                        <th class="px-4 lg:px-6 py-3 text-left text-xs font-semibold text-brand-primary uppercase tracking-wider">Daerah</th>
                        <th class="px-4 lg:px-6 py-3 text-left text-xs font-semibold text-brand-primary uppercase tracking-wider">Status</th>
                        <th class="px-4 lg:px-6 py-3 text-right text-xs font-semibold text-brand-primary uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($santris as $index => $santri)
                    <tr class="hover:bg-brand-bg transition-colors {{ $index % 2 == 0 ? 'bg-white' : 'bg-gray-50' }}">
                        @can('isAdmin')
                        <td class="px-4 lg:px-6 py-3 lg:py-4 whitespace-nowrap">
                            <input 
                                type="checkbox" 
                                name="santri_ids[]" 
                                value="{{ $santri->stambuk }}"
                                class="santri-checkbox w-4 h-4 text-brand-primary border-gray-300 rounded focus:ring-brand-primary"
                                onchange="updateFloatingBar()"
                            >
                        </td>
                        @endcan
                        <td class="px-4 lg:px-6 py-3 lg:py-4 whitespace-nowrap text-xs lg:text-sm text-gray-700">
                            {{ $santris->firstItem() + $index }}
                        </td>
                        <td class="px-4 lg:px-6 py-3 lg:py-4 whitespace-nowrap">
                            <span class="text-xs lg:text-sm font-medium text-gray-800">{{ $santri->stambuk }}</span>
                        </td>
                        <td class="px-4 lg:px-6 py-3 lg:py-4">
                            <div class="text-xs lg:text-sm font-medium text-gray-800 truncate max-w-[150px] lg:max-w-[200px]">{{ $santri->nama }}</div>
                            @if($santri->alamat)
                                <div class="text-xs text-gray-500 mt-0.5 truncate max-w-[150px] lg:max-w-[200px]">{{ Str::limit($santri->alamat, 30) }}</div>
                            @endif
                        </td>
                        <td class="px-4 lg:px-6 py-3 lg:py-4 whitespace-nowrap text-xs lg:text-sm text-gray-700 hidden lg:table-cell">
                            {{ $santri->kelas ?? '-' }}
                        </td>
                        <td class="px-4 lg:px-6 py-3 lg:py-4 whitespace-nowrap text-xs lg:text-sm text-gray-700">
                            <div class="truncate max-w-[100px]">{{ $santri->daerah }}</div>
                        </td>
                        <td class="px-4 lg:px-6 py-3 lg:py-4 whitespace-nowrap">
                            @if($santri->status == 'santri')
                                <span class="px-2 lg:px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Santri
                                </span>
                            @elseif($santri->status == 'alumni')
                                <span class="px-2 lg:px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    Alumni
                                </span>
                            @else
                                <span class="px-2 lg:px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                    Alumnus
                                </span>
                            @endif
                        </td>
                        <td class="px-4 lg:px-6 py-3 lg:py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end space-x-1 lg:space-x-2">
                                <a 
                                    href="{{ route('santri.show', $santri->stambuk) }}" 
                                    class="p-1.5 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg transition-colors"
                                    title="Lihat Detail"
                                >
                                    <svg class="w-4 h-4 lg:w-5 lg:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
                                @if(auth()->user() && auth()->user()->canManageSantri($santri))
                                <a 
                                    href="{{ route('santri.edit', $santri->stambuk) }}" 
                                    class="p-1.5 text-amber-600 hover:text-amber-800 hover:bg-amber-50 rounded-lg transition-colors"
                                    title="Edit"
                                >
                                    <svg class="w-4 h-4 lg:w-5 lg:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                                <form 
                                    action="{{ route('santri.destroy', $santri->stambuk) }}" 
                                    method="POST" 
                                    class="inline"
                                    onsubmit="return confirm('Hapus data {{ $santri->nama }}?')"
                                >
                                    @csrf
                                    @method('DELETE')
                                    <button 
                                        type="submit" 
                                        class="p-1.5 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition-colors"
                                        title="Hapus"
                                    >
                                        <svg class="w-4 h-4 lg:w-5 lg:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ auth()->user() && auth()->user()->can('isAdmin') ? '8' : '7' }}" class="px-6 py-10 text-center">
                            <div class="text-gray-500">
                                <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                </svg>
                                <p class="text-sm font-medium">
                                    @if(request('search'))
                                        Tidak ada data yang sesuai dengan "{{ request('search') }}"
                                    @else
                                        Belum ada data santri yang terdaftar
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

    <!-- JavaScript untuk Checkbox dan Floating Action Bar -->
    <script>
        function toggleSelectAll(checkbox) {
            const checkboxes = document.querySelectorAll('.santri-checkbox');
            checkboxes.forEach(cb => {
                cb.checked = checkbox.checked;
            });
            updateFloatingBar();
        }

        function updateFloatingBar() {
            const checkboxes = document.querySelectorAll('.santri-checkbox:checked');
            const floatingBar = document.getElementById('floatingActionBar');
            const selectedCount = document.getElementById('selectedCount');
            const selectAllCheckbox = document.getElementById('selectAll');
            
            if (!floatingBar || !selectedCount) return;
            
            const count = checkboxes.length;
            
            if (count > 0) {
                floatingBar.classList.remove('hidden');
                selectedCount.textContent = count + ' santri dipilih';
            } else {
                floatingBar.classList.add('hidden');
            }

            if (selectAllCheckbox) {
                const allCheckboxes = document.querySelectorAll('.santri-checkbox');
                const allChecked = allCheckboxes.length > 0 && Array.from(allCheckboxes).every(cb => cb.checked);
                selectAllCheckbox.checked = allChecked;
                selectAllCheckbox.indeterminate = count > 0 && count < allCheckboxes.length;
            }
        }

        function clearSelection() {
            const checkboxes = document.querySelectorAll('.santri-checkbox');
            checkboxes.forEach(cb => {
                cb.checked = false;
            });
            const selectAllCheckbox = document.getElementById('selectAll');
            if (selectAllCheckbox) {
                selectAllCheckbox.checked = false;
                selectAllCheckbox.indeterminate = false;
            }
            updateFloatingBar();
        }

        document.getElementById('promoteForm')?.addEventListener('submit', function(e) {
            const checkedBoxes = document.querySelectorAll('.santri-checkbox:checked');
            
            if (checkedBoxes.length === 0) {
                e.preventDefault();
                alert('Pilih minimal satu santri.');
                return false;
            }
            
            const existingInputs = this.querySelectorAll('input[type="hidden"][name="santri_ids[]"]');
            existingInputs.forEach(input => input.remove());
            
            checkedBoxes.forEach(cb => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'santri_ids[]';
                input.value = cb.value;
                this.appendChild(input);
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            updateFloatingBar();
        });
    </script>

    <!-- Modal Import Excel -->
    <div id="importModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-xl max-w-md w-full p-4 sm:p-6 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg sm:text-xl font-bold text-gray-800">Import Data Santri</h3>
                <button 
                    onclick="document.getElementById('importModal').classList.add('hidden')"
                    class="p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="mb-4 p-3 sm:p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <p class="text-xs sm:text-sm text-blue-800 mb-2"><strong>Format Excel:</strong></p>
                <ul class="text-xs text-blue-700 list-disc list-inside space-y-1">
                    <li>Kolom: Stambuk, Nama, Kelas, Daerah, Nama Ayah</li>
                    <li>Baris pertama adalah header</li>
                    <li>Format: .xlsx atau .xls</li>
                </ul>
            </div>

            <form action="{{ route('santri.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label for="file" class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">
                        Pilih File Excel
                    </label>
                    <input 
                        type="file" 
                        id="file" 
                        name="file" 
                        accept=".xlsx,.xls"
                        required
                        class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-primary focus:border-brand-primary transition-colors text-sm"
                    >
                    @error('file')
                        <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end space-x-2 sm:space-x-3">
                    <button 
                        type="button"
                        onclick="document.getElementById('importModal').classList.add('hidden')"
                        class="px-3 sm:px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors text-xs sm:text-sm"
                    >
                        Batal
                    </button>
                    <button 
                        type="submit" 
                        class="px-3 sm:px-4 py-2 bg-brand-primary text-white rounded-lg hover:bg-green-700 transition-colors text-xs sm:text-sm"
                    >
                        Import Data
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
