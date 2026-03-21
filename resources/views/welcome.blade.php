<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>IKPM Gontor Pontianak - Ikatan Keluarga Pondok Modern</title>
    
    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-800">
    @php
        // Ambil data sliders dan news dari database
        $sliders = \App\Models\LandingContent::where('type', 'slider')
            ->where('is_active', true)
            ->orderBy('order')
            ->orderBy('created_at', 'desc')
            ->get();
        
        $news = \App\Models\LandingContent::where('type', 'news')
            ->where('is_active', true)
            ->orderBy('order')
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();
        
        // Ambil data kontak dari settings
        $footerAddress = \App\Models\Setting::get('footer_address', "Sekretariat IKPM Gontor Cabang Pontianak\nJl. Contoh No. 123\nPontianak, Kalimantan Barat\nIndonesia");
        $footerWhatsapp = \App\Models\Setting::get('footer_whatsapp', '812-3456-7890');
        $footerEmail = \App\Models\Setting::get('footer_email', 'info@ikpmpontianak.com');
        $footerInstagram = \App\Models\Setting::get('footer_instagram', '');
        $footerFacebook = \App\Models\Setting::get('footer_facebook', '');
    @endphp

    <!-- Navbar (Sticky & Glass Effect) -->
    <nav 
        x-data="{ mobileMenuOpen: false }"
        class="fixed top-0 left-0 right-0 z-50 glass-effect shadow-sm border-b border-gray-100"
    >
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 sm:h-20">
                <!-- Logo -->
                <a href="#beranda" class="flex items-center space-x-2 sm:space-x-3">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-emerald-600 rounded-xl flex items-center justify-center shadow-md flex-shrink-0">
                        <span class="text-white font-bold text-lg sm:text-xl">IK</span>
                    </div>
                    <div class="hidden xs:block">
                        <h1 class="text-emerald-600 font-bold text-lg sm:text-xl leading-tight">IKPM Gontor</h1>
                        <p class="text-gray-500 text-xs">Pontianak</p>
                    </div>
                </a>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-6 lg:space-x-8">
                    <a href="#beranda" class="text-slate-700 hover:text-emerald-600 font-medium transition-colors py-2">Beranda</a>
                    <a href="#tentang" class="text-slate-700 hover:text-emerald-600 font-medium transition-colors py-2">Tentang Kami</a>
                    <a href="{{ route('berita.index') }}" class="text-slate-700 hover:text-emerald-600 font-medium transition-colors py-2">Berita</a>
                    <a href="#kontak" class="text-slate-700 hover:text-emerald-600 font-medium transition-colors py-2">Kontak</a>
                </div>

                <!-- Login Button & Mobile Menu Toggle -->
                <div class="flex items-center space-x-2 sm:space-x-4">
                    <a 
                        href="{{ route('login') }}" 
                        class="hidden md:block px-4 lg:px-6 py-2 sm:py-2.5 bg-emerald-600 text-white rounded-full font-medium hover:bg-emerald-700 transition-all shadow-md hover:shadow-lg text-sm lg:text-base"
                    >
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
                    <a @click="mobileMenuOpen = false" href="#beranda" class="text-slate-700 hover:text-emerald-600 hover:bg-emerald-50 font-medium py-3 px-4 rounded-lg transition-colors">Beranda</a>
                    <a @click="mobileMenuOpen = false" href="#tentang" class="text-slate-700 hover:text-emerald-600 hover:bg-emerald-50 font-medium py-3 px-4 rounded-lg transition-colors">Tentang Kami</a>
                    <a href="{{ route('berita.index') }}" class="text-slate-700 hover:text-emerald-600 hover:bg-emerald-50 font-medium py-3 px-4 rounded-lg transition-colors">Berita</a>
                    <a @click="mobileMenuOpen = false" href="#kontak" class="text-slate-700 hover:text-emerald-600 hover:bg-emerald-50 font-medium py-3 px-4 rounded-lg transition-colors">Kontak</a>
                    <a href="{{ route('login') }}" class="mt-2 px-6 py-3 bg-emerald-600 text-white rounded-full font-medium text-center active:bg-emerald-700">Login Sistem</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section (Carousel Slider) -->
    <section id="beranda" class="pt-16 sm:pt-20">
        @if($sliders->count() > 0)
        <div 
            x-data="{ 
                currentSlide: 0, 
                slides: {{ $sliders->count() }},
                touchStartX: 0,
                touchEndX: 0,
                init() {
                    setInterval(() => {
                        this.currentSlide = (this.currentSlide + 1) % this.slides;
                    }, 5000);
                },
                handleSwipe() {
                    if (this.touchEndX < this.touchStartX - 50) {
                        this.currentSlide = (this.currentSlide + 1) % this.slides;
                    }
                    if (this.touchEndX > this.touchStartX + 50) {
                        this.currentSlide = (this.currentSlide - 1 + this.slides) % this.slides;
                    }
                }
            }"
            @touchstart="touchStartX = $event.changedTouches[0].screenX"
            @touchend="touchEndX = $event.changedTouches[0].screenX; handleSwipe()"
            class="relative h-[400px] sm:h-[500px] md:h-[600px] lg:h-[700px] overflow-hidden"
        >
            @foreach($sliders as $index => $slider)
            <div 
                x-show="currentSlide === {{ $index }}"
                x-transition:enter="transition ease-out duration-500"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-500"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="absolute inset-0"
            >
                <img 
                    src="{{ asset('storage/' . $slider->image) }}" 
                    alt="{{ $slider->title }}"
                    class="w-full h-full object-cover"
                >
                <div class="absolute inset-0 bg-gradient-to-r from-black/60 via-black/40 to-black/60"></div>
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="text-center text-white px-4 sm:px-6 max-w-4xl">
                        <h2 
                            x-show="currentSlide === {{ $index }}"
                            x-transition:enter="transition ease-out duration-700 delay-200"
                            x-transition:enter-start="opacity-0 translate-y-8"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            class="text-2xl sm:text-4xl md:text-6xl lg:text-7xl font-bold mb-3 sm:mb-6 leading-tight"
                        >
                            {{ $slider->title }}
                        </h2>
                        @if($slider->description)
                        <p 
                            x-show="currentSlide === {{ $index }}"
                            x-transition:enter="transition ease-out duration-700 delay-400"
                            x-transition:enter-start="opacity-0 translate-y-8"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            class="text-sm sm:text-lg md:text-xl lg:text-2xl text-gray-100 px-2"
                        >
                            {{ $slider->description }}
                        </p>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach

            <!-- Navigation Dots -->
            @if($sliders->count() > 1)
            <div class="absolute bottom-4 sm:bottom-8 left-1/2 transform -translate-x-1/2 flex space-x-2">
                @foreach($sliders as $index => $slider)
                <button 
                    @click="currentSlide = {{ $index }}"
                    :class="currentSlide === {{ $index }} ? 'bg-white w-6 sm:w-8' : 'bg-white/50 w-2'"
                    class="h-2 rounded-full transition-all duration-300"
                    aria-label="Go to slide {{ $index + 1 }}"
                ></button>
                @endforeach
            </div>
            @endif

            <!-- Swipe Indicator (Mobile) -->
            <div class="absolute bottom-16 left-1/2 transform -translate-x-1/2 sm:hidden">
                <p class="text-white/60 text-xs">Geser untuk slide lainnya</p>
            </div>
        </div>
        @else
        <!-- Default Placeholder Slide -->
        <div class="relative h-[400px] sm:h-[500px] md:h-[600px] lg:h-[700px] bg-gradient-to-br from-emerald-50 to-green-100 flex items-center justify-center">
            <div class="text-center px-4">
                <div class="w-16 h-16 sm:w-24 sm:h-24 bg-emerald-600 rounded-full flex items-center justify-center mx-auto mb-4 sm:mb-6 shadow-lg">
                    <span class="text-white font-bold text-2xl sm:text-4xl">IK</span>
                </div>
                <h2 class="text-2xl sm:text-4xl md:text-6xl font-bold text-emerald-600 mb-2 sm:mb-4">IKPM Gontor Pontianak</h2>
                <p class="text-base sm:text-xl text-slate-600">Ikatan Keluarga Pondok Modern</p>
            </div>
        </div>
        @endif
    </section>

    <!-- Section Nilai Pondok (Static Features) -->
    <section id="tentang" class="py-12 sm:py-16 lg:py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-10 sm:mb-16">
                <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold text-slate-800 mb-3 sm:mb-4">Nilai Pondok</h2>
                <p class="text-base sm:text-lg text-slate-600 max-w-2xl mx-auto px-2">Prinsip-prinsip yang menjadi landasan pendidikan di Pondok Modern Gontor</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 sm:gap-6 lg:gap-8">
                <!-- Ukhuwah Islamiyah -->
                <div class="bg-white rounded-xl sm:rounded-2xl shadow-md border border-gray-100 p-5 sm:p-8 hover:shadow-xl transition-all active:scale-[0.98]">
                    <div class="w-12 h-12 sm:w-16 sm:h-16 bg-emerald-100 rounded-xl flex items-center justify-center mb-4 sm:mb-6">
                        <svg class="w-6 h-6 sm:w-8 sm:h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl sm:text-2xl font-bold text-slate-800 mb-2 sm:mb-3">Ukhuwah Islamiyah</h3>
                    <p class="text-sm sm:text-base text-slate-600 leading-relaxed">Persaudaraan yang dibangun atas dasar keimanan dan ketaqwaan, menciptakan ikatan yang kuat antar sesama muslim.</p>
                </div>

                <!-- Kemandirian -->
                <div class="bg-white rounded-xl sm:rounded-2xl shadow-md border border-gray-100 p-5 sm:p-8 hover:shadow-xl transition-all active:scale-[0.98]">
                    <div class="w-12 h-12 sm:w-16 sm:h-16 bg-emerald-100 rounded-xl flex items-center justify-center mb-4 sm:mb-6">
                        <svg class="w-6 h-6 sm:w-8 sm:h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <h3 class="text-xl sm:text-2xl font-bold text-slate-800 mb-2 sm:mb-3">Kemandirian</h3>
                    <p class="text-sm sm:text-base text-slate-600 leading-relaxed">Mengembangkan kemampuan untuk berdiri sendiri, mandiri dalam berpikir, bertindak, dan mengambil keputusan.</p>
                </div>

                <!-- Kebebasan -->
                <div class="bg-white rounded-xl sm:rounded-2xl shadow-md border border-gray-100 p-5 sm:p-8 hover:shadow-xl transition-all active:scale-[0.98] sm:col-span-2 md:col-span-1">
                    <div class="w-12 h-12 sm:w-16 sm:h-16 bg-emerald-100 rounded-xl flex items-center justify-center mb-4 sm:mb-6">
                        <svg class="w-6 h-6 sm:w-8 sm:h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl sm:text-2xl font-bold text-slate-800 mb-2 sm:mb-3">Kebebasan</h3>
                    <p class="text-sm sm:text-base text-slate-600 leading-relaxed">Kebebasan yang bertanggung jawab dalam mengembangkan potensi diri sesuai dengan nilai-nilai Islam.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Section Berita & Informasi (Dynamic Grid) -->
    <section id="berita" class="py-12 sm:py-16 lg:py-20 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-8 sm:mb-12 lg:mb-16">
                <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold text-slate-800 mb-3 sm:mb-4">Kabar Dari IKPM</h2>
                <p class="text-base sm:text-lg text-slate-600 max-w-2xl mx-auto px-2">Informasi terbaru dan berita seputar kegiatan IKPM Gontor Pontianak</p>
            </div>

            @if($news->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 lg:gap-8">
                @foreach($news as $item)
                <article class="bg-white rounded-xl sm:rounded-2xl shadow-md border border-gray-100 overflow-hidden hover:shadow-xl transition-all active:scale-[0.98] group">
                    <a href="{{ route('berita.show', $item->id) }}" class="block">
                        @if($item->image)
                        <div class="relative h-40 sm:h-48 overflow-hidden">
                            <img 
                                src="{{ asset('storage/' . $item->image) }}" 
                                alt="{{ $item->title }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                loading="lazy"
                            >
                        </div>
                        @else
                        <div class="h-40 sm:h-48 bg-gradient-to-br from-emerald-100 to-green-100 flex items-center justify-center">
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
                            <h3 class="text-lg sm:text-xl font-bold text-slate-800 mb-2 sm:mb-3 line-clamp-2 group-hover:text-emerald-600 transition-colors">{{ $item->title }}</h3>
                        </a>
                        @if($item->description)
                        <p class="text-slate-600 text-sm mb-3 sm:mb-4 line-clamp-2 sm:line-clamp-3">{{ Str::limit($item->description, 100) }}</p>
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
            
            <!-- See All News Button -->
            <div class="text-center mt-8 sm:mt-12">
                <a href="{{ route('berita.index') }}" class="inline-flex items-center px-5 sm:px-6 py-2.5 sm:py-3 bg-emerald-600 text-white rounded-full font-medium hover:bg-emerald-700 active:bg-emerald-800 transition-colors text-sm sm:text-base">
                    Lihat Semua Berita
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </a>
            </div>
            @else
            <div class="text-center py-10 sm:py-12">
                <div class="w-16 h-16 sm:w-20 sm:h-20 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 sm:w-10 sm:h-10 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                    </svg>
                </div>
                <p class="text-slate-600 text-sm sm:text-base">Belum ada berita tersedia saat ini.</p>
            </div>
            @endif
        </div>
    </section>

    <!-- Footer -->
    <footer id="kontak" class="bg-[#064e3b] text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-12 lg:py-16">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8 sm:gap-10 lg:gap-12">
                <!-- Kolom 1: Logo & Alamat -->
                <div class="sm:col-span-2 md:col-span-1">
                    <div class="flex items-center space-x-3 mb-4 sm:mb-6">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-white rounded-xl flex items-center justify-center flex-shrink-0">
                            <span class="text-emerald-600 font-bold text-lg sm:text-xl">IK</span>
                        </div>
                        <div>
                            <h3 class="text-white font-bold text-lg sm:text-xl">IKPM Gontor</h3>
                            <p class="text-emerald-200 text-xs sm:text-sm">Pontianak</p>
                        </div>
                    </div>
                    <p class="text-gray-300 text-sm leading-relaxed whitespace-pre-line">{{ $footerAddress }}</p>
                </div>

                <!-- Kolom 2: Quick Links -->
                <div>
                    <h4 class="text-white font-bold text-base sm:text-lg mb-4 sm:mb-6">Tautan Cepat</h4>
                    <ul class="space-y-2 sm:space-y-3">
                        <li><a href="#beranda" class="text-gray-300 hover:text-white transition-colors text-sm sm:text-base py-1 inline-block">Beranda</a></li>
                        <li><a href="#tentang" class="text-gray-300 hover:text-white transition-colors text-sm sm:text-base py-1 inline-block">Tentang Kami</a></li>
                        <li><a href="{{ route('berita.index') }}" class="text-gray-300 hover:text-white transition-colors text-sm sm:text-base py-1 inline-block">Berita</a></li>
                        <li><a href="#kontak" class="text-gray-300 hover:text-white transition-colors text-sm sm:text-base py-1 inline-block">Kontak</a></li>
                        <li><a href="{{ route('login') }}" class="text-gray-300 hover:text-white transition-colors text-sm sm:text-base py-1 inline-block">Login Sistem</a></li>
                    </ul>
                </div>

                <!-- Kolom 3: Kontak -->
                <div>
                    <h4 class="text-white font-bold text-base sm:text-lg mb-4 sm:mb-6">Hubungi Kami</h4>
                    <ul class="space-y-3 sm:space-y-4">
                        @if($footerWhatsapp)
                        <li>
                            <a href="https://wa.me/62{{ str_replace(['-', ' '], '', $footerWhatsapp) }}" class="flex items-center space-x-3 text-gray-300 hover:text-white transition-colors py-1">
                                <svg class="w-5 h-5 text-emerald-400 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                </svg>
                                <span class="text-sm sm:text-base">+62 {{ $footerWhatsapp }}</span>
                            </a>
                        </li>
                        @endif
                        @if($footerEmail)
                        <li>
                            <a href="mailto:{{ $footerEmail }}" class="flex items-center space-x-3 text-gray-300 hover:text-white transition-colors py-1">
                                <svg class="w-5 h-5 text-emerald-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                <span class="text-sm sm:text-base break-all">{{ $footerEmail }}</span>
                            </a>
                        </li>
                        @endif
                        @if($footerInstagram)
                        <li>
                            <a href="https://instagram.com/{{ $footerInstagram }}" target="_blank" class="flex items-center space-x-3 text-gray-300 hover:text-white transition-colors py-1">
                                <svg class="w-5 h-5 text-emerald-400 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                </svg>
                                <span class="text-sm sm:text-base">@{{ $footerInstagram }}</span>
                            </a>
                        </li>
                        @endif
                    </ul>
                </div>
            </div>

            <!-- Copyright Bar -->
            <div class="border-t border-emerald-800 mt-8 sm:mt-12 pt-6 sm:pt-8">
                <div class="flex flex-col sm:flex-row justify-between items-center text-center sm:text-left">
                    <p class="text-gray-400 text-xs sm:text-sm">
                        &copy; {{ date('Y') }} IKPM Gontor Pontianak. All rights reserved.
                    </p>
                    <div class="flex space-x-4 sm:space-x-6 mt-3 sm:mt-0">
                        <a href="#" class="text-gray-400 hover:text-white transition-colors text-xs sm:text-sm py-1">Privacy Policy</a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors text-xs sm:text-sm py-1">Terms of Service</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
