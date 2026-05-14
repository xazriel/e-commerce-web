<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Farhana Web - Exclusive Moslem Wear</title>
    
    <link rel="icon" type="image/svg+xml" href="{{ asset('farhana.svg') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; }

        .nav-link {
            font-size: 0.7rem;
            letter-spacing: 0.2em;
            transition: all 0.3s ease;
        }

        /* ── MEGA MENU FIX ──
           Dulu posisinya absolute tanpa top, jadi overlap ke atas navbar/logo.
           Sekarang pakai top: 100% supaya muncul tepat di bawah navbar. */
        .mega-menu {
            display: none;
            position: absolute;
            top: 100%;          /* ← FIX: mulai dari bawah navbar */
            left: 0;
            width: 100%;
            background: white;
            z-index: 50;
            border-bottom: 1px solid #f3f4f6;
        }
        .group:hover .mega-menu { display: block; }

        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-10px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-down { animation: fadeInDown 0.4s ease-out; }

        .heroSwiper { height: 90vh; }
        .swiper-slide { overflow: hidden; }
        .swiper-slide img {
            width: 100%; height: 100%;
            object-fit: cover;
            transition: transform 8s ease-out;
        }
        .swiper-slide-active img { transform: scale(1.1); }
        .swiper-pagination-bullet-active { background: #fff !important; }

        .category-btn.active {
            font-weight: 700;
            border-bottom: 1px solid black;
            color: black;
        }

        #mobile-menu {
            transition: all 0.3s ease-in-out;
            transform: translateX(-100%);
        }
        #mobile-menu.open { transform: translateX(0); }

        /* SEARCH BAR STYLING */
        #search-container {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            background-color: white;
            position: relative;
        }
        #search-container.active {
            max-height: 100vh;
            border-bottom: 1px solid #f3f4f6;
        }

        /* Suggestion Overlay */
        #search-results-overlay {
            display: none;
            background: white;
            width: 100%;
            max-height: 70vh;
            overflow-y: auto;
            border-top: 1px solid #f3f4f6;
        }
        #search-results-overlay.show { display: block; }

        @media (min-width: 768px) { .mobile-only { display: none !important; } }
        @media (max-width: 767px) { .desktop-only { display: none !important; } }
    </style>
