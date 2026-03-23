@extends('layouts.app')

@section('title', 'Edit User - IKPM Kalbar')
@section('page-title', 'Edit User')
@section('page-subtitle', 'Perbarui data user')

@php
    $ustadRole = $roles->firstWhere('slug', 'ustad');
@endphp

@section('content')
    <div class="max-w-4xl mx-auto">
        <!-- Card Form -->
        <div class="bg-white rounded-xl shadow-sm border border-green-100 overflow-hidden">
            <!-- Card Header -->
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-100 bg-brand-bg">
                <h3 class="text-base sm:text-lg font-semibold text-gray-800">Data User</h3>
                <p class="text-xs sm:text-sm text-gray-600 mt-0.5 sm:mt-1">Perbarui informasi user di bawah ini</p>
            </div>
            
            <!-- Form -->
            <form action="{{ route('users.update', $user->id) }}" method="POST" class="p-4 sm:p-6" x-data="{ 
                selectedRole: '{{ old('role_id', $user->role_id) }}',
                ustadRoleId: '{{ $ustadRole ? $ustadRole->id : '' }}'
            }">
                @csrf
                @method('PUT')
                
                <!-- Baris 1: Nama & Email -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 mb-4 sm:mb-6">
                    <!-- Nama -->
                    <div>
                        <label for="name" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="name" 
                            name="name" 
                            value="{{ old('name', $user->name) }}"
                            required
                            class="w-full px-3 sm:px-4 py-2 sm:py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-200 focus:border-brand-primary transition-colors text-gray-800 placeholder-gray-400 text-sm"
                            placeholder="Masukkan nama lengkap"
                        >
                        @error('name')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            value="{{ old('email', $user->email) }}"
                            required
                            class="w-full px-3 sm:px-4 py-2 sm:py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-200 focus:border-brand-primary transition-colors text-gray-800 placeholder-gray-400 text-sm"
                            placeholder="contoh@email.com"
                        >
                        @error('email')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <!-- Baris 2: Password & Role -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 mb-4 sm:mb-6">
                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">
                            Password
                        </label>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            minlength="8"
                            class="w-full px-3 sm:px-4 py-2 sm:py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-200 focus:border-brand-primary transition-colors text-gray-800 placeholder-gray-400 text-sm"
                            placeholder="Kosongkan jika tidak ingin mengubah"
                        >
                        @error('password')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Kosongkan jika tidak ingin mengubah password</p>
                    </div>
                    
                    <!-- Role -->
                    <div>
                        <label for="role_id" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">
                            Role <span class="text-red-500">*</span>
                        </label>
                        <select 
                            id="role_id" 
                            name="role_id" 
                            required
                            x-model="selectedRole"
                            class="w-full px-3 sm:px-4 py-2 sm:py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-200 focus:border-brand-primary transition-colors text-gray-800 bg-white text-sm"
                        >
                            <option value="">Pilih Role</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('role_id')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Pondok Cabang Section - Only for Ustad -->
                <div x-show="selectedRole == ustadRoleId" x-transition class="mb-4 sm:mb-6 p-3 sm:p-4 bg-purple-50 border border-purple-200 rounded-lg">
                    <h4 class="text-xs sm:text-sm font-semibold text-gray-800 mb-3 flex items-center">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        Akses Pondok Cabang
                    </h4>
                    <p class="text-xs text-gray-600 mb-3">Pilih pondok cabang yang dapat dikelola oleh ustad ini:</p>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2 max-h-64 overflow-y-auto">
                        @foreach($pondokCabangList as $key => $name)
                            <label class="flex items-start p-2 bg-white rounded-lg border border-gray-200 hover:border-purple-300 cursor-pointer transition-colors">
                                <input 
                                    type="checkbox" 
                                    name="pondok_cabang[]" 
                                    value="{{ $key }}"
                                    {{ in_array($key, old('pondok_cabang', $userPondokCabang ?? [])) ? 'checked' : '' }}
                                    class="w-4 h-4 mt-0.5 text-purple-600 border-gray-300 rounded focus:ring-purple-500"
                                >
                                <span class="ml-2 text-xs text-gray-700">
                                    <span class="font-semibold text-purple-700">Gontor {{ $key }}</span><br>
                                    <span class="text-gray-500">{{ Str::limit($name, 30) }}</span>
                                </span>
                            </label>
                        @endforeach
                    </div>
                    @error('pondok_cabang')
                        <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Baris 3: No HP & Status Aktif -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 mb-4 sm:mb-6">
                    <!-- No HP -->
                    <div>
                        <label for="no_hp" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">
                            No HP (WhatsApp)
                        </label>
                        <input 
                            type="text" 
                            id="no_hp" 
                            name="no_hp" 
                            value="{{ old('no_hp', $user->no_hp) }}"
                            class="w-full px-3 sm:px-4 py-2 sm:py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-200 focus:border-brand-primary transition-colors text-gray-800 placeholder-gray-400 text-sm"
                            placeholder="Contoh: 081234567890"
                        >
                        @error('no_hp')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Status Aktif -->
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">
                            Status
                        </label>
                        <div class="flex items-center space-x-3 mt-2">
                            <label class="flex items-center">
                                <input 
                                    type="checkbox" 
                                    name="is_active" 
                                    value="1"
                                    {{ old('is_active', $user->is_active) ? 'checked' : '' }}
                                    class="w-4 h-4 text-brand-primary border-gray-300 rounded focus:ring-brand-primary"
                                >
                                <span class="ml-2 text-sm text-gray-700">Aktif</span>
                            </label>
                        </div>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex flex-col-reverse sm:flex-row items-stretch sm:items-center justify-end gap-2 sm:gap-4 pt-4 sm:pt-6 border-t border-gray-100">
                    <a 
                        href="{{ route('users.index') }}" 
                        class="px-4 sm:px-6 py-2 sm:py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors text-center"
                    >
                        Batal
                    </a>
                    <button 
                        type="submit" 
                        class="px-4 sm:px-6 py-2 sm:py-2.5 text-sm font-medium text-white bg-brand-primary rounded-lg hover:bg-green-700 transition-colors focus:outline-none focus:ring-2 focus:ring-green-200 focus:ring-offset-2"
                    >
                        Perbarui User
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
