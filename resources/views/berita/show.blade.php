<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $berita->title }} - IKPM Gontor Pontianak</title>
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="{{ $berita->title }}">
    <meta property="og:description" content="{{ Str::limit($berita->description, 200) }}">
    @if($berita->image)
    <meta property="og:image" content="{{ asset('storage/' . $berita->image) }}">
    @endif
    <meta property="og:type" content="article">
    
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
                        <h1 class="text-emerald-600 font-bold text-lg sm:text-xl leading-tight">IKPM Gontor</h1>
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

    <!-- Article Content -->
    <article class="pt-20 sm:pt-28 pb-12 sm:pb-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumb - Hidden on very small screens -->
            <nav class="mb-6 sm:mb-8 hidden sm:block">
                <ol class="flex items-center flex-wrap gap-1 text-sm text-gray-500">
                    <li><a href="{{ url('/') }}" class="hover:text-emerald-600 transition-colors">Beranda</a></li>
                    <li><svg class="w-4 h-4 mx-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></li>
                    <li><a href="{{ route('berita.index') }}" class="hover:text-emerald-600 transition-colors">Berita</a></li>
                    <li><svg class="w-4 h-4 mx-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></li>
                    <li class="text-gray-800 font-medium truncate max-w-[150px] sm:max-w-[250px]">{{ $berita->title }}</li>
                </ol>
            </nav>

            <!-- Mobile Back Button -->
            <div class="mb-4 sm:hidden">
                <a href="{{ route('berita.index') }}" class="inline-flex items-center text-emerald-600 font-medium text-sm">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
            </div>

            <!-- Article Header -->
            <header class="mb-6 sm:mb-8">
                <div class="flex items-center text-xs sm:text-sm text-gray-500 mb-3 sm:mb-4">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <time datetime="{{ $berita->created_at->toISOString() }}">{{ $berita->created_at->translatedFormat('l, d F Y') }}</time>
                </div>
                <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold text-slate-800 leading-tight">{{ $berita->title }}</h1>
            </header>

            <!-- Featured Image -->
            @if($berita->image)
            <div class="mb-8 sm:mb-10 rounded-xl sm:rounded-2xl overflow-hidden shadow-lg -mx-4 sm:mx-0">
                <img 
                    src="{{ asset('storage/' . $berita->image) }}" 
                    alt="{{ $berita->title }}"
                    class="w-full h-auto max-h-[300px] sm:max-h-[400px] md:max-h-[500px] object-cover"
                >
            </div>
            @endif

            <!-- Article Body -->
            <div class="prose prose-sm sm:prose-lg max-w-none text-slate-700">
                @if($berita->description)
                    @foreach(explode("\n", $berita->description) as $paragraph)
                        @if(trim($paragraph))
                        <p class="text-sm sm:text-base leading-relaxed sm:leading-loose">{{ $paragraph }}</p>
                        @endif
                    @endforeach
                @else
                <p class="text-gray-500 italic">Tidak ada konten tambahan.</p>
                @endif
            </div>

            <!-- Share Buttons -->
            <div class="mt-8 sm:mt-12 pt-6 sm:pt-8 border-t border-gray-200">
                <h3 class="text-sm font-semibold text-gray-500 mb-3 sm:mb-4">Bagikan Berita Ini</h3>
                <div class="flex flex-wrap gap-2 sm:gap-3">
                    <a href="https://wa.me/?text={{ urlencode($berita->title . ' - ' . url()->current()) }}" target="_blank" 
                       class="flex items-center px-3 sm:px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 active:bg-green-700 transition-colors text-xs sm:text-sm">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-1.5 sm:mr-2" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                        </svg>
                        <span class="hidden xs:inline">WhatsApp</span>
                        <span class="xs:hidden">WA</span>
                    </a>
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" 
                       class="flex items-center px-3 sm:px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 active:bg-blue-800 transition-colors text-xs sm:text-sm">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-1.5 sm:mr-2" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                        <span class="hidden xs:inline">Facebook</span>
                        <span class="xs:hidden">FB</span>
                    </a>
                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($berita->title) }}" target="_blank" 
                       class="flex items-center px-3 sm:px-4 py-2 bg-black text-white rounded-lg hover:bg-gray-800 active:bg-gray-900 transition-colors text-xs sm:text-sm">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-1.5 sm:mr-2" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                        </svg>
                        <span class="hidden xs:inline">Twitter</span>
                        <span class="xs:hidden">X</span>
                    </a>
                    <button 
                        x-data="{ copied: false }"
                        @click="navigator.clipboard.writeText('{{ url()->current() }}'); copied = true; setTimeout(() => copied = false, 2000)"
                        class="flex items-center px-3 sm:px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 active:bg-gray-400 transition-colors text-xs sm:text-sm">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-1.5 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                        </svg>
                        <span x-show="!copied">Salin</span>
                        <span x-show="copied" x-cloak class="text-green-600">Tersalin!</span>
                    </button>
                </div>
            </div>

            <!-- Back Button (Desktop) -->
            <div class="mt-8 hidden sm:block">
                <a href="{{ route('berita.index') }}" class="inline-flex items-center text-emerald-600 font-medium hover:text-emerald-700 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali ke Daftar Berita
                </a>
            </div>
        </div>
    </article>

    <!-- Related News -->
    @if($relatedNews->count() > 0)
    <section class="py-10 sm:py-16 bg-white border-t border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-xl sm:text-2xl font-bold text-slate-800 mb-6 sm:mb-8">Berita Lainnya</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 sm:gap-6 lg:gap-8">
                @foreach($relatedNews as $item)
                <article class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg active:scale-[0.98] transition-all group">
                    <a href="{{ route('berita.show', $item->id) }}" class="block">
                        @if($item->image)
                        <div class="relative h-36 sm:h-40 overflow-hidden">
                            <img 
                                src="{{ asset('storage/' . $item->image) }}" 
                                alt="{{ $item->title }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                loading="lazy"
                            >
                        </div>
                        @else
                        <div class="h-36 sm:h-40 bg-gradient-to-br from-emerald-100 to-green-100 flex items-center justify-center">
                            <svg class="w-10 h-10 sm:w-12 sm:h-12 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                            </svg>
                        </div>
                        @endif
                    </a>
                    <div class="p-4 sm:p-5">
                        <div class="text-xs text-gray-500 mb-2">{{ $item->created_at->format('d M Y') }}</div>
                        <a href="{{ route('berita.show', $item->id) }}">
                            <h3 class="font-semibold text-sm sm:text-base text-slate-800 line-clamp-2 group-hover:text-emerald-600 transition-colors">{{ $item->title }}</h3>
                        </a>
                    </div>
                </article>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Footer -->
    @php
        $footerAddress = \App\Models\Setting::get('footer_address', "Sekretariat IKPM Gontor Cabang Pontianak\nJl. Contoh No. 123\nPontianak, Kalimantan Barat");
        $footerWhatsapp = \App\Models\Setting::get('footer_whatsapp', '812-3456-7890');
        $footerEmail = \App\Models\Setting::get('footer_email', 'info@ikpmpontianak.com');
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
                            <h3 class="text-white font-bold">IKPM Gontor</h3>
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
                &copy; {{ date('Y') }} IKPM Gontor Pontianak. All rights reserved.
            </div>
        </div>
    </footer>
</body>
</html>