</head>
<body class="antialiased bg-white text-gray-900" x-data="{ openModal: null }">

    {{-- Global Modal --}}
    <template x-if="openModal">
        <div class="fixed inset-0 z-[400] flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
             @click.self="openModal = null">
            <div class="bg-white w-full max-w-[900px] max-h-[85vh] overflow-y-auto rounded-[2rem] shadow-2xl relative text-gray-800"
                 style="scrollbar-width: none;">
                <button @click="openModal = null"
                        class="absolute top-8 right-8 text-gray-400 hover:text-black z-50 bg-white/80 backdrop-blur rounded-full p-2 transition-all">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
                <div class="prose prose-sm max-w-none">
                    <template x-if="openModal === 'contact'"><div>@include('includes.contact')</div></template>
                    <template x-if="openModal === 'shipping'"><div>@include('includes.shipping')</div></template>
                    <template x-if="openModal === 'howtobuy'"><div>@include('includes.how-to-buy')</div></template>
                    <template x-if="openModal === 'faqs'"><div>@include('includes.faqs')</div></template>
                </div>
            </div>
        </div>
    </template>

    {{-- ══ NAVBAR ══ --}}
    {{-- Tambahkan relative di sini supaya mega-menu (position: absolute; top: 100%) 
         ngikut posisi navbar, bukan viewport --}}
    <header class="sticky top-0 z-50 relative">
        <nav class="bg-white border-b border-gray-100">
            <div class="max-w-7xl mx-auto px-6 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-20">
                    <div class="flex items-center">
                        <div class="flex items-center md:hidden mr-2 ml-2 relative z-20">
                            <button id="mobile-menu-button" class="text-gray-600 hover:text-black focus:outline-none p-2">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16"/>
                                </svg>
                            </button>
                        </div>
                        <div class="shrink-0 flex items-center relative">
                            <a href="{{ route('home') }}">
                                <img src="{{ Storage::url('LOGO-FARHANA-NEW-TRANSPARENT.png') }}"
                                     alt="Farhana"
                                     class="h-14 md:h-20 w-auto object-contain">
                            </a>
                        </div>
                    </div>

                    <div class="hidden md:flex space-x-10 items-center">
                        {{-- FIX: Shop All sekarang scroll ke #koleksi, sama seperti tombol "Shop Now" --}}
                        <a href="#koleksi" class="nav-link font-bold hover:text-gray-400 uppercase text-[11px] tracking-widest">Shop All</a>
                        <div class="group static">
                            <button class="nav-link font-bold hover:text-gray-400 flex items-center uppercase text-[11px] tracking-widest">
                                Collections
                                <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div class="mega-menu pt-10 pb-12 shadow-2xl animate-fade-in-down">
                                <div class="max-w-7xl mx-auto px-8 grid grid-cols-4 gap-12">
                                    @foreach($categories as $cat)
                                    <div>
                                        <h4 class="text-[11px] font-black tracking-widest text-gray-900 mb-5 uppercase border-b pb-2">{{ $cat->name }}</h4>
                                        <ul class="space-y-3">
                                            <li>
                                                <button type="button" onclick="filterCategory('{{ $cat->slug }}')"
                                                    class="text-[10px] text-gray-500 hover:text-black uppercase tracking-widest transition">
                                                    {{ $cat->name }}
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <a href="#about" class="nav-link font-bold hover:text-gray-400 uppercase text-[11px] tracking-widest">About</a>
                    </div>

                    <div class="flex items-center space-x-1 md:space-x-4">
                        <button onclick="toggleSearch()" class="p-1.5 text-gray-600 hover:text-black transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </button>
                        <div class="p-1.5"><x-cart-count /></div>
                        @auth
                            <a href="{{ Auth::user()->is_admin ? route('admin.dashboard') : route('dashboard') }}" class="p-1.5 text-gray-600 hover:text-black transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                                </svg>
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="p-1.5 text-gray-600 hover:text-black transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                                </svg>
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        {{-- Search Bar --}}
        <div id="search-container">
            <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">
                <div class="flex items-center flex-1 min-w-0 pl-5 md:pl-0">
                    <div class="text-gray-400 mr-4 shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text" id="search-input" autocomplete="off"
                        placeholder="SEARCH FOR..."
                        class="w-full bg-transparent border-none focus:ring-0 focus:outline-none text-[11px] tracking-[0.2em] font-light placeholder-gray-300 uppercase py-0 leading-none">
                </div>
                <button onclick="toggleSearch()" class="text-gray-400 hover:text-black transition ml-4 pr-8 md:pr-0 shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div id="search-results-overlay" class="pb-12 animate-fade-in-down">
                <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-4 gap-12 mt-8">
                    <div class="md:col-span-1">
                        <h5 class="text-[9px] font-bold tracking-[0.3em] text-gray-400 uppercase mb-6 border-b pb-2">Suggestions</h5>
                        <ul id="suggestion-list" class="space-y-3"></ul>
                    </div>
                    <div class="md:col-span-3">
                        <h5 class="text-[9px] font-bold tracking-[0.3em] text-gray-400 uppercase mb-6 border-b pb-2">Products</h5>
                        <div id="suggestion-products" class="grid grid-cols-2 md:grid-cols-4 gap-6"></div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    {{-- ══ MOBILE MENU ══ --}}
    <div id="mobile-menu" class="fixed inset-0 bg-white z-[60] md:hidden flex flex-col p-8 space-y-8 translate-x-full transition-transform duration-300">
        <div class="flex justify-end">
            <button id="close-menu-button" class="text-gray-500 p-2">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <nav class="flex flex-col space-y-6">
            {{-- FIX: Shop All di mobile juga scroll ke #koleksi --}}
            <a href="#koleksi" onclick="toggleMenu()" class="text-sm font-bold tracking-widest uppercase border-b pb-2">Shop All</a>
            <div class="space-y-4">
                <p class="text-[10px] text-gray-400 tracking-widest uppercase">Collections</p>
                <div class="grid grid-cols-1 gap-4 pl-4">
                    @foreach($categories as $cat)
                    <button onclick="filterCategory('{{ $cat->slug }}'); toggleMenu();"
                        class="text-left text-xs tracking-widest uppercase hover:text-gray-500">
                        {{ $cat->name }}
                    </button>
                    @endforeach
                </div>
            </div>
            <a href="#about" onclick="toggleMenu()" class="text-sm font-bold tracking-widest uppercase border-b pb-2">About</a>
        </nav>
    </div>

    {{-- ══ HERO SLIDER ══ --}}
    <header class="relative overflow-hidden bg-[#E9E9E9]">
        <div class="swiper heroSwiper">
            <div class="swiper-wrapper">
                @forelse($sliders as $slider)
                    @php
                        $hasDesktop = !empty($slider->image_path);
                        $hasMobile  = !empty($slider->image_mobile_path);
                        $hasVideo   = !empty($slider->video_path);
                        $posterUrl  = \Storage::url($slider->image_path ?? $slider->image_mobile_path);
                    @endphp
                    <div class="swiper-slide relative {{ !$hasMobile && !$hasVideo ? 'desktop-only' : '' }} {{ !$hasDesktop && !$hasVideo ? 'mobile-only' : '' }}">
                        @if($hasVideo)
                            <video autoplay muted loop playsinline webkit-playsinline preload="auto" class="main-slider-img w-full h-full object-cover">
                                <source src="{{ asset('storage/' . $slider->video_path) }}" type="video/mp4">
                                <img src="{{ $posterUrl }}" class="w-full h-full object-cover">
                            </video>
                        @else
                            <picture>
                                @if($hasMobile)<source media="(max-width: 767px)" srcset="{{ \Storage::url($slider->image_mobile_path) }}">@endif
                                <img src="{{ $posterUrl }}" alt="{{ $slider->title }}" class="main-slider-img" loading="eager">
                            </picture>
                        @endif
                        <div class="absolute inset-0 bg-black/20"></div>
                        <div class="absolute inset-0 z-10 flex flex-col items-center justify-center text-center text-white px-4">
                            <h2 class="text-xs tracking-[0.6em] uppercase mb-6">{{ $slider->title }}</h2>
                            <h1 class="text-5xl md:text-6xl font-extralight tracking-widest uppercase mb-8">Koleksi Eksklusif</h1>
                            <a href="#koleksi" class="mt-10 inline-block px-10 py-4 border border-[#6B705C] text-[10px] tracking-[0.4em] uppercase text-white hover:bg-[#6B705C] transition duration-500">Shop Now</a>
                        </div>
                    </div>
                @empty
                    <div class="swiper-slide flex items-center justify-center bg-[#E9E9E9]">
                        <p class="text-gray-400 uppercase tracking-widest text-xs">No Banners Uploaded</p>
                    </div>
                @endforelse
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </header>

    {{-- ══ PRODUCT COLLECTION ══ --}}
    <section id="koleksi" class="py-24 max-w-7xl mx-auto px-4">
        <div class="text-center mb-16">
            <h3 class="text-2xl font-medium tracking-[0.3em] uppercase mb-4">Our Collection</h3>
        </div>

        <div class="flex flex-wrap justify-center gap-8 mb-20">
            <button type="button" onclick="filterCategory('all')" class="category-btn active text-[10px] uppercase tracking-[0.2em] pb-2 text-gray-400 hover:text-black transition">All Products</button>
            @foreach($categories as $cat)
                <button type="button" onclick="filterCategory('{{ $cat->slug }}')" class="category-btn text-[10px] uppercase tracking-[0.2em] pb-2 text-gray-400 hover:text-black transition">{{ $cat->name }}</button>
            @endforeach
        </div>

        <div id="product-grid" class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-5 gap-x-6 gap-y-12">
            @forelse($products as $product)
                <a href="{{ route('product.details', $product->slug) }}"
                    class="group block no-underline product-item"
                    data-category="{{ $product->category->slug }}"
                    data-name="{{ strtolower($product->name) }}"
                    data-image="{{ $product->images->first() ? asset('storage/' . $product->images->first()->image_path) : '' }}"
                    data-price="IDR {{ number_format($product->price, 0, ',', '.') }}">
                    <div class="relative overflow-hidden aspect-[3/4] bg-[#E9E9E9] mb-6">
                        @if($product->images->first())
                            <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" alt="{{ $product->name }}" class="w-full h-full object-cover transition duration-1000 group-hover:scale-105">
                        @else
                            <div class="flex items-center justify-center h-full text-gray-300 text-[10px] tracking-widest uppercase">No Image</div>
                        @endif
                    </div>
                    <div class="text-center">
                        <h4 class="product-title text-[11px] font-bold tracking-widest uppercase mb-1">{{ $product->name }}</h4>
                        <p class="text-[10px] text-gray-400 mb-3">{{ $product->category->name ?? 'Collection' }}</p>
                        <p class="text-xs font-light tracking-widest">IDR {{ number_format($product->price, 0, ',', '.') }}</p>
                    </div>
                </a>
            @empty
                <div class="col-span-full text-center py-20">
                    <p class="text-gray-400 text-xs tracking-widest uppercase">The collection is currently being updated.</p>
                </div>
            @endforelse
        </div>
        <div id="no-results" class="hidden text-center py-20">
            <p class="text-gray-400 text-xs tracking-widest uppercase">No products found matching your search.</p>
        </div>
    </section>

    {{-- ══ FOOTER ══ --}}
    <footer id="about" class="bg-[#2F3526] text-white">

        <div class="max-w-7xl mx-auto px-6 pt-16 pb-14">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-16 text-left">

                {{-- About --}}
                <div>
                    <h4 class="text-[10px] font-bold tracking-[0.3em] uppercase mb-6">About Farhana</h4>
                    <p class="text-[11px] text-white/80 leading-loose tracking-widest uppercase">
                        Eksklusivitas dalam balutan kesantunan. Kami menghadirkan kualitas terbaik untuk gaya Muslim modern yang elegan dan berkelas.
                    </p>
                </div>

                {{-- Customer Care --}}
                <div>
                    <h4 class="text-[10px] font-bold tracking-[0.3em] uppercase mb-6">Customer Care</h4>
                    <ul class="space-y-3 text-[11px] tracking-widest uppercase text-white/80">
                        <li>
                            <button @click="openModal = 'contact'"
                                    class="hover:text-white hover:translate-x-1 transition-all duration-200">
                                CONTACT US
                            </button>
                        </li>
                        <li>
                            <button @click="openModal = 'shipping'"
                                    class="hover:text-white hover:translate-x-1 transition-all duration-200">
                                SHIPPING &amp; RETURNS
                            </button>
                        </li>
                        <li>
                            <button @click="openModal = 'howtobuy'"
                                    class="hover:text-white hover:translate-x-1 transition-all duration-200">
                                HOW TO BUY
                            </button>
                        </li>
                        <li>
                            <button @click="openModal = 'faqs'"
                                    class="hover:text-white hover:translate-x-1 transition-all duration-200">
                                FAQs
                            </button>
                        </li>
                    </ul>
                </div>

                {{-- Follow Us --}}
                <div>
                    <h4 class="text-[10px] font-bold tracking-[0.3em] uppercase mb-6">Follow Us</h4>
                    <div class="flex flex-col items-start gap-4 mt-2">

                        {{-- Instagram --}}
                        <div class="flex items-center gap-3">
                            <a href="https://www.instagram.com/farhanas.id"
                               class="w-9 h-9 rounded-full bg-white flex items-center justify-center text-[#2F3526] hover:bg-[#E9E9E9] transition"
                               target="_blank" rel="noopener" aria-label="Instagram Farhana">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                </svg>
                            </a>
                            <span class="text-[10px] tracking-widest uppercase text-white/80">@farhanas.id</span>
                        </div>

                        {{-- TikTok --}}
                        <div class="flex items-center gap-3">
                            <a href="https://www.tiktok.com/@farhanas.id"
                               class="w-9 h-9 rounded-full bg-white flex items-center justify-center text-[#2F3526] hover:bg-[#E9E9E9] transition"
                               target="_blank" rel="noopener" aria-label="TikTok Farhana">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.17-2.86-.6-4.12-1.31a8.52 8.52 0 0 1-1.87-1.43v6.24c-.03 2.34-.79 4.7-2.6 6.13-1.81 1.43-4.39 1.83-6.57 1.15-2.18-.68-4.05-2.48-4.66-4.67-.61-2.19-.19-4.78 1.25-6.58 1.44-1.8 3.84-2.74 6.11-2.43v4.18c-1.13-.19-2.36.03-3.23.82-.87.79-1.2 2.06-.85 3.19.35 1.13 1.43 1.99 2.61 2.08 1.18.09 2.4-.42 3.01-1.43.25-.42.36-.91.36-1.4V0z"/>
                                </svg>
                            </a>
                            <span class="text-[10px] tracking-widest uppercase text-white/80">@farhanas.id</span>
                        </div>

                    </div>
                </div>

            </div>
        </div>

        <div class="border-t border-white/10 py-4 text-center">
            <p class="text-[9px] tracking-[0.4em] text-white/40 uppercase">&copy; 2026 Farhana Official. All Rights Reserved.</p>
        </div>
    </footer>

    {{-- ══ SCRIPTS ══ --}}
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const mobileBtn  = document.getElementById('mobile-menu-button');
            const closeBtn   = document.getElementById('close-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');

            window.toggleMenu = function () { mobileMenu.classList.toggle('open'); }
            if (mobileBtn) mobileBtn.addEventListener('click', toggleMenu);
            if (closeBtn)  closeBtn.addEventListener('click', toggleMenu);

            const isMobile = window.innerWidth < 768;
            document.querySelectorAll('.swiper-slide').forEach(el => {
                const isVideo = el.querySelector('video');
                if (!isVideo) {
                    if (isMobile && el.classList.contains('desktop-only')) el.remove();
                    if (!isMobile && el.classList.contains('mobile-only')) el.remove();
                }
            });

            new Swiper('.heroSwiper', {
                loop: true,
                effect: 'fade',
                fadeEffect: { crossFade: true },
                autoplay: { delay: 5000, disableOnInteraction: false },
                pagination: { el: '.swiper-pagination', clickable: true },
                observer: true,
                observeParents: true,
            });

            const searchInput          = document.getElementById('search-input');
            const searchResultsOverlay = document.getElementById('search-results-overlay');
            const suggestionList       = document.getElementById('suggestion-list');
            const suggestionProducts   = document.getElementById('suggestion-products');
            const productItems         = Array.from(document.querySelectorAll('.product-item'));

            searchInput.addEventListener('input', function (e) {
                const term = e.target.value.toLowerCase().trim();
                if (term.length > 0) {
                    searchResultsOverlay.classList.add('show');
                    renderSuggestions(term);
                } else {
                    searchResultsOverlay.classList.remove('show');
                }
            });

            function renderSuggestions(term) {
                suggestionList.innerHTML = '';
                suggestionProducts.innerHTML = '';

                const matches = productItems.filter(item =>
                    item.getAttribute('data-name').includes(term)
                );

                if (matches.length > 0) {
                    const uniqueNames = [...new Set(matches.map(m => m.getAttribute('data-name')))].slice(0, 5);
                    uniqueNames.forEach(name => {
                        const li = document.createElement('li');
                        li.className = "text-[10px] tracking-widest uppercase cursor-pointer hover:text-gray-400 transition";
                        li.innerHTML = name.replace(term, `<span class="font-bold text-black">${term}</span>`);
                        li.onclick = () => { searchInput.value = name; renderSuggestions(name); };
                        suggestionList.appendChild(li);
                    });

                    matches.slice(0, 4).forEach(item => {
                        const productUrl = item.getAttribute('href');
                        const img        = item.getAttribute('data-image');
                        const name       = item.querySelector('.product-title').innerText;
                        const price      = item.getAttribute('data-price');

                        const div = document.createElement('div');
                        div.innerHTML = `
                            <a href="${productUrl}" class="block group">
                                <div class="aspect-[3/4] bg-gray-100 overflow-hidden mb-3">
                                    <img src="${img}" class="w-full h-full object-cover transition duration-700 group-hover:scale-105">
                                </div>
                                <h6 class="text-[9px] font-bold tracking-widest uppercase mb-1">${name}</h6>
                                <p class="text-[9px] text-gray-400">${price}</p>
                            </a>
                        `;
                        suggestionProducts.appendChild(div);
                    });
                } else {
                    suggestionList.innerHTML = `<li class="text-[10px] text-gray-400 uppercase tracking-widest">No matching found</li>`;
                }
            }
        });

        function toggleSearch() {
            const searchBox = document.getElementById('search-container');
            const overlay   = document.getElementById('search-results-overlay');
            searchBox.classList.toggle('active');
            if (searchBox.classList.contains('active')) {
                setTimeout(() => { document.getElementById('search-input').focus(); }, 300);
            } else {
                document.getElementById('search-input').value = '';
                overlay.classList.remove('show');
            }
        }

        function filterCategory(category) {
            const products = document.querySelectorAll('.product-item');
            const buttons  = document.querySelectorAll('.category-btn');
            buttons.forEach(btn => btn.classList.remove('active'));
            buttons.forEach(btn => {
                const btnText = btn.textContent.trim().toLowerCase();
                if ((category === 'all' && btnText === 'all products') || btnText === category.replace('-', ' ').toLowerCase()) {
                    btn.classList.add('active');
                }
            });
            products.forEach(p => {
                p.style.display = (category === 'all' || p.dataset.category === category) ? 'block' : 'none';
            });
            document.getElementById('koleksi').scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    </script>
</body>
</html>