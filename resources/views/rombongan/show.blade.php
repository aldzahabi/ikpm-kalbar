@extends('layouts.app')

@section('title', 'Detail Rombongan - IKPM Gontor Pontianak')
@section('page-title', 'Detail Rombongan')
@section('page-subtitle', $rombongan->nama)

@section('content')
    <!-- Info Rombongan Card -->
    <div class="bg-white rounded-xl shadow-sm border border-green-100 p-6 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 mb-2">{{ $rombongan->nama }}</h2>
                <div class="flex items-center space-x-3 text-sm text-gray-600">
                    <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-md font-medium">
                        {{ $rombongan->moda_transportasi }}
                    </span>
                    <span class="px-3 py-1 {{ $rombongan->status === 'open' ? 'bg-green-100 text-green-700' : ($rombongan->status === 'departed' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700') }} rounded-md font-medium">
                        {{ ucfirst($rombongan->status) }}
                    </span>
                </div>
            </div>
            <div class="mt-4 md:mt-0 flex items-center space-x-3">
                <a 
                    href="{{ route('rombongan.print-manifest', $rombongan->id) }}" 
                    target="_blank"
                    class="px-4 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors font-medium inline-flex items-center space-x-2"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    <span>Cetak Manifest (PDF)</span>
                </a>
                @can('canManageRombongan')
                <a 
                    href="{{ route('rombongan.edit', $rombongan) }}" 
                    class="px-4 py-2 bg-amber-100 text-amber-700 rounded-lg hover:bg-amber-200 transition-colors font-medium"
                >
                    Edit
                </a>
                @endcan
                <a 
                    href="{{ route('rombongan.index') }}" 
                    class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium"
                >
                    Kembali
                </a>
            </div>
        </div>

        <!-- Info Detail -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div>
                <p class="text-sm text-gray-600 mb-1">Waktu Berangkat</p>
                <p class="text-lg font-semibold text-gray-800">{{ $rombongan->waktu_berangkat->format('d M Y, H:i') }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600 mb-1">Titik Kumpul</p>
                <p class="text-lg font-semibold text-gray-800">{{ $rombongan->titik_kumpul ?? '-' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600 mb-1">Biaya per Orang</p>
                <p class="text-lg font-semibold text-gray-800">Rp {{ number_format($rombongan->biaya_per_orang, 0, ',', '.') }}</p>
            </div>
        </div>

        <!-- Progress Bar -->
        <div>
            <div class="flex items-center justify-between text-sm mb-2">
                <span class="text-gray-700 font-medium">Kapasitas Rombongan</span>
                <span class="text-gray-600 font-semibold">
                    {{ $rombongan->santris->count() }} / {{ $rombongan->kapasitas }} kursi
                </span>
            </div>
            @php
                $persentase = $rombongan->kapasitas > 0 ? ($rombongan->santris->count() / $rombongan->kapasitas) * 100 : 0;
                $colorClass = $persentase >= 100 ? 'bg-red-400' : ($persentase >= 80 ? 'bg-yellow-400' : 'bg-emerald-400');
            @endphp
            <div class="w-full bg-gray-200 rounded-full h-4 overflow-hidden">
                <div 
                    class="h-4 rounded-full transition-all duration-300 {{ $colorClass }}"
                    style="width: {{ min(100, $persentase) }}%"
                ></div>
            </div>
            @if($rombongan->isPenuh())
                <p class="mt-2 text-sm text-red-600 font-medium">⚠️ Rombongan sudah penuh!</p>
            @else
                <p class="mt-2 text-sm text-gray-600">Sisa kursi: {{ $rombongan->sisa_kursi }}</p>
            @endif
        </div>
    </div>

    <!-- 2 Kolom: Daftar Penumpang & Form Tambah -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Kolom Kiri: Daftar Santri yang Sudah Masuk -->
        <div class="bg-white rounded-xl shadow-sm border border-green-100 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Daftar Penumpang</h3>
            
            @if($rombongan->santris->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-brand-bg">
                        <tr>
                            <th class="px-3 py-2 text-left text-xs font-medium text-brand-primary">No</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-brand-primary">Stambuk</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-brand-primary">Nama</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-brand-primary">Kelas</th>
                            <th class="px-3 py-2 text-center text-xs font-medium text-brand-primary">Kursi</th>
                            <th class="px-3 py-2 text-center text-xs font-medium text-brand-primary">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($rombongan->santris as $index => $santri)
                        <tr class="hover:bg-gray-50">
                            <td class="px-3 py-2 text-gray-800">{{ $index + 1 }}</td>
                            <td class="px-3 py-2 font-medium text-gray-800">{{ $santri->stambuk }}</td>
                            <td class="px-3 py-2 text-gray-800">{{ $santri->nama }}</td>
                            <td class="px-3 py-2 text-gray-600">{{ $santri->kelas ?? '-' }}</td>
                            <td class="px-3 py-2 text-center">
                                @if($santri->pivot->nomor_kursi)
                                    <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs">{{ $santri->pivot->nomor_kursi }}</span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-3 py-2 text-center">
                                @can('canManageRombongan')
                                <form 
                                    action="{{ route('rombongan.remove-santri', [$rombongan, $santri->stambuk]) }}" 
                                    method="POST" 
                                    onsubmit="return confirm('Hapus dari rombongan?')"
                                    class="inline"
                                >
                                    @csrf
                                    @method('DELETE')
                                    <button 
                                        type="submit" 
                                        class="text-red-600 hover:text-red-800 transition-colors"
                                        title="Hapus dari Bus"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                                @else
                                <span class="text-xs text-gray-400">-</span>
                                @endcan
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-8 text-gray-500">
                <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <p>Belum ada santri yang terdaftar</p>
            </div>
            @endif
        </div>

        <!-- Kolom Kanan: Form Pencarian & Tambah Santri -->
        @can('canManageRombongan')
        @if($rombongan->status === 'open' && !$rombongan->isPenuh())
        <div class="bg-white rounded-xl shadow-sm border border-green-100 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Tambah Penumpang</h3>
            
            <!-- Form Pencarian -->
            <div class="mb-4">
                <label for="search_santri" class="block text-sm font-medium text-gray-700 mb-2">
                    Cari Santri
                </label>
                <input 
                    type="text" 
                    id="search_santri" 
                    placeholder="Cari berdasarkan nama atau stambuk..."
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-primary focus:border-brand-primary transition-colors"
                    onkeyup="searchSantri(this.value)"
                >
                <div id="search_results" class="mt-2 max-h-60 overflow-y-auto border border-gray-200 rounded-lg hidden"></div>
            </div>

            <!-- Form Tambah Santri -->
            <form action="{{ route('rombongan.add-santri', $rombongan) }}" method="POST" id="addSantriForm" class="space-y-4">
                @csrf
                <div>
                    <label for="santri_stambuk" class="block text-sm font-medium text-gray-700 mb-2">
                        Pilih Santri <span class="text-red-500">*</span>
                    </label>
                    <select 
                        id="santri_stambuk" 
                        name="santri_stambuk" 
                        required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-primary focus:border-brand-primary transition-colors"
                    >
                        <option value="">-- Pilih Santri --</option>
                        @foreach($santriAvailable as $santri)
                            <option value="{{ $santri->stambuk }}">{{ $santri->stambuk }} - {{ $santri->nama }} ({{ $santri->kelas ?? '-' }})</option>
                        @endforeach
                    </select>
                    @if($santriAvailable->isEmpty())
                        <p class="mt-1 text-sm text-gray-500">Tidak ada santri yang tersedia untuk ditambahkan.</p>
                    @endif
                </div>
                <div>
                    <label for="nomor_kursi" class="block text-sm font-medium text-gray-700 mb-2">
                        Nomor Kursi (Opsional)
                    </label>
                    <input 
                        type="text" 
                        id="nomor_kursi" 
                        name="nomor_kursi" 
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-primary focus:border-brand-primary transition-colors"
                        placeholder="Kosongkan jika auto"
                    >
                </div>
                <div>
                    <label for="catatan" class="block text-sm font-medium text-gray-700 mb-2">
                        Catatan (Opsional)
                    </label>
                    <textarea 
                        id="catatan" 
                        name="catatan" 
                        rows="2"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-primary focus:border-brand-primary transition-colors"
                        placeholder="Catatan khusus..."
                    ></textarea>
                </div>
                <button 
                    type="submit" 
                    class="w-full px-4 py-2.5 bg-brand-primary text-white rounded-lg hover:bg-green-700 transition-colors focus:outline-none focus:ring-2 focus:ring-brand-primary focus:ring-offset-2"
                >
                    Tambahkan ke Rombongan
                </button>
            </form>
        </div>
        @else
        <div class="bg-gray-50 rounded-xl border border-gray-200 p-6 flex items-center justify-center">
            <div class="text-center text-gray-500">
                <svg class="w-16 h-16 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
                <p class="font-medium">Tidak dapat menambah penumpang</p>
                <p class="text-sm mt-1">
                    @if($rombongan->status !== 'open')
                        Status rombongan: {{ ucfirst($rombongan->status) }}
                    @elseif($rombongan->isPenuh())
                        Rombongan sudah penuh
                    @endif
                </p>
            </div>
        </div>
        @endif
        @endcan
    </div>

    @push('scripts')
    <script>
        function searchSantri(query) {
            const resultsDiv = document.getElementById('search_results');
            if (query.length < 2) {
                resultsDiv.classList.add('hidden');
                return;
            }

            fetch(`{{ route('rombongan.search-santri', $rombongan) }}?search=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    if (data.length === 0) {
                        resultsDiv.innerHTML = '<div class="p-3 text-sm text-gray-500">Tidak ditemukan</div>';
                        resultsDiv.classList.remove('hidden');
                        return;
                    }

                    let html = '';
                    data.forEach(santri => {
                        html += `
                            <div 
                                class="p-3 hover:bg-gray-50 cursor-pointer border-b border-gray-100 last:border-0"
                                onclick="selectSantri('${santri.stambuk}', '${santri.nama.replace(/'/g, "\\'")}')"
                            >
                                <div class="font-medium text-gray-800">${santri.stambuk} - ${santri.nama}</div>
                                <div class="text-xs text-gray-500">Kelas: ${santri.kelas || '-'}</div>
                            </div>
                        `;
                    });
                    resultsDiv.innerHTML = html;
                    resultsDiv.classList.remove('hidden');
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }

        function selectSantri(stambuk, nama) {
            document.getElementById('santri_stambuk').value = stambuk;
            document.getElementById('search_santri').value = `${stambuk} - ${nama}`;
            document.getElementById('search_results').classList.add('hidden');
        }

        // Hide search results when clicking outside
        document.addEventListener('click', function(event) {
            const searchDiv = document.getElementById('search_results');
            const searchInput = document.getElementById('search_santri');
            if (!searchDiv.contains(event.target) && event.target !== searchInput) {
                searchDiv.classList.add('hidden');
            }
        });
    </script>
    @endpush
@endsection
