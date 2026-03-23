<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Berita & Informasi - IKPM Kalbar</title>
    
    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-800">
    <!-- Navbar -->
    <nav x-data="{ mobileMenuOpen: false }" class="fixed top-0 left-0 right-0 z-50 glass-effect shadow-sm border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 sm:h-20">
                <!-- Logo -->
                <a href="{{ url('/') }}" class="flex items-center space-x-2 sm:space-x-3">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-emerald-600 rounded-xl flex items-center justify-center shadow-md flex-shrink-0">
                        <span class="text-white font-bold text-lg sm:text-xl">IK</span>
                    </div>
                    <div class="hidden xs:block">
                        <h1 class="text-emerald-600 font-bold text-lg sm:text-xl leading-tight">IKPM Kalbar</h1>
                        <p class="text-gray-500 text-xs">Pontianak</p>
                    </div>
                </a>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-6 lg:space-x-8">
                    <a href="{{ url('/') }}" class="text-slate-700 hover:text-emerald-600 font-medium transition-colors py-2">Beranda</a>
                    <a href="{{ url('/#tentang') }}" class="text-slate-700 hover:text-emerald-600 font-medium transition-colors py-2">Tentang Kami</a>
                    <a href="{{ route('berita.index') }}" class="text-emerald-600 font-semibold transition-colors py-2 border-b-2 border-emerald-600">Berita</a>
                    <a href="{{ url('/#kontak') }}" class="text-slate-700 hover:text-emerald-600 font-medium transition-colors py-2">Kontak</a>
                </div>

                <!-- Login Button & Mobile Menu Toggle -->
                <div class="flex items-center space-x-2 sm:space-x-4">
                    <a href="{{ route('login') }}" class="hidden md:block px-4 lg:px-6 py-2 sm:py-2.5 bg-emerald-600 text-white rounded-full font-medium hover:bg-emerald-700 transition-all shadow-md hover:shadow-lg text-sm lg:text-base">
                        Login Sistem
                    </a>
                    
                    <!-- Mobile Menu Button -->
                    <button 
                        @click="mobileMenuOpen = !mobileMenuOpen"
                        class="md:hidden p-2 text-slate-700 hover:text-emerald-600 transition-colors rounded-lg hover:bg-gray-100 active:bg-gray-200"
                        aria-label="Toggle menu"
                    >
                        <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <svg x-show="mobileMenuOpen" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Mobile Menu Dropdown -->
            <div 
                x-show="mobileMenuOpen"
                x-cloak
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 -translate-y-2"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 -translate-y-2"
                class="md:hidden pb-4 border-t border-gray-100"
            >
                <div class="flex flex-col space-y-1 pt-4">
                    <a href="{{ url('/') }}" class="text-slate-700 hover:text-emerald-600 hover:bg-emerald-50 font-medium py-3 px-4 rounded-lg transition-colors">Beranda</a>
                    <a href="{{ url('/#tentang') }}" class="text-slate-700 hover:text-emerald-600 hover:bg-emerald-50 font-medium py-3 px-4 rounded-lg transition-colors">Tentang Kami</a>
                    <a href="{{ route('berita.index') }}" class="text-emerald-600 bg-emerald-50 font-semibold py-3 px-4 rounded-lg">Berita</a>
                    <a href="{{ url('/#kontak') }}" class="text-slate-700 hover:text-emerald-600 hover:bg-emerald-50 font-medium py-3 px-4 rounded-lg transition-colors">Kontak</a>
                    <a href="{{ route('login') }}" class="mt-2 px-6 py-3 bg-emerald-600 text-white rounded-full font-medium text-center active:bg-emerald-700">Login Sistem</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Header -->
    <section class="pt-24 sm:pt-28 pb-8 sm:pb-12 bg-gradient-to-br from-emerald-50 to-green-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold text-slate-800 mb-3 sm:mb-4">Berita & Informasi</h1>
                <p class="text-base sm:text-lg text-slate-600 max-w-2xl mx-auto px-4">Informasi terbaru dan berita seputar kegiatan IKPM Kalbar</p>
            </div>
        </div>
    </section>

    <!-- News Grid -->
    <section class="py-8 sm:py-12 lg:py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($news->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 lg:gap-8">
                @foreach($news as $item)
                <article class="bg-white rounded-xl sm:rounded-2xl shadow-md border border-gray-100 overflow-hidden hover:shadow-xl transition-all active:scale-[0.98] group">
                    <a href="{{ route('berita.show', $item->id) }}" class="block">
                        @if($item->image)
                        <div class="relative h-44 sm:h-52 overflow-hidden">
                            <img 
                                src="{{ asset('storage/' . $item->image) }}" 
                                alt="{{ $item->title }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                loading="lazy"
                            >
                        </div>
                        @else
                        <div class="h-44 sm:h-52 bg-gradient-to-br from-emerald-100 to-green-100 flex items-center justify-center">
                            <svg class="w-12 h-12 sm:w-16 sm:h-16 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                            </svg>
                        </div>
                        @endif
                    </a>
                    <div class="p-4 sm:p-6">
                        <div class="flex items-center text-xs text-gray-500 mb-2 sm:mb-3">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span>{{ $item->created_at->format('d M Y') }}</span>
                        </div>
                        <a href="{{ route('berita.show', $item->id) }}">
                            <h2 class="text-lg sm:text-xl font-bold text-slate-800 mb-2 sm:mb-3 line-clamp-2 group-hover:text-emerald-600 transition-colors">{{ $item->title }}</h2>
                        </a>
                        @if($item->description)
                        <p class="text-slate-600 text-sm mb-3 sm:mb-4 line-clamp-2 sm:line-clamp-3">{{ Str::limit($item->description, 120) }}</p>
                        @endif
                        <a href="{{ route('berita.show', $item->id) }}" class="inline-flex items-center text-emerald-600 font-medium hover:text-emerald-700 transition-colors text-sm sm:text-base">
                            Baca Selengkapnya
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </article>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($news->hasPages())
            <div class="mt-8 sm:mt-12 flex justify-center">
                <div class="inline-flex items-center space-x-1 sm:space-x-2">
                    {{ $news->links() }}
                </div>
            </div>
            @endif
            @else
            <div class="text-center py-12 sm:py-16">
                <div class="w-20 h-20 sm:w-24 sm:h-24 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4 sm:mb-6">
                    <svg class="w-10 h-10 sm:w-12 sm:h-12 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                    </svg>
                </div>
                <h3 class="text-xl sm:text-2xl font-bold text-slate-800 mb-2">Belum Ada Berita</h3>
                <p class="text-slate-600 text-sm sm:text-base mb-4 sm:mb-6 px-4">Belum ada berita tersedia saat ini. Silakan kembali lagi nanti.</p>
                <a href="{{ url('/') }}" class="inline-flex items-center px-5 sm:px-6 py-2.5 sm:py-3 bg-emerald-600 text-white rounded-full font-medium hover:bg-emerald-700 active:bg-emerald-800 transition-colors text-sm sm:text-base">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali ke Beranda
                </a>
            </div>
            @endif
        </div>
    </section>

    <!-- Footer -->
    @php
        $footerAddress = \App\Models\Setting::get('footer_address', "Sekretariat IKPM Kalbar\nJl. Contoh No. 123\nPontianak, Kalimantan Barat");
        $footerWhatsapp = \App\Models\Setting::get('footer_whatsapp', '812-3456-7890');
        $footerEmail = \App\Models\Setting::get('footer_email', 'info@ikpmkalbar.com');
    @endphp
    <footer class="bg-[#064e3b] text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-12">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
                <div class="sm:col-span-2 md:col-span-1">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center flex-shrink-0">
                            <span class="text-emerald-600 font-bold">IK</span>
                        </div>
                        <div>
                            <h3 class="text-white font-bold">IKPM Kalbar</h3>
                            <p class="text-emerald-200 text-xs">Pontianak</p>
                        </div>
                    </div>
                    <p class="text-gray-300 text-sm whitespace-pre-line">{{ $footerAddress }}</p>
                </div>
                <div>
                    <h4 class="text-white font-bold mb-4">Tautan Cepat</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ url('/') }}" class="text-gray-300 hover:text-white transition-colors py-1 inline-block">Beranda</a></li>
                        <li><a href="{{ route('berita.index') }}" class="text-gray-300 hover:text-white transition-colors py-1 inline-block">Berita</a></li>
                        <li><a href="{{ route('login') }}" class="text-gray-300 hover:text-white transition-colors py-1 inline-block">Login Sistem</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-bold mb-4">Hubungi Kami</h4>
                    <ul class="space-y-2 text-sm">
                        @if($footerWhatsapp)
                        <li>
                            <a href="https://wa.me/62{{ str_replace(['-', ' '], '', $footerWhatsapp) }}" class="text-gray-300 hover:text-white transition-colors py-1 inline-flex items-center">
                                <svg class="w-4 h-4 mr-2 text-emerald-400" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                +62{{ $footerWhatsapp }}
                            </a>
                        </li>
                        @endif
                        @if($footerEmail)
                        <li>
                            <a href="mailto:{{ $footerEmail }}" class="text-gray-300 hover:text-white transition-colors py-1 inline-flex items-center">
                                <svg class="w-4 h-4 mr-2 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                {{ $footerEmail }}
                            </a>
                        </li>
                        @endif
                    </ul>
                </div>
            </div>
            <div class="border-t border-emerald-800 mt-8 pt-6 sm:pt-8 text-center text-gray-400 text-xs sm:text-sm">
                &copy; {{ date('Y') }} IKPM Kalbar. All rights reserved.
            </div>
        </div>
    </footer>
</body>
</html>
