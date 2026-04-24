<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Farhana Web - Exclusive Moslem Wear</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <style>
        [x-cloak] { display: none !important; }
        
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
<body class="antialiased bg-white text-gray-900" 
      x-data="{ loginModal: false, searchOpen: false, searchQuery: '', contactModal: false, activeTab: 'contact' }">

    <div x-show="searchOpen" 
         x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[110] bg-white">
        
        <div class="max-w-7xl mx-auto px-4 h-full flex flex-col">
            <div class="flex justify-end pt-10">
                <button @click="searchOpen = false; searchQuery = ''; filterSearch();" class="text-gray-400 hover:text-black">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="flex-grow flex flex-col items-center justify-center -mt-20">
                <p class="text-[10px] tracking-[0.5em] uppercase text-gray-400 mb-8">What are you looking for?</p>
                <div class="w-full max-w-3xl relative">
                    <input type="text" 
                           x-model="searchQuery"
                           @input.debounce.300ms="filterSearch()"
                           @keydown.enter="searchOpen = false; document.getElementById('koleksi').scrollIntoView({ behavior: 'smooth' })"
                           placeholder="SEARCH OUR COLLECTIONS..." 
                           class="w-full border-b-2 border-gray-100 py-6 text-2xl md:text-4xl font-light tracking-widest outline-none focus:border-black transition-colors uppercase text-center">
                    
                    <div class="mt-8 text-center" x-show="searchQuery.length > 0">
                        <button @click="searchOpen = false; document.getElementById('koleksi').scrollIntoView({ behavior: 'smooth' })" 
                                class="text-[10px] tracking-[0.3em] uppercase underline underline-offset-8">
                            View Results
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
                    <a href="{{ route('home') }}" class="text-lg font-light tracking-[0.4em] uppercase">Farhana</a>
                </div>
                
                <div class="hidden md:flex space-x-10 items-center">
                    <a href="#koleksi" class="nav-link font-bold hover:text-gray-400 uppercase">Shop All</a>
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
                                            <button type="button" onclick="filterCategory('{{ $cat->slug }}')" class="text-[10px] text-gray-500 hover:text-black uppercase tracking-widest transition">
                                                View {{ $cat->name }}
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
                    <button @click="searchOpen = true" class="text-gray-600 hover:text-black transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </button>
                    <x-cart-count />
                    @auth
                        <a href="{{ Auth::user()->is_admin ? route('admin.dashboard') : route('dashboard') }}" class="text-gray-600 hover:text-black transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                        </a>
                    @else
                        <button @click="loginModal = true" class="text-gray-600 hover:text-black transition focus:outline-none">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                        </button>
                    @endauth
                </div>
            </div>
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
                            <img src="{{ asset('storage/' . ($slider->image_path ?? $slider->image_mobile_path)) }}" alt="{{ $slider->title }}" class="main-slider-img" loading="eager">
                        </picture>
                        <div class="absolute inset-0 bg-black/20"></div>
                        <div class="absolute inset-0 z-10 flex flex-col items-center justify-center text-center text-white px-4">
                            <h2 class="text-xs tracking-[0.6em] uppercase mb-6 animate-fade-in-down">{{ $slider->title ?? 'Given Beauty, Wrapped in Modesty' }}</h2>
                            <h1 class="text-5xl md:text-6xl font-extralight tracking-widest uppercase mb-8">Koleksi Eksklusif</h1>
                            <a href="#koleksi" class="mt-10 inline-block px-10 py-4 border border-[#6B7D5C] text-[10px] tracking-[0.4em] uppercase text-white hover:bg-[#6B7D5C] hover:text-white transition duration-500">Shop Now</a>
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
            <h3 class="text-2xl font-medium tracking-[0.3em] uppercase mb-4">Our Collection</h3>
            <p x-show="searchQuery.length > 0" x-cloak class="text-[10px] tracking-widest text-gray-400 uppercase">
                Showing results for: <span x-text="searchQuery" class="text-black font-bold"></span>
                <button @click="searchQuery = ''; filterSearch();" class="ml-2 underline italic">Clear</button>
            </p>
        </div>
        
        <div class="flex flex-wrap justify-center gap-8 mb-20">
            <button id="btn-all" onclick="filterCategory('all')" class="category-btn active text-[10px] uppercase tracking-[0.2em] pb-2 text-gray-400 hover:text-black transition">All Products</button>
            @foreach($categories as $cat)
                <button id="btn-{{ $cat->slug }}" onclick="filterCategory('{{ $cat->slug }}')" class="category-btn text-[10px] uppercase tracking-[0.2em] pb-2 text-gray-400 hover:text-black transition">{{ $cat->name }}</button>
            @endforeach
        </div>
        
        <div id="product-grid" class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-5 gap-x-6 gap-y-12">
            @forelse($products as $product)
                <a href="{{ route('product.details', $product->slug) }}" class="group block no-underline product-item" data-category="{{ $product->category->slug }}" data-name="{{ strtolower($product->name) }}">
                    <div class="relative overflow-hidden aspect-[3/4] bg-gray-50 mb-6">
                        @if($product->images->first())
                            <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" alt="{{ $product->name }}" class="w-full h-full object-cover transition duration-1000 group-hover:scale-105">
                        @else
                            <div class="flex items-center justify-center h-full text-gray-300 text-[10px] tracking-widest uppercase">No Image</div>
                        @endif
                    </div>
                    <div class="text-center">
                        <h4 class="text-[11px] font-bold tracking-widest uppercase mb-1 product-name-label">{{ $product->name }}</h4>
                        <p class="text-[10px] text-gray-400 italic mb-3">{{ $product->category->name ?? 'Collection' }}</p>
                        <p class="text-xs font-light tracking-widest">IDR {{ number_format($product->price, 0, ',', '.') }}</p>
                    </div>
                </a>
            @empty
                <div class="col-span-full text-center py-20">
                    <p class="text-gray-400 text-xs tracking-widest uppercase italic">The collection is currently being updated.</p>
                </div>
            @endforelse
            <div id="no-results" class="hidden col-span-full text-center py-20">
                <p class="text-gray-400 text-xs tracking-widest uppercase italic">No products match your search.</p>
            </div>
        </div>
    </section>

    <footer id="about" class="py-16 bg-[#5A5A00] text-white">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-16 text-left">
                <div>
                    <h4 class="text-[10px] font-bold tracking-[0.3em] uppercase mb-6">About Farhana</h4>
                    <p class="text-[11px] text-white/80 leading-loose tracking-widest uppercase">Eksklusivitas dalam balutan kesantunan. Kami menghadirkan kualitas terbaik untuk gaya Muslim modern yang elegan dan berkelas.</p>
                </div>
                <div>
                    <h4 class="text-[10px] font-bold tracking-[0.3em] uppercase mb-6">Customer Care</h4>
                    <ul class="space-y-3 text-[11px] tracking-widest uppercase text-white/80">
                        <li><button @click="contactModal = true; activeTab = 'contact'" class="hover:text-black transition uppercase">Contact Us</button></li>
                        <li><button @click="contactModal = true; activeTab = 'shipping'" class="hover:text-black transition uppercase">Shipping & Returns</button></li>
                        <li><button @click="contactModal = true; activeTab = 'how-to-buy'" class="hover:text-black transition uppercase">How to Buy</button></li>
                        <li><button @click="contactModal = true; activeTab = 'faqs'" class="hover:text-black transition uppercase">FAQs</button></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-[10px] font-bold tracking-[0.3em] uppercase mb-6">Follow Us</h4>
                    <div class="flex space-x-4">
                        <a href="#" class="w-9 h-9 rounded-full bg-white flex items-center justify-center text-[#5A5A00] hover:bg-gray-200 transition">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        </a>
                        <span class="self-center text-[10px] tracking-widest uppercase">@farhana.official</span>
                    </div>
                </div>
            </div>
            <div class="mt-20 pt-8 border-t border-white/10 text-center">
                <p class="text-[9px] tracking-[0.4em] text-white/40 uppercase">&copy; 2026 Farhana Official. All Rights Reserved.</p>
            </div>
        </div>
    </footer>

    <div x-show="contactModal" x-cloak class="fixed inset-0 z-[150] overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div x-show="contactModal" @click="contactModal = false" x-transition.opacity class="fixed inset-0 bg-black/60 backdrop-blur-sm"></div>
            <div x-show="contactModal" x-transition.scale.95 class="relative bg-white w-full max-w-4xl p-10 shadow-2xl z-10 overflow-hidden rounded-sm">
                <button @click="contactModal = false" class="absolute top-6 right-6 text-gray-400 hover:text-black z-20 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
                <div class="max-h-[80vh] overflow-y-auto pr-2">
                    <div x-show="activeTab === 'contact'">
                        @include('includes.contact')
                    </div>
                    <div x-show="activeTab === 'faqs'">
                        @include('includes.faqs')
                    </div>
                    <div x-show="activeTab === 'how-to-buy'">
                        @include('includes.how-to-buy')
                    </div>
                    <div x-show="activeTab === 'shipping'">
                        @include('includes.shipping')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div x-show="loginModal" x-cloak class="fixed inset-0 z-[120] overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div x-show="loginModal" @click="loginModal = false" x-transition.opacity class="fixed inset-0 bg-black/50 backdrop-blur-sm"></div>
            <div x-show="loginModal" x-transition.scale.95 class="relative bg-white w-full max-w-md p-10 shadow-2xl z-10">
                <div class="flex justify-between items-center mb-10">
                    <h2 class="text-xs tracking-[0.4em] uppercase font-bold text-black">Login to Farhana</h2>
                    <button @click="loginModal = false" class="text-gray-400 hover:text-black focus:outline-none"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                </div>
                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf
                    <div>
                        <label class="block text-[10px] tracking-[0.2em] uppercase text-gray-400 mb-2">Email Address</label>
                        <input type="email" name="email" required autofocus class="w-full border-b border-gray-200 focus:border-black outline-none py-2 text-sm tracking-widest">
                    </div>
                    <div x-data="{ show: false }">
                        <label class="block text-[10px] tracking-[0.2em] uppercase text-gray-400 mb-2">Password</label>
                        <div class="relative">
                            <input :type="show ? 'text' : 'password'" name="password" required class="w-full border-b border-gray-200 focus:border-black outline-none py-2 text-sm tracking-widest">
                            <button type="button" @click="show = !show" class="absolute right-0 top-2 text-gray-400 hover:text-black">
                                <svg x-show="!show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                <svg x-show="show" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7 1.274-4.057 5.064-7 9.542-7 1.254 0 2.438.271 3.5.755M21 21l-5.22-5.22m0 0A3 3 0 1010.78 10.78m5.22 5.22l4.22 4.22m-4.22-4.22l-5.22-5.22m3.93-2.017A3 3 0 0012 9h0c-.483 0-.939.114-1.343.315M3 3l3.59 3.59" /></svg>
                            </button>
                        </div>
                    </div>
                    <div class="pt-4"><button type="submit" class="w-full bg-black text-white py-4 text-[10px] tracking-[0.3em] uppercase hover:bg-gray-800 transition">Sign In</button></div>
                    <div class="text-center mt-6"><a href="{{ route('register') }}" class="text-[9px] tracking-[0.2em] uppercase text-gray-400 hover:text-black">Create Account</a></div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const swiper = new Swiper('.heroSwiper', {
                loop: true,
                effect: 'fade',
                autoplay: { delay: 5000 },
                pagination: { el: '.swiper-pagination', clickable: true },
            });
        });

        let currentCategory = 'all';

        function filterCategory(category) {
            currentCategory = category;
            const buttons = document.querySelectorAll('.category-btn');
            buttons.forEach(btn => btn.classList.remove('active'));
            const activeBtn = document.getElementById(category === 'all' ? 'btn-all' : `btn-${category}`);
            if(activeBtn) activeBtn.classList.add('active');
            executeFilter();
            document.getElementById('koleksi').scrollIntoView({ behavior: 'smooth' });
        }

        function filterSearch() { executeFilter(); }

        function executeFilter() {
            const products = document.querySelectorAll('.product-item');
            const noResults = document.getElementById('no-results');
            const searchInput = document.querySelector('[x-model="searchQuery"]');
            const query = searchInput ? searchInput.value.toLowerCase().trim() : '';
            let visibleCount = 0;

            products.forEach(p => {
                const matchesCategory = (currentCategory === 'all' || p.dataset.category === currentCategory);
                const matchesSearch = (query === '' || p.dataset.name.includes(query));
                if (matchesCategory && matchesSearch) {
                    p.style.display = 'block';
                    visibleCount++;
                } else {
                    p.style.display = 'none';
                }
            });
            visibleCount === 0 ? noResults.classList.remove('hidden') : noResults.classList.add('hidden');
        }
    </script>
</body>
</html>