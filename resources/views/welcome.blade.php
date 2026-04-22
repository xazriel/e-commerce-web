<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Farhana Web - Exclusive Moslem Wear</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <style>
        .nav-link {
            font-size: 0.7rem;
            letter-spacing: 0.2em;
            transition: all 0.3s ease;
        }
        .mega-menu {
            display: none;
            position: absolute;
            left: 0;
            width: 100%;
            background: white;
            z-index: 50;
            border-bottom: 1px solid #f3f4f6;
        }
        .group:hover .mega-menu {
            display: block;
        }
        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-down {
            animation: fadeInDown 0.4s ease-out;
        }
        
        .heroSwiper {
            height: 90vh;
        }
        .swiper-slide {
            overflow: hidden;
        }
        .swiper-slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 8s ease-out;
        }
        .swiper-slide-active img {
            transform: scale(1.1);
        }
        .swiper-pagination-bullet-active {
            background: #fff !important;
        }

        .category-btn.active {
            font-weight: 700;
            border-bottom: 1px solid black;
            color: black;
        }

        /* Mobile Menu Style */
        #mobile-menu {
            transition: all 0.3s ease-in-out;
            transform: translateX(-100%);
        }
        #mobile-menu.open {
            transform: translateX(0);
        }

        @media (min-width: 768px) {
            .mobile-only {
                display: none !important;
            }
        }
        @media (max-width: 767px) {
            .desktop-only {
                display: none !important;
            }
        }
    </style>
