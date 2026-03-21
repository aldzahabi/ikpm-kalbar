@extends('layouts.app')

@section('title', 'Edit Konten Landing Page - IKPM Gontor Pontianak')
@section('page-title', 'Edit Konten Landing Page')
@section('page-subtitle', 'Perbarui konten landing page')

@section('content')
    <div class="max-w-4xl mx-auto">
        <!-- Card Form -->
        <div class="bg-white rounded-xl shadow-sm border border-green-100 overflow-hidden">
            <!-- Card Header -->
            <div class="px-6 py-4 border-b border-gray-100 bg-brand-bg">
                <h3 class="text-lg font-semibold text-gray-800">Data Konten</h3>
                <p class="text-sm text-gray-600 mt-1">Perbarui informasi konten di bawah ini</p>
            </div>
            
            <!-- Form -->
            <form action="{{ route('landing-content.update', $landingContent->id) }}" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf
                @method('PUT')
                
                <!-- Baris 1: Judul & Tipe -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Judul -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                            Judul <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="title" 
                            name="title" 
                            value="{{ old('title', $landingContent->title) }}"
                            required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-200 focus:border-brand-primary transition-colors text-gray-800 placeholder-gray-400"
                            placeholder="Masukkan judul konten"
                        >
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Tipe -->
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                            Tipe <span class="text-red-500">*</span>
                        </label>
                        <select 
                            id="type" 
                            name="type" 
                            required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-200 focus:border-brand-primary transition-colors text-gray-800 bg-white"
                        >
                            <option value="">Pilih Tipe</option>
                            <option value="slider" {{ old('type', $landingContent->type) == 'slider' ? 'selected' : '' }}>Slider</option>
                            <option value="news" {{ old('type', $landingContent->type) == 'news' ? 'selected' : '' }}>News</option>
                        </select>
                        @error('type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <!-- Baris 2: Deskripsi -->
                <div class="mb-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Deskripsi
                    </label>
                    <textarea 
                        id="description" 
                        name="description" 
                        rows="4"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-200 focus:border-brand-primary transition-colors text-gray-800 placeholder-gray-400 resize-none"
                        placeholder="Masukkan deskripsi konten (opsional)"
                    >{{ old('description', $landingContent->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Baris 3: Gambar & Urutan -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Gambar -->
                    <div>
                        <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                            Gambar
                        </label>
                        @if($landingContent->image)
                            <div class="mb-4">
                                <img 
                                    src="{{ asset('storage/' . $landingContent->image) }}" 
                                    alt="{{ $landingContent->title }}"
                                    class="w-full h-48 object-cover rounded-lg border border-gray-300 mb-2"
                                >
                                <p class="text-xs text-gray-500">Gambar saat ini</p>
                            </div>
                        @endif
                        <input 
                            type="file" 
                            id="image" 
                            name="image" 
                            accept="image/jpeg,image/jpg,image/png,image/webp"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-200 focus:border-brand-primary transition-colors text-gray-800 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-brand-primary file:text-white hover:file:bg-green-700"
                            onchange="previewImage(this)"
                        >
                        @error('image')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Kosongkan jika tidak ingin mengubah gambar. Format: JPG/PNG/WEBP, Maks 5MB</p>
                        <div id="imagePreview" class="mt-4 hidden">
                            <img id="previewImg" src="" alt="Preview" class="w-full h-48 object-cover rounded-lg border border-gray-300">
                            <p class="text-xs text-gray-500 mt-2">Preview gambar baru</p>
                        </div>
                    </div>
                    
                    <!-- Urutan & Status -->
                    <div class="space-y-6">
                        <!-- Urutan -->
                        <div>
                            <label for="order" class="block text-sm font-medium text-gray-700 mb-2">
                                Urutan
                            </label>
                            <input 
                                type="number" 
                                id="order" 
                                name="order" 
                                value="{{ old('order', $landingContent->order) }}"
                                min="0"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-200 focus:border-brand-primary transition-colors text-gray-800 placeholder-gray-400"
                                placeholder="0"
                            >
                            @error('order')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Angka lebih kecil = muncul lebih dulu</p>
                        </div>
                        
                        <!-- Status Aktif -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Status
                            </label>
                            <div class="flex items-center space-x-3 mt-2">
                                <label class="flex items-center">
                                    <input 
                                        type="checkbox" 
                                        name="is_active" 
                                        value="1"
                                        {{ old('is_active', $landingContent->is_active) ? 'checked' : '' }}
                                        class="w-4 h-4 text-brand-primary border-gray-300 rounded focus:ring-brand-primary"
                                    >
                                    <span class="ml-2 text-sm text-gray-700">Aktif</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-100">
                    <a 
                        href="{{ route('landing-content.index', ['tab' => $landingContent->type]) }}" 
                        class="px-6 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors"
                    >
                        Batal
                    </a>
                    <button 
                        type="submit" 
                        class="px-6 py-2.5 text-sm font-medium text-white bg-brand-primary rounded-lg hover:bg-green-700 transition-colors focus:outline-none focus:ring-2 focus:ring-green-200 focus:ring-offset-2"
                    >
                        Perbarui Konten
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function previewImage(input) {
            const preview = document.getElementById('imagePreview');
            const previewImg = document.getElementById('previewImg');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    preview.classList.remove('hidden');
                }
                
                reader.readAsDataURL(input.files[0]);
            } else {
                preview.classList.add('hidden');
            }
        }
    </script>
@endsection
