@extends('layouts.app')

@section('title', 'Pendataan Santri Baru - IKPM Kalbar')
@section('page-title', 'Pendataan Santri Baru')
@section('page-subtitle', 'Formulir pendataan santri baru')

@section('content')
    <div class="max-w-4xl mx-auto">
        <!-- Card Form -->
        <div class="bg-white rounded-xl shadow-sm border border-green-100 overflow-hidden">
            <!-- Card Header -->
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-100 bg-brand-bg">
                <h3 class="text-base sm:text-lg font-semibold text-gray-800">Data Santri</h3>
                <p class="text-xs sm:text-sm text-gray-600 mt-0.5 sm:mt-1">Lengkapi formulir di bawah ini untuk menambahkan data santri baru</p>
            </div>
            
            <!-- Form -->
            <form action="{{ route('santri.store') }}" method="POST" enctype="multipart/form-data" class="p-4 sm:p-6">
                @csrf
                
                <!-- Baris 1: Stambuk & Nama Lengkap -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6 mb-4 sm:mb-6">
                    <!-- Stambuk -->
                    <div>
                        <label for="stambuk" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">
                            Stambuk <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="stambuk" 
                            name="stambuk" 
                            value="{{ old('stambuk') }}"
                            maxlength="5"
                            required
                            class="w-full px-3 sm:px-4 py-2 sm:py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-200 focus:border-brand-primary transition-colors text-gray-800 placeholder-gray-400 text-sm"
                            placeholder="Contoh: 12345"
                        >
                        @error('stambuk')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Maksimal 5 karakter</p>
                    </div>
                    
                    <!-- Nama Lengkap -->
                    <div class="sm:col-span-2">
                        <label for="nama" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="nama" 
                            name="nama" 
                            value="{{ old('nama') }}"
                            required
                            class="w-full px-3 sm:px-4 py-2 sm:py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-200 focus:border-brand-primary transition-colors text-gray-800 placeholder-gray-400 text-sm"
                            placeholder="Masukkan nama lengkap santri"
                        >
                        @error('nama')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Baris Biodata: NIK, Tempat Lahir, Tanggal Lahir, Nama Ayah -->
                <div class="mb-4 sm:mb-6 p-3 sm:p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <h4 class="text-xs sm:text-sm font-semibold text-gray-800 mb-3 sm:mb-4">Data Biodata (Untuk Ticketing)</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                        <!-- NIK -->
                        <div>
                            <label for="nik" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">
                                NIK (16 digit)
                            </label>
                            <input 
                                type="text" 
                                id="nik" 
                                name="nik" 
                                value="{{ old('nik') }}"
                                maxlength="16"
                                pattern="[0-9]{16}"
                                class="w-full px-3 sm:px-4 py-2 sm:py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-200 focus:border-brand-primary transition-colors text-gray-800 placeholder-gray-400 text-sm"
                                placeholder="16 digit NIK"
                            >
                            @error('nik')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nama Ayah -->
                        <div>
                            <label for="nama_ayah" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">
                                Nama Ayah
                            </label>
                            <input 
                                type="text" 
                                id="nama_ayah" 
                                name="nama_ayah" 
                                value="{{ old('nama_ayah') }}"
                                class="w-full px-3 sm:px-4 py-2 sm:py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-200 focus:border-brand-primary transition-colors text-gray-800 placeholder-gray-400 text-sm"
                                placeholder="Nama lengkap ayah"
                            >
                            @error('nama_ayah')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tempat Lahir -->
                        <div>
                            <label for="tempat_lahir" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">
                                Tempat Lahir
                            </label>
                            <input 
                                type="text" 
                                id="tempat_lahir" 
                                name="tempat_lahir" 
                                value="{{ old('tempat_lahir') }}"
                                class="w-full px-3 sm:px-4 py-2 sm:py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-200 focus:border-brand-primary transition-colors text-gray-800 placeholder-gray-400 text-sm"
                                placeholder="Contoh: Pontianak"
                            >
                            @error('tempat_lahir')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tanggal Lahir -->
                        <div>
                            <label for="tanggal_lahir" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">
                                Tanggal Lahir
                            </label>
                            <input 
                                type="date" 
                                id="tanggal_lahir" 
                                name="tanggal_lahir" 
                                value="{{ old('tanggal_lahir') }}"
                                class="w-full px-3 sm:px-4 py-2 sm:py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-200 focus:border-brand-primary transition-colors text-gray-800 text-sm"
                            >
                            @error('tanggal_lahir')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <!-- Baris 2: Provinsi & Daerah -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 mb-4 sm:mb-6">
                    <!-- Provinsi -->
                    <div>
                        <label for="provinsi" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">
                            Provinsi <span class="text-red-500">*</span>
                        </label>
                        <select 
                            id="provinsi" 
                            name="provinsi" 
                            required
                            class="w-full px-3 sm:px-4 py-2 sm:py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-200 focus:border-brand-primary transition-colors text-gray-800 bg-white text-sm"
                        >
                            <option value="">Pilih Provinsi</option>
                            <option value="Kalimantan Barat" {{ old('provinsi') == 'Kalimantan Barat' ? 'selected' : '' }}>Kalimantan Barat</option>
                            <option value="Kalimantan Tengah" {{ old('provinsi') == 'Kalimantan Tengah' ? 'selected' : '' }}>Kalimantan Tengah</option>
                            <option value="Kalimantan Selatan" {{ old('provinsi') == 'Kalimantan Selatan' ? 'selected' : '' }}>Kalimantan Selatan</option>
                            <option value="Kalimantan Timur" {{ old('provinsi') == 'Kalimantan Timur' ? 'selected' : '' }}>Kalimantan Timur</option>
                            <option value="Kalimantan Utara" {{ old('provinsi') == 'Kalimantan Utara' ? 'selected' : '' }}>Kalimantan Utara</option>
                            <option value="Jawa Barat" {{ old('provinsi') == 'Jawa Barat' ? 'selected' : '' }}>Jawa Barat</option>
                            <option value="Jawa Tengah" {{ old('provinsi') == 'Jawa Tengah' ? 'selected' : '' }}>Jawa Tengah</option>
                            <option value="Jawa Timur" {{ old('provinsi') == 'Jawa Timur' ? 'selected' : '' }}>Jawa Timur</option>
                            <option value="Sumatera Barat" {{ old('provinsi') == 'Sumatera Barat' ? 'selected' : '' }}>Sumatera Barat</option>
                            <option value="Sumatera Utara" {{ old('provinsi') == 'Sumatera Utara' ? 'selected' : '' }}>Sumatera Utara</option>
                            <option value="Riau" {{ old('provinsi') == 'Riau' ? 'selected' : '' }}>Riau</option>
                            <option value="Lainnya" {{ old('provinsi') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                        @error('provinsi')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Daerah (Kabupaten/Kota) -->
                    <div>
                        <label for="daerah" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">
                            Kabupaten/Kota <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="daerah" 
                            name="daerah" 
                            value="{{ old('daerah') }}"
                            required
                            class="w-full px-3 sm:px-4 py-2 sm:py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-200 focus:border-brand-primary transition-colors text-gray-800 placeholder-gray-400 text-sm"
                            placeholder="Contoh: Pontianak, Singkawang"
                        >
                        @error('daerah')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <!-- Baris 3: Status, Kelas, Pondok Cabang -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6 mb-4 sm:mb-6">
                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select 
                            id="status" 
                            name="status" 
                            required
                            class="w-full px-3 sm:px-4 py-2 sm:py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-200 focus:border-brand-primary transition-colors text-gray-800 bg-white text-sm"
                        >
                            <option value="">Pilih Status</option>
                            <option value="ustad" {{ old('status') == 'ustad' ? 'selected' : '' }}>Ustad</option>
                            <option value="santri" {{ old('status', 'santri') == 'santri' ? 'selected' : '' }}>Santri</option>
                            <option value="alumni" {{ old('status') == 'alumni' ? 'selected' : '' }}>Alumni</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Kelas -->
                    <div>
                        <label for="kelas" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">
                            Kelas
                        </label>
                        <input 
                            type="text" 
                            id="kelas" 
                            name="kelas" 
                            value="{{ old('kelas') }}"
                            class="w-full px-3 sm:px-4 py-2 sm:py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-200 focus:border-brand-primary transition-colors text-gray-800 placeholder-gray-400 text-sm"
                            placeholder="Contoh: 1, 2, 3Int"
                        >
                        <p id="kelas-ustad-hint" class="mt-1 text-xs text-indigo-600 hidden">Untuk Ustad, angka ini = tahun ke (1, 2, 3…) dan diperbarui otomatis tiap tahun kalender.</p>
                        @error('kelas')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Pondok Cabang -->
                    <div>
                        <label for="pondok_cabang" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">
                            Pondok Cabang
                        </label>
                        <select 
                            id="pondok_cabang" 
                            name="pondok_cabang" 
                            class="w-full px-3 sm:px-4 py-2 sm:py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-200 focus:border-brand-primary transition-colors text-gray-800 bg-white text-sm"
                        >
                            <option value="">Pilih Pondok</option>
                            @foreach($pondokCabangList as $key => $name)
                                <option value="{{ $key }}" {{ old('pondok_cabang') == $key ? 'selected' : '' }}>{{ $key }} - {{ $name }}</option>
                            @endforeach
                        </select>
                        @error('pondok_cabang')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div id="ustad-mulai-wrap" class="hidden mb-4 sm:mb-6 p-3 sm:p-4 bg-indigo-50 border border-indigo-100 rounded-lg">
                    <label for="ustad_mulai_tahun" class="block text-xs sm:text-sm font-medium text-gray-800 mb-1 sm:mb-2">
                        Tahun mulai menjadi Ustad <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="number"
                        id="ustad_mulai_tahun"
                        name="ustad_mulai_tahun"
                        min="1990"
                        max="2100"
                        value="{{ old('ustad_mulai_tahun', date('Y')) }}"
                        class="w-full max-w-xs px-3 sm:px-4 py-2 sm:py-2.5 border border-indigo-200 rounded-lg focus:ring-2 focus:ring-indigo-200 text-gray-800 text-sm"
                    >
                    <p class="mt-2 text-xs text-gray-600">
                        Tahun ustad saat ini: <strong id="ustad-tahun-ke-preview">1</strong>
                        (otomatis naik setiap pergantian tahun sampai status diubah ke Alumni).
                    </p>
                    @error('ustad_mulai_tahun')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror

                    <!-- Info: User akan dibuat otomatis -->
                    <div class="mt-3 p-3 bg-blue-100 border border-blue-300 rounded-lg">
                        <div class="flex items-start space-x-2">
                            <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div>
                                <p class="text-xs font-medium text-blue-800">Akun User Akan Dibuat Otomatis</p>
                                <p class="text-xs text-blue-700 mt-1">
                                    Ketika santri dengan status <strong>Ustad</strong> disimpan:
                                </p>
                                <ul class="text-xs text-blue-700 mt-1 list-disc list-inside space-y-0.5">
                                    <li>Email: <code class="bg-blue-200 px-1 rounded">[stambuk]@ikpm.local</code></li>
                                    <li>Password default: <code class="bg-blue-200 px-1 rounded">[stambuk]</code> (sama dengan nomor stambuk)</li>
                                    <li>Role: Ustad (hanya bisa manage santri di pondok cabang yang sama)</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Baris 4: Alamat (Textarea Full Width) -->
                <div class="mb-4 sm:mb-6">
                    <label for="alamat" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">
                        Alamat Lengkap
                    </label>
                    <textarea 
                        id="alamat" 
                        name="alamat" 
                        rows="3"
                        class="w-full px-3 sm:px-4 py-2 sm:py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-200 focus:border-brand-primary transition-colors text-gray-800 placeholder-gray-400 resize-none text-sm"
                        placeholder="Masukkan alamat lengkap santri (opsional)"
                    >{{ old('alamat') }}</textarea>
                    @error('alamat')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Kenaikan Kelas -->
                <div class="mb-4 sm:mb-6">
                    <label for="kenaikan_kelas" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">
                        Kenaikan Kelas
                    </label>
                    <select 
                        id="kenaikan_kelas" 
                        name="kenaikan_kelas" 
                        class="w-full px-3 sm:px-4 py-2 sm:py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-200 focus:border-brand-primary transition-colors text-gray-800 bg-white text-sm"
                    >
                        <option value="">Pilih Status Kenaikan</option>
                        <option value="baru" {{ old('kenaikan_kelas') == 'baru' ? 'selected' : '' }}>Baru</option>
                        <option value="naik" {{ old('kenaikan_kelas') == 'naik' ? 'selected' : '' }}>Naik</option>
                        <option value="tidak_naik" {{ old('kenaikan_kelas') == 'tidak_naik' ? 'selected' : '' }}>Tidak Naik</option>
                        <option value="lulus" {{ old('kenaikan_kelas') == 'lulus' ? 'selected' : '' }}>Lulus</option>
                    </select>
                    @error('kenaikan_kelas')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Section Upload Foto -->
                <div class="mb-4 sm:mb-6 p-3 sm:p-4 bg-purple-50 border border-purple-200 rounded-lg">
                    <h4 class="text-xs sm:text-sm font-semibold text-gray-800 mb-3 sm:mb-4 flex items-center">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Upload Dokumen
                    </h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                        <!-- Foto Diri -->
                        <div>
                            <label for="foto_diri" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">
                                Foto Diri
                            </label>
                            <div class="relative">
                                <input 
                                    type="file" 
                                    id="foto_diri" 
                                    name="foto_diri" 
                                    accept="image/jpeg,image/jpg,image/png,image/webp"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-200 focus:border-brand-primary transition-colors text-gray-800 text-sm file:mr-3 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:bg-purple-100 file:text-purple-700 hover:file:bg-purple-200"
                                    onchange="previewImage(this, 'preview_foto_diri')"
                                >
                            </div>
                            <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG, WEBP. Maks 2MB</p>
                            @error('foto_diri')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                            <div id="preview_foto_diri" class="mt-2 hidden">
                                <img src="" alt="Preview" class="w-24 h-24 object-cover rounded-lg border">
                            </div>
                        </div>

                        <!-- Foto KK -->
                        <div>
                            <label for="foto_kk" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">
                                Foto Kartu Keluarga (KK)
                            </label>
                            <div class="relative">
                                <input 
                                    type="file" 
                                    id="foto_kk" 
                                    name="foto_kk" 
                                    accept="image/jpeg,image/jpg,image/png,image/webp"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-200 focus:border-brand-primary transition-colors text-gray-800 text-sm file:mr-3 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:bg-purple-100 file:text-purple-700 hover:file:bg-purple-200"
                                    onchange="previewImage(this, 'preview_foto_kk')"
                                >
                            </div>
                            <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG, WEBP. Maks 2MB</p>
                            @error('foto_kk')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                            <div id="preview_foto_kk" class="mt-2 hidden">
                                <img src="" alt="Preview" class="w-32 h-20 object-cover rounded-lg border">
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex flex-col-reverse sm:flex-row items-stretch sm:items-center justify-end gap-2 sm:gap-4 pt-4 sm:pt-6 border-t border-gray-100">
                    <a 
                        href="{{ route('santri.index') }}" 
                        class="px-4 sm:px-6 py-2 sm:py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors text-center"
                    >
                        Batal
                    </a>
                    <button 
                        type="submit" 
                        class="px-4 sm:px-6 py-2 sm:py-2.5 text-sm font-medium text-white bg-brand-primary rounded-lg hover:bg-green-700 transition-colors focus:outline-none focus:ring-2 focus:ring-green-200 focus:ring-offset-2"
                    >
                        Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        (function () {
            const statusEl = document.getElementById('status');
            const kelasEl = document.getElementById('kelas');
            const wrap = document.getElementById('ustad-mulai-wrap');
            const mulaiEl = document.getElementById('ustad_mulai_tahun');
            const preview = document.getElementById('ustad-tahun-ke-preview');
            const hint = document.getElementById('kelas-ustad-hint');

            function tahunKe() {
                const y = new Date().getFullYear();
                const mulai = parseInt(mulaiEl.value, 10);
                if (!mulai || mulai < 1990) return 1;
                return Math.max(1, y - mulai + 1);
            }

            function syncUstadUi() {
                const isUstad = statusEl.value === 'ustad';
                wrap.classList.toggle('hidden', !isUstad);
                hint.classList.toggle('hidden', !isUstad);
                if (mulaiEl) {
                    mulaiEl.disabled = !isUstad;
                    mulaiEl.required = isUstad;
                }
                if (isUstad) {
                    kelasEl.readOnly = true;
                    kelasEl.classList.add('bg-gray-50', 'cursor-not-allowed');
                    const ke = tahunKe();
                    if (preview) preview.textContent = String(ke);
                    kelasEl.value = String(ke);
                } else {
                    kelasEl.readOnly = false;
                    kelasEl.classList.remove('bg-gray-50', 'cursor-not-allowed');
                }
            }

            statusEl.addEventListener('change', syncUstadUi);
            if (mulaiEl) mulaiEl.addEventListener('input', syncUstadUi);
            syncUstadUi();
        })();

        function previewImage(input, previewId) {
            const preview = document.getElementById(previewId);
            const previewImg = preview.querySelector('img');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    preview.classList.remove('hidden');
                };
                reader.readAsDataURL(input.files[0]);
            } else {
                preview.classList.add('hidden');
            }
        }
    </script>
@endsection