</head>
<body class="antialiased bg-white text-gray-900">

    <nav class="bg-white border-b border-gray-100 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                
                <div class="flex items-center md:hidden">
                    <button id="mobile-menu-button" class="text-gray-600 hover:text-black focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>

                <div class="flex-shrink-0">
                    <a href="{{ route('home') }}" class="text-lg font-light tracking-[0.4em] uppercase">
                        Farhana
                    </a>
                </div>
                
                <div class="hidden md:flex space-x-10 items-center">
                    <a href="{{ route('home') }}" class="nav-link font-bold hover:text-gray-400 uppercase">Shop All</a>
                    
                    <div class="group static">
                        <button class="nav-link font-bold hover:text-gray-400 flex items-center uppercase">
                            Collections 
                            <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <div class="mega-menu pt-10 pb-12 shadow-2xl animate-fade-in-down">
                            <div class="max-w-7xl mx-auto px-8 grid grid-cols-4 gap-12">
                                @foreach($categories as $cat)
                                <div>
                                    <h4 class="text-[11px] font-black tracking-widest text-gray-900 mb-5 uppercase border-b pb-2">
                                        {{ $cat->name }}
                                    </h4>
                                    <ul class="space-y-3">
                                        <li>
                                            <button
                                                type="button"
                                                onclick="filterCategory('{{ $cat->slug }}')"
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

                    <a href="#about" class="nav-link font-bold hover:text-gray-400 uppercase">About</a>
                </div>

                <div class="flex items-center space-x-6">
                    <button class="text-gray-600 hover:text-black transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </button>

                    <x-cart-count />

                    @auth
                        <a href="{{ Auth::user()->is_admin ? route('admin.dashboard') : route('dashboard') }}" class="text-gray-600 hover:text-black transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                            </svg>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-600 hover:text-black transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                            </svg>
                        </a>
                    @endauth
                </div>
            </div>
        </div>

        <div id="mobile-menu" class="fixed inset-0 bg-white z-[60] md:hidden flex flex-col p-8 space-y-8">
            <div class="flex justify-end">
                <button id="close-menu-button" class="text-gray-500">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <nav class="flex flex-col space-y-6">
                <a href="{{ route('home') }}" class="text-sm font-bold tracking-widest uppercase border-b pb-2">Shop All</a>
                
                <div class="space-y-4">
                    <p class="text-[10px] text-gray-400 tracking-widest uppercase">Collections</p>
                    <div class="grid grid-cols-1 gap-4 pl-4">
                        @foreach($categories as $cat)
                        <button onclick="filterCategory('{{ $cat->slug }}'); toggleMenu();" class="text-left text-xs tracking-widest uppercase hover:text-gray-500">
                            {{ $cat->name }}
                        </button>
                        @endforeach
                    </div>
                </div>

                <a href="#about" onclick="toggleMenu()" class="text-sm font-bold tracking-widest uppercase border-b pb-2">About</a>
            </nav>
        </div>
    </nav>

    <header class="relative overflow-hidden bg-gray-100">
        <div class="swiper heroSwiper">
            <div class="swiper-wrapper">
                @forelse($sliders as $slider)
                    @php
                        $hasDesktop = !empty($slider->image_path);
                        $hasMobile = !empty($slider->image_mobile_path);
                    @endphp

                    <div class="swiper-slide relative {{ !$hasMobile ? 'desktop-only' : '' }} {{ !$hasDesktop ? 'mobile-only' : '' }}">
                        <picture>
                            @if($hasMobile)
                                <source media="(max-width: 767px)" srcset="{{ asset('storage/' . $slider->image_mobile_path) }}">
                            @endif
                            
                            <img src="{{ asset('storage/' . ($slider->image_path ?? $slider->image_mobile_path)) }}" 
                                 alt="{{ $slider->title }}"
                                 class="main-slider-img"
                                 loading="eager">
                        </picture>

                        <div class="absolute inset-0 bg-black/20"></div>

                        <div class="absolute inset-0 z-10 flex flex-col items-center justify-center text-center text-white px-4">
                            <h2 class="text-xs tracking-[0.6em] uppercase mb-6 animate-fade-in-down">
                                {{ $slider->title ?? 'Given Beauty, Wrapped in Modesty' }}
                            </h2>

                            <h1 class="text-5xl md:text-6xl font-extralight tracking-widest uppercase mb-8">
                                Koleksi Eksklusif
                            </h1>

                            <a href="#koleksi" class="mt-10 inline-block px-10 py-4 border border-[#6B7D5C] text-[10px] tracking-[0.4em] uppercase text-white hover:bg-[#6B7D5C] hover:text-white transition duration-500">
                                Shop Now
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="swiper-slide flex items-center justify-center bg-gray-200">
                        <p class="text-gray-400 uppercase tracking-widest text-xs">No Banners Uploaded</p>
                    </div>
                @endforelse
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </header>

    <section id="koleksi" class="py-24 max-w-7xl mx-auto px-4">
        <div class="text-center mb-16">
            <h3 class="text-2xl font-medium tracking-[0.3em] uppercase mb-4">
                Our Collection
            </h3>
        </div>
        
        <div class="flex flex-wrap justify-center gap-8 mb-20">
            <button
                type="button"
                onclick="filterCategory('all')"
                class="category-btn active text-[10px] uppercase tracking-[0.2em] pb-2 text-gray-400 hover:text-black transition">
                All Products
            </button>

            @foreach($categories as $cat)
                <button
                    type="button"
                    onclick="filterCategory('{{ $cat->slug }}')"
                    class="category-btn text-[10px] uppercase tracking-[0.2em] pb-2 text-gray-400 hover:text-black transition">
                    {{ $cat->name }}
                </button>
            @endforeach
        </div>
        
        <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-5 gap-x-6 gap-y-12">
            @forelse($products as $product)
                <a href="{{ route('product.details', $product->slug) }}"
                   class="group block no-underline product-item"
                   data-category="{{ $product->category->slug }}">
                    <div class="relative overflow-hidden aspect-[3/4] bg-gray-50 mb-6">
                        @if($product->images->first())
                            <img src="{{ asset('storage/' . $product->images->first()->image_path) }}"
                                 alt="{{ $product->name }}"
                                 class="w-full h-full object-cover transition duration-1000 group-hover:scale-105">
                        @else
                            <div class="flex items-center justify-center h-full text-gray-300 text-[10px] tracking-widest uppercase">
                                No Image
                            </div>
                        @endif
                    </div>

                    <div class="text-center">
                        <h4 class="text-[11px] font-bold tracking-widest uppercase mb-1">
                            {{ $product->name }}
                        </h4>
                        <p class="text-[10px] text-gray-400 italic mb-3">
                            {{ $product->category->name ?? 'Collection' }}
                        </p>
                        <p class="text-xs font-light tracking-widest">
                            IDR {{ number_format($product->price, 0, ',', '.') }}
                        </p>
                    </div>
                </a>
            @empty
                <div class="col-span-full text-center py-20">
                    <p class="text-gray-400 text-xs tracking-widest uppercase italic">
                        The collection is currently being updated.
                    </p>
                </div>
            @endforelse
        </div>
    </section>

    <footer id="about" class="py-16 bg-[#5A5A00] text-white">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-16 text-left">
                <div>
                    <h4 class="text-[10px] font-bold tracking-[0.3em] uppercase mb-6">About Farhana</h4>
                    <p class="text-[11px] text-white/80 leading-loose tracking-widest uppercase">
                        Eksklusivitas dalam balutan kesantunan. Kami menghadirkan kualitas terbaik untuk gaya Muslim modern yang elegan dan berkelas.
                    </p>
                </div>

                <div>
                    <h4 class="text-[10px] font-bold tracking-[0.3em] uppercase mb-6">Customer Care</h4>
                    <ul class="space-y-3 text-[11px] tracking-widest uppercase text-white/80">
                        <li><a href="#" class="hover:text-black transition">Contact Us</a></li>
                        <li><a href="#" class="hover:text-black transition">Shipping & Returns</a></li>
                        <li><a href="#" class="hover:text-black transition">How to Buy</a></li>
                        <li><a href="#" class="hover:text-black transition">FAQs</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-[10px] font-bold tracking-[0.3em] uppercase mb-6">Follow Us</h4>
                    <div class="flex space-x-4">
                        <a href="#" class="w-9 h-9 rounded-full bg-white flex items-center justify-center text-[#5A5A00] hover:bg-gray-200 transition">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                            </svg>
                        </a>
                        <span class="self-center text-[10px] tracking-widest uppercase">@farhana.official</span>
                    </div>
                </div>
            </div>

            <div class="mt-20 pt-8 border-t border-white/10 text-center">
                <p class="text-[9px] tracking-[0.4em] text-white/40 uppercase">
                    &copy; 2026 Farhana Official. All Rights Reserved.
                </p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile Menu Toggle Logic
            const mobileBtn = document.getElementById('mobile-menu-button');
            const closeBtn = document.getElementById('close-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');

            window.toggleMenu = function() {
                mobileMenu.classList.toggle('open');
            }

            if(mobileBtn) mobileBtn.addEventListener('click', toggleMenu);
            if(closeBtn) closeBtn.addEventListener('click', toggleMenu);

            function initSwiper() {
                const isMobile = window.innerWidth < 768;

                if (isMobile) {
                    document.querySelectorAll('.swiper-slide.desktop-only').forEach(el => el.remove());
                } else {
                    document.querySelectorAll('.swiper-slide.mobile-only').forEach(el => el.remove());
                }

                return new Swiper('.heroSwiper', {
                    loop: true,
                    effect: 'fade',
                    fadeEffect: { crossFade: true },
                    autoplay: { delay: 5000, disableOnInteraction: false },
                    pagination: { el: '.swiper-pagination', clickable: true },
                    observer: true,
                    observeParents: true,
                    on: {
                        init: function() {
                            // Ensure first slide is visible and scaled
                        }
                    }
                });
            }

            initSwiper();
        });

        function filterCategory(category) {
            const products = document.querySelectorAll('.product-item');
            const buttons = document.querySelectorAll('.category-btn');

            buttons.forEach(btn => btn.classList.remove('active'));

            buttons.forEach(btn => {
                if (
                    (category === 'all' && btn.textContent.trim().toLowerCase() === 'all products') ||
                    btn.textContent.trim().toLowerCase() === category.toLowerCase()
                ) {
                    btn.classList.add('active');
                }
            });

            products.forEach(product => {
                if (category === 'all' || product.dataset.category === category) {
                    product.style.display = 'block';
                } else {
                    product.style.display = 'none';
                }
            });

            document.getElementById('koleksi').scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    </script>
</body>
</html>