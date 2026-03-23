@extends('layouts.app')

@section('title', 'Pengaturan - IKPM Kalbar')
@section('page-title', 'Pengaturan')
@section('page-subtitle', 'Konfigurasi sistem dan aplikasi')

@section('content')
    <div x-data="{ 
        activeTab: '{{ request('tab', 'organisasi') }}',
        editAccountId: null,
        editCategoryId: null,
        showAddAccount: false,
        showAddCategory: false
    }">
        <!-- Tab Navigation -->
        <div class="bg-white rounded-xl shadow-sm border border-green-100 mb-6">
            <div class="border-b border-gray-200">
                <nav class="flex -mb-px overflow-x-auto">
                    <button @click="activeTab = 'organisasi'" 
                            :class="activeTab === 'organisasi' ? 'border-brand-primary text-brand-primary bg-brand-bg' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm transition-colors flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <span>Informasi Organisasi</span>
                    </button>
                    <button @click="activeTab = 'akun'" 
                            :class="activeTab === 'akun' ? 'border-brand-primary text-brand-primary bg-brand-bg' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm transition-colors flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                        <span>Akun Keuangan</span>
                    </button>
                    <button @click="activeTab = 'kategori'" 
                            :class="activeTab === 'kategori' ? 'border-brand-primary text-brand-primary bg-brand-bg' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm transition-colors flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                        <span>Kategori Keuangan</span>
                    </button>
                </nav>
            </div>
        </div>

        <!-- Tab Content: Informasi Organisasi -->
        <div x-show="activeTab === 'organisasi'" x-cloak>
            <div class="bg-white rounded-xl shadow-sm border border-green-100 p-6">
                <div class="flex items-center space-x-3 mb-6">
                    <div class="w-10 h-10 bg-brand-bg rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-brand-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">Informasi Organisasi</h3>
                        <p class="text-sm text-gray-500">Kelola identitas dan informasi kontak organisasi</p>
                    </div>
                </div>

                <form action="{{ route('settings.update.organization') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Logo -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Logo Organisasi</label>
                            <div class="flex items-center space-x-6">
                                <div class="w-24 h-24 bg-gray-100 rounded-xl flex items-center justify-center overflow-hidden border-2 border-dashed border-gray-300" id="logo-preview">
                                    @if($orgSettings['org_logo'])
                                        <img src="{{ Storage::url($orgSettings['org_logo']) }}" alt="Logo" class="w-full h-full object-cover">
                                    @else
                                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    @endif
                                </div>
                                <div>
                                    <input type="file" name="org_logo" id="org_logo" accept="image/*" class="hidden" onchange="previewLogo(this)">
                                    <label for="org_logo" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 cursor-pointer transition-colors">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                        </svg>
                                        Upload Logo
                                    </label>
                                    <p class="text-xs text-gray-500 mt-1">PNG, JPG, GIF max 2MB</p>
                                </div>
                            </div>
                        </div>

                        <!-- Nama Organisasi -->
                        <div>
                            <label for="org_name" class="block text-sm font-medium text-gray-700 mb-2">Nama Organisasi <span class="text-red-500">*</span></label>
                            <input type="text" name="org_name" id="org_name" value="{{ old('org_name', $orgSettings['org_name']) }}" required
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-brand-primary transition-colors">
                            @error('org_name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="org_email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <input type="email" name="org_email" id="org_email" value="{{ old('org_email', $orgSettings['org_email']) }}"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-brand-primary transition-colors">
                        </div>

                        <!-- Telepon -->
                        <div>
                            <label for="org_phone" class="block text-sm font-medium text-gray-700 mb-2">Telepon</label>
                            <input type="text" name="org_phone" id="org_phone" value="{{ old('org_phone', $orgSettings['org_phone']) }}"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-brand-primary transition-colors">
                        </div>

                        <!-- Website -->
                        <div>
                            <label for="org_website" class="block text-sm font-medium text-gray-700 mb-2">Website</label>
                            <input type="url" name="org_website" id="org_website" value="{{ old('org_website', $orgSettings['org_website']) }}" placeholder="https://"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-brand-primary transition-colors">
                        </div>

                        <!-- Alamat -->
                        <div class="md:col-span-2">
                            <label for="org_address" class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
                            <textarea name="org_address" id="org_address" rows="2"
                                      class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-brand-primary transition-colors">{{ old('org_address', $orgSettings['org_address']) }}</textarea>
                        </div>

                        <!-- Deskripsi -->
                        <div class="md:col-span-2">
                            <label for="org_description" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Organisasi</label>
                            <textarea name="org_description" id="org_description" rows="3"
                                      class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-brand-primary transition-colors">{{ old('org_description', $orgSettings['org_description']) }}</textarea>
                        </div>
                    </div>

                    <div class="mt-6 pt-6 border-t border-gray-200 flex justify-end">
                        <button type="submit" class="inline-flex items-center px-6 py-2.5 bg-brand-primary text-white font-medium rounded-lg hover:bg-green-700 focus:ring-4 focus:ring-green-200 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tab Content: Akun Keuangan -->
        <div x-show="activeTab === 'akun'" x-cloak>
            <div class="bg-white rounded-xl shadow-sm border border-green-100 p-6">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">Akun Keuangan</h3>
                            <p class="text-sm text-gray-500">Kelola akun kas organisasi</p>
                        </div>
                    </div>
                    <button @click="showAddAccount = true" class="inline-flex items-center px-4 py-2 bg-brand-primary text-white font-medium rounded-lg hover:bg-green-700 transition-colors text-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Akun
                    </button>
                </div>

                <!-- Add Account Form -->
                <div x-show="showAddAccount" x-cloak class="mb-6 p-4 bg-brand-bg rounded-lg border border-green-200">
                    <form action="{{ route('settings.store.account') }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Akun <span class="text-red-500">*</span></label>
                                <input type="text" name="name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-brand-primary text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                                <input type="text" name="description" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-brand-primary text-sm">
                            </div>
                            <div class="flex items-end space-x-2">
                                <button type="submit" class="px-4 py-2 bg-brand-primary text-white rounded-lg hover:bg-green-700 text-sm font-medium">Simpan</button>
                                <button type="button" @click="showAddAccount = false" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 text-sm font-medium">Batal</button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Accounts Table -->
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Nama Akun</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Deskripsi</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-gray-700 uppercase">Saldo</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-gray-700 uppercase">Transaksi</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($financeAccounts as $account)
                            <tr class="hover:bg-brand-bg transition-colors">
                                <td class="px-4 py-3">
                                    <span class="font-medium text-gray-800">{{ $account->name }}</span>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $account->description ?? '-' }}</td>
                                <td class="px-4 py-3 text-right">
                                    <span class="font-semibold {{ $account->current_balance >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                        Rp {{ number_format($account->current_balance, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-right text-sm text-gray-600">
                                    {{ $account->transactions()->count() }} transaksi
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-center space-x-2">
                                        <a href="#" onclick="editAccount({{ $account->id }}, '{{ $account->name }}', '{{ $account->description }}')" class="p-1.5 text-blue-600 hover:bg-blue-100 rounded-lg transition-colors" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        @if($account->transactions()->count() === 0)
                                        <form action="{{ route('settings.destroy.account', $account) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus akun ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 text-red-600 hover:bg-red-100 rounded-lg transition-colors" title="Hapus">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                                    Belum ada akun keuangan. Klik "Tambah Akun" untuk menambahkan.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Tab Content: Kategori Keuangan -->
        <div x-show="activeTab === 'kategori'" x-cloak>
            <div class="bg-white rounded-xl shadow-sm border border-green-100 p-6">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">Kategori Keuangan</h3>
                            <p class="text-sm text-gray-500">Kelola kategori pemasukan dan pengeluaran</p>
                        </div>
                    </div>
                    <button @click="showAddCategory = true" class="inline-flex items-center px-4 py-2 bg-brand-primary text-white font-medium rounded-lg hover:bg-green-700 transition-colors text-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Kategori
                    </button>
                </div>

                <!-- Add Category Form -->
                <div x-show="showAddCategory" x-cloak class="mb-6 p-4 bg-brand-bg rounded-lg border border-green-200">
                    <form action="{{ route('settings.store.category') }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Kategori <span class="text-red-500">*</span></label>
                                <input type="text" name="name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-brand-primary text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tipe <span class="text-red-500">*</span></label>
                                <select name="type" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-brand-primary text-sm">
                                    <option value="income">Pemasukan</option>
                                    <option value="expense">Pengeluaran</option>
                                </select>
                            </div>
                            <div class="flex items-end space-x-2">
                                <button type="submit" class="px-4 py-2 bg-brand-primary text-white rounded-lg hover:bg-green-700 text-sm font-medium">Simpan</button>
                                <button type="button" @click="showAddCategory = false" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 text-sm font-medium">Batal</button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Categories Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Pemasukan -->
                    <div>
                        <div class="flex items-center space-x-2 mb-4">
                            <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                            <h4 class="font-semibold text-gray-800">Pemasukan (Income)</h4>
                        </div>
                        <div class="space-y-2">
                            @forelse($financeCategories->where('type', 'income') as $category)
                            <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg border border-green-100 group">
                                <span class="text-gray-800">{{ $category->name }}</span>
                                <div class="flex items-center space-x-1">
                                    <a href="#" onclick="editCategory({{ $category->id }}, '{{ $category->name }}', 'income')" class="p-1 text-blue-600 hover:bg-blue-100 rounded opacity-0 group-hover:opacity-100 transition-all" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    @if($category->transactions()->count() === 0)
                                    <form action="{{ route('settings.destroy.category', $category) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1 text-red-600 hover:bg-red-100 rounded opacity-0 group-hover:opacity-100 transition-all" title="Hapus">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </div>
                            @empty
                            <p class="text-gray-500 text-sm text-center py-4">Belum ada kategori pemasukan</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Pengeluaran -->
                    <div>
                        <div class="flex items-center space-x-2 mb-4">
                            <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                            <h4 class="font-semibold text-gray-800">Pengeluaran (Expense)</h4>
                        </div>
                        <div class="space-y-2">
                            @forelse($financeCategories->where('type', 'expense') as $category)
                            <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg border border-red-100 group">
                                <span class="text-gray-800">{{ $category->name }}</span>
                                <div class="flex items-center space-x-1">
                                    <a href="#" onclick="editCategory({{ $category->id }}, '{{ $category->name }}', 'expense')" class="p-1 text-blue-600 hover:bg-blue-100 rounded opacity-0 group-hover:opacity-100 transition-all" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    @if($category->transactions()->count() === 0)
                                    <form action="{{ route('settings.destroy.category', $category) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1 text-red-600 hover:bg-red-100 rounded opacity-0 group-hover:opacity-100 transition-all" title="Hapus">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </div>
                            @empty
                            <p class="text-gray-500 text-sm text-center py-4">Belum ada kategori pengeluaran</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Account Modal -->
        <div id="editAccountModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
            <div class="bg-white rounded-xl shadow-xl max-w-md w-full mx-4 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Edit Akun Keuangan</h3>
                <form id="editAccountForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Akun</label>
                            <input type="text" name="name" id="editAccountName" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-brand-primary">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                            <input type="text" name="description" id="editAccountDesc" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-brand-primary">
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" onclick="closeEditAccountModal()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-brand-primary text-white rounded-lg hover:bg-green-700">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Edit Category Modal -->
        <div id="editCategoryModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
            <div class="bg-white rounded-xl shadow-xl max-w-md w-full mx-4 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Edit Kategori Keuangan</h3>
                <form id="editCategoryForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Kategori</label>
                            <input type="text" name="name" id="editCategoryName" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-brand-primary">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tipe</label>
                            <select name="type" id="editCategoryType" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-brand-primary">
                                <option value="income">Pemasukan</option>
                                <option value="expense">Pengeluaran</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" onclick="closeEditCategoryModal()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-brand-primary text-white rounded-lg hover:bg-green-700">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        [x-cloak] { display: none !important; }
    </style>

    <script>
        function previewLogo(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('logo-preview');
                    preview.innerHTML = '<img src="' + e.target.result + '" alt="Preview" class="w-full h-full object-cover">';
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function editAccount(id, name, description) {
            document.getElementById('editAccountForm').action = '/settings/account/' + id;
            document.getElementById('editAccountName').value = name;
            document.getElementById('editAccountDesc').value = description || '';
            document.getElementById('editAccountModal').classList.remove('hidden');
        }

        function closeEditAccountModal() {
            document.getElementById('editAccountModal').classList.add('hidden');
        }

        function editCategory(id, name, type) {
            document.getElementById('editCategoryForm').action = '/settings/category/' + id;
            document.getElementById('editCategoryName').value = name;
            document.getElementById('editCategoryType').value = type;
            document.getElementById('editCategoryModal').classList.remove('hidden');
        }

        function closeEditCategoryModal() {
            document.getElementById('editCategoryModal').classList.add('hidden');
        }

        // Close modal on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeEditAccountModal();
                closeEditCategoryModal();
            }
        });
    </script>
@endsection
