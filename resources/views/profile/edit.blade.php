@extends('layouts.app')

@section('title', 'Pengaturan Profil - IKPM Kalbar')
@section('page-title', 'Pengaturan Profil')
@section('page-subtitle', 'Kelola informasi profil Anda')

@section('content')
    <div class="max-w-3xl mx-auto">
        <!-- Flash Messages -->
        @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
            </div>
        </div>
        @endif

        <!-- Card Form Profil -->
        <div class="bg-white rounded-xl shadow-sm border border-green-100 overflow-hidden mb-6">
            <!-- Card Header -->
            <div class="px-6 py-4 border-b border-gray-100 bg-brand-bg">
                <h3 class="text-lg font-semibold text-gray-800">Informasi Profil</h3>
                <p class="text-sm text-gray-600 mt-1">Perbarui nama dan nomor HP Anda</p>
            </div>
            
            <!-- Form Profil -->
            <form action="{{ route('profile.update') }}" method="POST" class="p-6">
                @csrf
                @method('PUT')
                
                <!-- Nama -->
                <div class="mb-6">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        value="{{ old('name', $user->name) }}"
                        required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-200 focus:border-brand-primary transition-colors text-gray-800 placeholder-gray-400"
                        placeholder="Masukkan nama lengkap"
                    >
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Email (Read Only) -->
                <div class="mb-6">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Email
                    </label>
                    <input 
                        type="email" 
                        id="email" 
                        value="{{ $user->email }}"
                        disabled
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-gray-50 text-gray-600 cursor-not-allowed"
                    >
                    <p class="mt-1 text-xs text-gray-500">Email tidak dapat diubah</p>
                </div>
                
                <!-- No HP -->
                <div class="mb-6">
                    <label for="no_hp" class="block text-sm font-medium text-gray-700 mb-2">
                        No HP (WhatsApp)
                    </label>
                    <input 
                        type="text" 
                        id="no_hp" 
                        name="no_hp" 
                        value="{{ old('no_hp', $user->no_hp) }}"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-200 focus:border-brand-primary transition-colors text-gray-800 placeholder-gray-400"
                        placeholder="Contoh: 081234567890"
                    >
                    @error('no_hp')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Role (Read Only) -->
                <div class="mb-6">
                    <label for="role" class="block text-sm font-medium text-gray-700 mb-2">
                        Role
                    </label>
                    <input 
                        type="text" 
                        id="role" 
                        value="{{ $user->role->name ?? '-' }}"
                        disabled
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-gray-50 text-gray-600 cursor-not-allowed"
                    >
                    <p class="mt-1 text-xs text-gray-500">Role tidak dapat diubah</p>
                </div>
                
                <!-- Action Button -->
                <div class="flex items-center justify-end pt-6 border-t border-gray-100">
                    <button 
                        type="submit" 
                        class="px-6 py-2.5 text-sm font-medium text-white bg-brand-primary rounded-lg hover:bg-green-700 transition-colors focus:outline-none focus:ring-2 focus:ring-green-200 focus:ring-offset-2"
                    >
                        Perbarui Profil
                    </button>
                </div>
            </form>
        </div>

        <!-- Card Form Password -->
        <div class="bg-white rounded-xl shadow-sm border border-green-100 overflow-hidden">
            <!-- Card Header -->
            <div class="px-6 py-4 border-b border-gray-100 bg-brand-bg">
                <h3 class="text-lg font-semibold text-gray-800">Ubah Password</h3>
                <p class="text-sm text-gray-600 mt-1">Ganti password akun Anda</p>
            </div>
            
            <!-- Form Password -->
            <form action="{{ route('profile.password.update') }}" method="POST" class="p-6">
                @csrf
                @method('PUT')
                
                <!-- Password Lama -->
                <div class="mb-6">
                    <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">
                        Password Lama <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="password" 
                        id="current_password" 
                        name="current_password" 
                        required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-200 focus:border-brand-primary transition-colors text-gray-800 placeholder-gray-400 @error('current_password') border-red-300 @enderror"
                        placeholder="Masukkan password lama"
                    >
                    @error('current_password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Password Baru -->
                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        Password Baru <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        required
                        minlength="8"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-200 focus:border-brand-primary transition-colors text-gray-800 placeholder-gray-400 @error('password') border-red-300 @enderror"
                        placeholder="Masukkan password baru (min. 8 karakter)"
                    >
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Minimal 8 karakter</p>
                </div>
                
                <!-- Konfirmasi Password -->
                <div class="mb-6">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                        Konfirmasi Password Baru <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="password" 
                        id="password_confirmation" 
                        name="password_confirmation" 
                        required
                        minlength="8"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-200 focus:border-brand-primary transition-colors text-gray-800 placeholder-gray-400"
                        placeholder="Ulangi password baru"
                    >
                </div>
                
                <!-- Action Button -->
                <div class="flex items-center justify-end pt-6 border-t border-gray-100">
                    <button 
                        type="submit" 
                        class="px-6 py-2.5 text-sm font-medium text-white bg-brand-primary rounded-lg hover:bg-green-700 transition-colors focus:outline-none focus:ring-2 focus:ring-green-200 focus:ring-offset-2"
                    >
                        Ubah Password
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
