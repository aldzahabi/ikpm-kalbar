@extends('layouts.app')

@section('title', 'Tambah Rombongan - IKPM Kalbar')
@section('page-title', 'Tambah Rombongan')
@section('page-subtitle', 'Buat rombongan perpulangan baru')

@section('content')
    <div class="bg-white rounded-xl shadow-sm border border-green-100 p-6 md:p-8">
        <form action="{{ route('rombongan.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Nama Rombongan -->
            <div>
                <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">
                    Nama Rombongan <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    id="nama" 
                    name="nama" 
                    value="{{ old('nama') }}"
                    required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-primary focus:border-brand-primary transition-colors text-gray-800"
                    placeholder="Contoh: Bus 1 - Pontianak"
                >
                @error('nama')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Grid: Moda Transportasi & Kapasitas -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Moda Transportasi -->
                <div>
                    <label for="moda_transportasi" class="block text-sm font-medium text-gray-700 mb-2">
                        Moda Transportasi <span class="text-red-500">*</span>
                    </label>
                    <select 
                        id="moda_transportasi" 
                        name="moda_transportasi" 
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-primary focus:border-brand-primary transition-colors text-gray-800"
                    >
                        <option value="">Pilih Moda</option>
                        <option value="Bus" {{ old('moda_transportasi') === 'Bus' ? 'selected' : '' }}>Bus</option>
                        <option value="Pesawat" {{ old('moda_transportasi') === 'Pesawat' ? 'selected' : '' }}>Pesawat</option>
                        <option value="Kapal" {{ old('moda_transportasi') === 'Kapal' ? 'selected' : '' }}>Kapal</option>
                    </select>
                    @error('moda_transportasi')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kapasitas -->
                <div>
                    <label for="kapasitas" class="block text-sm font-medium text-gray-700 mb-2">
                        Kapasitas (Kursi) <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="number" 
                        id="kapasitas" 
                        name="kapasitas" 
                        value="{{ old('kapasitas') }}"
                        required
                        min="1"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-primary focus:border-brand-primary transition-colors text-gray-800"
                        placeholder="40"
                    >
                    @error('kapasitas')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Grid: Waktu Berangkat & Titik Kumpul -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Waktu Berangkat -->
                <div>
                    <label for="waktu_berangkat" class="block text-sm font-medium text-gray-700 mb-2">
                        Waktu Berangkat <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="datetime-local" 
                        id="waktu_berangkat" 
                        name="waktu_berangkat" 
                        value="{{ old('waktu_berangkat') }}"
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-primary focus:border-brand-primary transition-colors text-gray-800"
                    >
                    @error('waktu_berangkat')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Titik Kumpul -->
                <div>
                    <label for="titik_kumpul" class="block text-sm font-medium text-gray-700 mb-2">
                        Titik Kumpul
                    </label>
                    <input 
                        type="text" 
                        id="titik_kumpul" 
                        name="titik_kumpul" 
                        value="{{ old('titik_kumpul') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-primary focus:border-brand-primary transition-colors text-gray-800"
                        placeholder="Contoh: Terminal Bus Pontianak"
                    >
                    @error('titik_kumpul')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Grid: Biaya per Orang & Status -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Biaya per Orang -->
                <div>
                    <label for="biaya_per_orang" class="block text-sm font-medium text-gray-700 mb-2">
                        Biaya per Orang (Rp) <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="number" 
                        id="biaya_per_orang" 
                        name="biaya_per_orang" 
                        value="{{ old('biaya_per_orang') }}"
                        required
                        min="0"
                        step="0.01"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-primary focus:border-brand-primary transition-colors text-gray-800"
                        placeholder="150000"
                    >
                    @error('biaya_per_orang')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select 
                        id="status" 
                        name="status" 
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-primary focus:border-brand-primary transition-colors text-gray-800"
                    >
                        <option value="open" {{ old('status', 'open') === 'open' ? 'selected' : '' }}>Open</option>
                        <option value="closed" {{ old('status') === 'closed' ? 'selected' : '' }}>Closed</option>
                        <option value="departed" {{ old('status') === 'departed' ? 'selected' : '' }}>Departed</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end space-x-4 pt-4 border-t border-gray-100">
                <a 
                    href="{{ route('rombongan.index') }}" 
                    class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors"
                >
                    Batal
                </a>
                <button 
                    type="submit" 
                    class="px-6 py-2.5 bg-brand-primary text-white rounded-lg hover:bg-green-700 transition-colors focus:outline-none focus:ring-2 focus:ring-brand-primary focus:ring-offset-2"
                >
                    Simpan Rombongan
                </button>
            </div>
        </form>
    </div>
@endsection
