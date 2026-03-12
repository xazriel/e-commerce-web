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
        
        /* Fix Swiper & Zoom Effect */
        .heroSwiper {
            height: 85vh;
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
    </style>
</head>
<body class="antialiased bg-white text-gray-900">

    <nav class="bg-white border-b border-gray-100 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex-shrink-0">
                    <a href="{{ route('home') }}" class="text-2xl font-light tracking-[0.4em] uppercase">
                        Farhana
                    </a>
                </div>

                <div class="hidden md:flex space-x-10 items-center">
                    <a href="{{ route('home') }}" class="nav-link font-bold hover:text-gray-400 uppercase">Shop All</a>
                    <div class="group static">
                        <button class="nav-link font-bold hover:text-gray-400 flex items-center uppercase">
                            Collections 
                            <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div class="mega-menu pt-10 pb-12 shadow-2xl animate-fade-in-down">
                            <div class="max-w-7xl mx-auto px-8 grid grid-cols-4 gap-12">
                                @foreach($categories as $cat)
                                <div>
                                    <h4 class="text-[11px] font-black tracking-widest text-gray-900 mb-5 uppercase border-b pb-2">{{ $cat->name }}</h4>
                                    <ul class="space-y-3">
                                        <li>
                                            <a href="{{ route('home', ['category' => $cat->slug]) }}" class="text-[10px] text-gray-500 hover:text-black uppercase tracking-widest transition">
                                                View All {{ $cat->name }}
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <a href="#" class="nav-link font-bold hover:text-gray-400 uppercase">Our Story</a>
                </div>

                <div class="flex items-center space-x-6">
                    <button class="text-gray-600 hover:text-black transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </button>
                    @auth
                        <a href="{{ Auth::user()->is_admin ? route('admin.dashboard') : route('dashboard') }}" class="text-gray-600 hover:text-black transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z"/></svg>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-600 hover:text-black transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/></svg>
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <header class="relative overflow-hidden bg-gray-100">
        <div class="swiper heroSwiper">
            <div class="swiper-wrapper">
                @forelse($sliders as $slider)
                    <div class="swiper-slide relative {{ !$slider->image_mobile_path ? 'desktop-only' : '' }}">
                        <picture>
                            @if($slider->image_mobile_path)
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
                            <h1 class="text-5xl md:text-7xl font-extralight tracking-widest uppercase mb-8">
                                Koleksi Eksklusif
                            </h1>
                            <a href="#koleksi" class="px-10 py-4 border border-white text-[10px] tracking-[0.4em] uppercase hover:bg-white hover:text-black transition duration-500">
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
            <h3 class="text-2xl font-light tracking-[0.3em] uppercase mb-4">Our Collection</h3>
            <div class="w-12 h-[1px] bg-black mx-auto"></div>
        </div>
        
        <div class="flex flex-wrap justify-center gap-8 mb-20">
            <a href="{{ route('home') }}" 
               class="text-[10px] uppercase tracking-[0.2em] {{ !request('category') ? 'font-bold border-b border-black' : 'text-gray-400 hover:text-black transition' }} pb-2">
                All Products
            </a>
            @foreach($categories as $cat)
                <a href="{{ route('home', ['category' => $cat->slug]) }}" 
                   class="text-[10px] uppercase tracking-[0.2em] {{ request('category') == $cat->slug ? 'font-bold border-b border-black' : 'text-gray-400 hover:text-black transition' }} pb-2">
                    {{ $cat->name }}
                </a>
            @endforeach
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-x-6 gap-y-12">
            @forelse($products as $product)
                <a href="{{ route('product.details', $product->slug) }}" class="group block no-underline">
                    <div class="relative overflow-hidden aspect-[3/4] bg-gray-50 mb-6">
                        @if($product->images->first())
                            <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" 
                                 alt="{{ $product->name }}"
                                 class="w-full h-full object-cover transition duration-1000 group-hover:scale-105">
                        @else
                            <div class="flex items-center justify-center h-full text-gray-300 text-[10px] tracking-widest uppercase">No Image</div>
                        @endif
                    </div>
                    <div class="text-center">
                        <h4 class="text-[11px] font-bold tracking-widest uppercase mb-1">{{ $product->name }}</h4>
                        <p class="text-[10px] text-gray-400 italic mb-3">{{ $product->category->name ?? 'Collection' }}</p>
                        <p class="text-xs font-light tracking-widest">IDR {{ number_format($product->price, 0, ',', '.') }}</p>
                    </div>
                </a>
            @empty
                <div class="col-span-full text-center py-20">
                    <p class="text-gray-400 text-xs tracking-widest uppercase italic">The collection is currently being updated.</p>
                </div>
            @endforelse
        </div>
    </section>

    <footer class="py-20 border-t border-gray-100 bg-white">
        <div class="max-w-7xl mx-auto px-4 grid grid-cols-1 md:grid-cols-3 gap-12 text-center md:text-left">
            <div>
                <h4 class="text-[10px] font-bold tracking-[0.3em] uppercase mb-6">About Farhana</h4>
                <p class="text-[10px] text-gray-400 leading-loose tracking-widest uppercase">
                    Eksklusivitas dalam balutan kesantunan. Kami menghadirkan kualitas terbaik.
                </p>
            </div>
            <div class="text-center">
                <h4 class="text-[10px] font-bold tracking-[0.3em] uppercase mb-6">Follow Us</h4>
                <div class="flex justify-center space-x-6 text-gray-400">
                    <a href="#" class="hover:text-black transition text-[10px] tracking-widest uppercase">Instagram</a>
                    <a href="#" class="hover:text-black transition text-[10px] tracking-widest uppercase">TikTok</a>
                </div>
            </div>
            <div class="md:text-right">
                <h4 class="text-[10px] font-bold tracking-[0.3em] uppercase mb-6">Customer Care</h4>
                <ul class="text-[10px] text-gray-400 space-y-3 tracking-widest uppercase">
                    <li><a href="#" class="hover:text-black">Contact Us</a></li>
                    <li><a href="#" class="hover:text-black">Shipping & Returns</a></li>
                </ul>
            </div>
        </div>
        <div class="mt-20 text-center text-[9px] text-gray-300 uppercase tracking-[0.4em]">
            &copy; 2026 Farhana Official. All Rights Reserved.
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function initSwiper() {
                const isMobile = window.innerWidth < 768;
                // Hapus slide desktop-only jika di mobile SEBELUM Swiper mulai
                if (isMobile) {
                    document.querySelectorAll('.swiper-slide.desktop-only').forEach(el => el.remove());
                }

                return new Swiper('.heroSwiper', {
                    loop: true,
                    effect: 'fade',
                    fadeEffect: { crossFade: true },
                    autoplay: { delay: 5000, disableOnInteraction: false },
                    pagination: { el: '.swiper-pagination', clickable: true },
                    observer: true,
                    observeParents: true,
                });
            }
            initSwiper();
        });
    </script>
</body>
</html>