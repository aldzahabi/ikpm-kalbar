@extends('layouts.app')

@section('title', 'CMS Landing Page - IKPM Gontor Pontianak')
@section('page-title', 'CMS Landing Page')
@section('page-subtitle', 'Kelola konten halaman depan')

@push('styles')
<style>
    [x-cloak] { display: none !important; }
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
    <div x-data="{ 
        activeTab: '{{ request('tab', 'slider') }}',
        showAddSlider: false,
        showAddNews: false,
        editItem: null
    }">
        <!-- Tab Navigation -->
        <div class="bg-white rounded-xl shadow-sm border border-green-100 mb-4 sm:mb-6">
            <div class="border-b border-gray-200">
                <nav class="flex -mb-px overflow-x-auto scrollbar-hide" style="-webkit-overflow-scrolling: touch;">
                    <button @click="activeTab = 'slider'" 
                            :class="activeTab === 'slider' ? 'border-brand-primary text-brand-primary bg-brand-bg/50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="whitespace-nowrap py-3 sm:py-4 px-4 sm:px-6 border-b-2 font-medium text-xs sm:text-sm transition-colors flex items-center space-x-1.5 sm:space-x-2 flex-shrink-0">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span>Slider</span>
                        <span class="px-1.5 sm:px-2 py-0.5 bg-purple-100 text-purple-700 text-xs rounded-full">{{ $sliders->count() }}</span>
                    </button>
                    <button @click="activeTab = 'news'" 
                            :class="activeTab === 'news' ? 'border-brand-primary text-brand-primary bg-brand-bg/50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="whitespace-nowrap py-3 sm:py-4 px-4 sm:px-6 border-b-2 font-medium text-xs sm:text-sm transition-colors flex items-center space-x-1.5 sm:space-x-2 flex-shrink-0">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                        </svg>
                        <span>Berita</span>
                        <span class="px-1.5 sm:px-2 py-0.5 bg-blue-100 text-blue-700 text-xs rounded-full">{{ $news->count() }}</span>
                    </button>
                    <button @click="activeTab = 'contact'" 
                            :class="activeTab === 'contact' ? 'border-brand-primary text-brand-primary bg-brand-bg/50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="whitespace-nowrap py-3 sm:py-4 px-4 sm:px-6 border-b-2 font-medium text-xs sm:text-sm transition-colors flex items-center space-x-1.5 sm:space-x-2 flex-shrink-0">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                        <span>Kontak</span>
                    </button>
                </nav>
            </div>
        </div>

        <!-- Tab Content: Slider Hero -->
        <div x-show="activeTab === 'slider'" x-cloak>
            <div class="bg-white rounded-xl shadow-sm border border-green-100 p-4 sm:p-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4 mb-4 sm:mb-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base sm:text-lg font-semibold text-gray-800">Slider Hero</h3>
                            <p class="text-xs sm:text-sm text-gray-500 hidden sm:block">Gambar carousel di halaman depan</p>
                        </div>
                    </div>
                    <a href="{{ route('landing-content.create', ['type' => 'slider']) }}" class="inline-flex items-center justify-center px-3 sm:px-4 py-2 bg-brand-primary text-white font-medium rounded-lg hover:bg-green-700 transition-colors text-xs sm:text-sm w-full sm:w-auto">
                        <svg class="w-4 h-4 mr-1.5 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Slider
                    </a>
                </div>

                @if($sliders->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4 lg:gap-6">
                    @foreach($sliders as $slider)
                    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden hover:shadow-lg transition-shadow group">
                        <div class="relative h-32 sm:h-40 overflow-hidden">
                            @if($slider->image)
                            <img src="{{ asset('storage/' . $slider->image) }}" alt="{{ $slider->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            @else
                            <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                <svg class="w-10 h-10 sm:w-12 sm:h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            @endif
                            <div class="absolute top-2 right-2 flex space-x-1">
                                @if($slider->is_active)
                                <span class="px-2 py-0.5 bg-green-500 text-white text-xs rounded-full">Aktif</span>
                                @else
                                <span class="px-2 py-0.5 bg-gray-500 text-white text-xs rounded-full">Non-aktif</span>
                                @endif
                            </div>
                            <div class="absolute top-2 left-2">
                                <span class="px-2 py-0.5 bg-black/50 text-white text-xs rounded-full">#{{ $slider->order }}</span>
                            </div>
                        </div>
                        <div class="p-3 sm:p-4">
                            <h4 class="font-semibold text-sm sm:text-base text-gray-800 mb-1 line-clamp-1">{{ $slider->title }}</h4>
                            @if($slider->description)
                            <p class="text-xs sm:text-sm text-gray-500 line-clamp-2">{{ $slider->description }}</p>
                            @endif
                            <div class="mt-3 sm:mt-4 flex items-center justify-end space-x-2">
                                <a href="{{ route('landing-content.edit', $slider) }}" class="p-1.5 sm:p-2 text-blue-600 hover:bg-blue-100 rounded-lg transition-colors" title="Edit">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                                <form action="{{ route('landing-content.destroy', $slider) }}" method="POST" onsubmit="return confirm('Hapus slider ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 sm:p-2 text-red-600 hover:bg-red-100 rounded-lg transition-colors" title="Hapus">
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-8 sm:py-12">
                    <div class="w-16 h-16 sm:w-20 sm:h-20 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-3 sm:mb-4">
                        <svg class="w-8 h-8 sm:w-10 sm:h-10 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <p class="text-sm text-gray-600 mb-3 sm:mb-4">Belum ada slider ditambahkan</p>
                    <a href="{{ route('landing-content.create', ['type' => 'slider']) }}" class="inline-flex items-center px-4 py-2 bg-brand-primary text-white rounded-lg hover:bg-green-700 transition-colors text-sm">
                        Tambah Slider
                    </a>
                </div>
                @endif

                <!-- Tips -->
                <div class="mt-4 sm:mt-6 p-3 sm:p-4 bg-purple-50 rounded-lg border border-purple-100">
                    <div class="flex items-start space-x-2 sm:space-x-3">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-purple-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div>
                            <h4 class="font-medium text-purple-800 text-xs sm:text-sm">Tips Slider</h4>
                            <p class="text-xs text-purple-700 mt-1">Gunakan gambar minimal 1920x1080px untuk hasil terbaik.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab Content: Berita -->
        <div x-show="activeTab === 'news'" x-cloak>
            <div class="bg-white rounded-xl shadow-sm border border-green-100 p-4 sm:p-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4 mb-4 sm:mb-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base sm:text-lg font-semibold text-gray-800">Berita & Informasi</h3>
                            <p class="text-xs sm:text-sm text-gray-500 hidden sm:block">Artikel di section berita</p>
                        </div>
                    </div>
                    <a href="{{ route('landing-content.create', ['type' => 'news']) }}" class="inline-flex items-center justify-center px-3 sm:px-4 py-2 bg-brand-primary text-white font-medium rounded-lg hover:bg-green-700 transition-colors text-xs sm:text-sm w-full sm:w-auto">
                        <svg class="w-4 h-4 mr-1.5 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Berita
                    </a>
                </div>

                @if($news->count() > 0)
                <!-- Mobile Card View -->
                <div class="block sm:hidden space-y-3">
                    @foreach($news as $item)
                    <div class="bg-white rounded-lg border border-gray-200 p-3">
                        <div class="flex space-x-3">
                            @if($item->image)
                            <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->title }}" class="w-16 h-16 object-cover rounded-lg flex-shrink-0">
                            @else
                            <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <h4 class="font-medium text-sm text-gray-800 line-clamp-1">{{ $item->title }}</h4>
                                <p class="text-xs text-gray-500 mt-0.5">{{ $item->created_at->format('d M Y') }}</p>
                                <div class="flex items-center space-x-2 mt-1">
                                    @if($item->is_active)
                                    <span class="px-1.5 py-0.5 bg-green-100 text-green-700 text-xs rounded">Aktif</span>
                                    @else
                                    <span class="px-1.5 py-0.5 bg-gray-100 text-gray-600 text-xs rounded">Non-aktif</span>
                                    @endif
                                    <span class="text-xs text-gray-400">#{{ $item->order }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center justify-end space-x-2 mt-3 pt-2 border-t border-gray-100">
                            <a href="{{ route('landing-content.edit', $item) }}" class="p-1.5 text-blue-600 hover:bg-blue-100 rounded-lg transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>
                            <form action="{{ route('landing-content.destroy', $item) }}" method="POST" onsubmit="return confirm('Hapus berita ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 text-red-600 hover:bg-red-100 rounded-lg transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Desktop Table View -->
                <div class="hidden sm:block overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Gambar</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Judul</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase hidden lg:table-cell">Tanggal</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Urutan</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Status</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($news as $item)
                            <tr class="hover:bg-brand-bg transition-colors">
                                <td class="px-4 py-3">
                                    @if($item->image)
                                    <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->title }}" class="w-14 h-10 lg:w-16 lg:h-12 object-cover rounded-lg">
                                    @else
                                    <div class="w-14 h-10 lg:w-16 lg:h-12 bg-gray-200 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 lg:w-6 lg:h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <div class="font-medium text-sm text-gray-800 truncate max-w-[200px]">{{ $item->title }}</div>
                                    @if($item->description)
                                    <div class="text-xs text-gray-500 mt-0.5 line-clamp-1">{{ Str::limit($item->description, 50) }}</div>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-xs lg:text-sm text-gray-600 hidden lg:table-cell">
                                    {{ $item->created_at->format('d M Y') }}
                                </td>
                                <td class="px-4 py-3 text-center text-sm text-gray-600">
                                    {{ $item->order }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if($item->is_active)
                                    <span class="px-2 py-0.5 bg-green-100 text-green-700 text-xs rounded-full">Aktif</span>
                                    @else
                                    <span class="px-2 py-0.5 bg-gray-100 text-gray-600 text-xs rounded-full">Non-aktif</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-center space-x-1">
                                        <a href="{{ route('landing-content.edit', $item) }}" class="p-1.5 text-blue-600 hover:bg-blue-100 rounded-lg transition-colors" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        <form action="{{ route('landing-content.destroy', $item) }}" method="POST" onsubmit="return confirm('Hapus berita ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 text-red-600 hover:bg-red-100 rounded-lg transition-colors" title="Hapus">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-8 sm:py-12">
                    <div class="w-16 h-16 sm:w-20 sm:h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3 sm:mb-4">
                        <svg class="w-8 h-8 sm:w-10 sm:h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                        </svg>
                    </div>
                    <p class="text-sm text-gray-600 mb-3 sm:mb-4">Belum ada berita ditambahkan</p>
                    <a href="{{ route('landing-content.create', ['type' => 'news']) }}" class="inline-flex items-center px-4 py-2 bg-brand-primary text-white rounded-lg hover:bg-green-700 transition-colors text-sm">
                        Tambah Berita
                    </a>
                </div>
                @endif
            </div>
        </div>

        <!-- Tab Content: Kontak & Footer -->
        <div x-show="activeTab === 'contact'" x-cloak>
            <div class="bg-white rounded-xl shadow-sm border border-green-100 p-4 sm:p-6">
                <div class="flex items-center space-x-3 mb-4 sm:mb-6">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 bg-emerald-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base sm:text-lg font-semibold text-gray-800">Kontak & Footer</h3>
                        <p class="text-xs sm:text-sm text-gray-500 hidden sm:block">Informasi kontak di footer</p>
                    </div>
                </div>

                <form action="{{ route('landing-content.update.contact') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                        <!-- Alamat Sekretariat -->
                        <div class="sm:col-span-2">
                            <label for="footer_address" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Alamat Sekretariat</label>
                            <textarea name="footer_address" id="footer_address" rows="2"
                                      class="w-full px-3 sm:px-4 py-2 sm:py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-brand-primary transition-colors text-sm"
                                      placeholder="Jl. Contoh No. 123, Pontianak">{{ $contactSettings['footer_address'] ?? '' }}</textarea>
                        </div>

                        <!-- WhatsApp -->
                        <div>
                            <label for="footer_whatsapp" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">WhatsApp</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 text-sm">+62</span>
                                <input type="text" name="footer_whatsapp" id="footer_whatsapp" 
                                       value="{{ $contactSettings['footer_whatsapp'] ?? '' }}"
                                       class="w-full pl-12 pr-3 py-2 sm:py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-brand-primary transition-colors text-sm"
                                       placeholder="812-3456-7890">
                            </div>
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="footer_email" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Email</label>
                            <input type="email" name="footer_email" id="footer_email" 
                                   value="{{ $contactSettings['footer_email'] ?? '' }}"
                                   class="w-full px-3 sm:px-4 py-2 sm:py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-brand-primary transition-colors text-sm"
                                   placeholder="info@ikpmpontianak.com">
                        </div>

                        <!-- Instagram -->
                        <div>
                            <label for="footer_instagram" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Instagram</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 text-sm">@</span>
                                <input type="text" name="footer_instagram" id="footer_instagram" 
                                       value="{{ $contactSettings['footer_instagram'] ?? '' }}"
                                       class="w-full pl-8 pr-3 py-2 sm:py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-brand-primary transition-colors text-sm"
                                       placeholder="ikpmpontianak">
                            </div>
                        </div>

                        <!-- Facebook -->
                        <div>
                            <label for="footer_facebook" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Facebook</label>
                            <input type="text" name="footer_facebook" id="footer_facebook" 
                                   value="{{ $contactSettings['footer_facebook'] ?? '' }}"
                                   class="w-full px-3 sm:px-4 py-2 sm:py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-brand-primary transition-colors text-sm"
                                   placeholder="IKPM Gontor Pontianak">
                        </div>

                        <!-- YouTube -->
                        <div>
                            <label for="footer_youtube" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">YouTube</label>
                            <input type="text" name="footer_youtube" id="footer_youtube" 
                                   value="{{ $contactSettings['footer_youtube'] ?? '' }}"
                                   class="w-full px-3 sm:px-4 py-2 sm:py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-brand-primary transition-colors text-sm"
                                   placeholder="@ikpmpontianak">
                        </div>

                        <!-- Maps Embed URL -->
                        <div>
                            <label for="footer_maps" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Google Maps URL</label>
                            <input type="url" name="footer_maps" id="footer_maps" 
                                   value="{{ $contactSettings['footer_maps'] ?? '' }}"
                                   class="w-full px-3 sm:px-4 py-2 sm:py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-brand-primary transition-colors text-sm"
                                   placeholder="https://www.google.com/maps/embed?...">
                        </div>
                    </div>

                    <div class="mt-4 sm:mt-6 pt-4 sm:pt-6 border-t border-gray-200 flex justify-end">
                        <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center px-4 sm:px-6 py-2 sm:py-2.5 bg-brand-primary text-white font-medium rounded-lg hover:bg-green-700 focus:ring-4 focus:ring-green-200 transition-colors text-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>

            <!-- Preview Footer -->
            <div class="mt-4 sm:mt-6 bg-white rounded-xl shadow-sm border border-green-100 p-4 sm:p-6">
                <h4 class="text-xs sm:text-sm font-semibold text-gray-800 mb-3 sm:mb-4 flex items-center">
                    <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    Preview Footer
                </h4>
                <div class="bg-emerald-900 text-white p-4 sm:p-6 rounded-lg">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6 text-xs sm:text-sm">
                        <div>
                            <h5 class="font-semibold mb-1 sm:mb-2">Alamat</h5>
                            <p class="text-emerald-200">{{ $contactSettings['footer_address'] ?? 'Belum diatur' }}</p>
                        </div>
                        <div>
                            <h5 class="font-semibold mb-1 sm:mb-2">Kontak</h5>
                            <p class="text-emerald-200">WA: +62{{ $contactSettings['footer_whatsapp'] ?? '-' }}</p>
                            <p class="text-emerald-200">Email: {{ $contactSettings['footer_email'] ?? '-' }}</p>
                        </div>
                        <div>
                            <h5 class="font-semibold mb-1 sm:mb-2">Sosial Media</h5>
                            <p class="text-emerald-200">IG: @{{ $contactSettings['footer_instagram'] ?? '-' }}</p>
                            <p class="text-emerald-200">FB: {{ $contactSettings['footer_facebook'] ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
