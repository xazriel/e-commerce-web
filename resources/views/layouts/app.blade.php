<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Farhana Web') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <style>[x-cloak] { display: none !important; }</style>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-50 text-gray-900" x-data="{ mobileMenuOpen: false, loginModal: false }">
        
        <div class="flex min-h-screen overflow-hidden">
            
            {{-- SIDEBAR: HANYA MUNCUL UNTUK ADMIN --}}
            @auth
                @if(auth()->user()->role === 'admin')
                <aside class="w-64 bg-white border-r border-gray-200 hidden md:flex flex-col flex-shrink-0 shadow-sm font-bold">
                    <div class="h-full flex flex-col">
                        <div class="p-6 border-b border-gray-100 bg-white sticky top-0">
                            <span class="text-xl font-black tracking-tighter text-black uppercase">
                                FARHANA ADMIN
                            </span>
                        </div>

                        <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
                            <p class="text-[10px] font-bold text-gray-400 uppercase px-3 mb-2 tracking-[0.2em]">Utama</p>
                            
                            <a href="{{ route('admin.dashboard') }}" 
                               class="flex items-center px-4 py-3 text-sm rounded-xl transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-black text-white shadow-lg font-bold' : 'text-gray-600 hover:bg-gray-100' }}">
                                Dashboard
                            </a>

                            <p class="text-[10px] font-bold text-gray-400 uppercase px-3 mt-6 mb-2 tracking-[0.2em]">Katalog</p>

                            <a href="{{ route('categories.index') }}" 
                               class="flex items-center px-4 py-3 text-sm rounded-xl transition-all {{ request()->routeIs('categories.*') ? 'bg-black text-white shadow-lg font-bold' : 'text-gray-600 hover:bg-gray-100' }}">
                                Kelola Kategori
                            </a>

                            <a href="{{ route('products.index') }}" 
                               class="flex items-center px-4 py-3 text-sm rounded-xl transition-all {{ request()->routeIs('products.*') ? 'bg-black text-white shadow-lg font-bold' : 'text-gray-600 hover:bg-gray-100' }}">
                                Kelola Produk
                            </a>

                            <a href="{{ route('sliders.index') }}" 
                               class="flex items-center px-4 py-3 text-sm rounded-xl transition-all {{ request()->routeIs('sliders.*') ? 'bg-black text-white shadow-lg font-bold' : 'text-gray-600 hover:bg-gray-100' }}">
                                Kelola Banner
                            </a>
                        </nav>

                        <div class="p-4 border-t border-gray-100 bg-gray-50">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-3 text-sm text-red-600 hover:bg-red-100 rounded-xl font-bold transition-all">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </aside>
                @endif
            @endauth

            {{-- CONTENT AREA --}}
            <div class="flex-1 flex flex-col min-w-0 h-screen overflow-hidden">
                
                <header class="bg-white border-b border-gray-200 sticky top-0 z-30 flex-shrink-0">
                    <div class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">
                        {{-- Hamburger Button --}}
                        @auth
                            @if(auth()->user()->role === 'admin')
                            <button @click="mobileMenuOpen = true" class="md:hidden p-2 rounded-md text-gray-600 hover:bg-gray-100 focus:outline-none">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                            </button>
                            @endif
                        @endauth

                        <div class="flex-1 flex justify-between items-center px-4 md:px-0">
                            <h2 class="font-bold text-lg text-gray-800 truncate uppercase tracking-widest text-sm">
                                @isset($header) {{ $header }} @else Farhana Official @endisset
                            </h2>
                            @include('layouts.navigation')
                        </div>
                    </div>
                </header>

                {{-- MAIN CONTENT --}}
                <main class="flex-1 overflow-y-auto bg-gray-50 focus:outline-none p-4 md:p-8">
                    <div class="max-w-7xl mx-auto">
                        <div class="{{ (auth()->check() && auth()->user()->role === 'admin') ? 'bg-white rounded-3xl p-6 shadow-sm border border-gray-100' : '' }} min-h-[80vh]">
                            {{ $slot }}
                        </div>
                    </div>
                </main>
            </div>

            {{-- MOBILE MENU: ADMIN ONLY --}}
            @auth
                @if(auth()->user()->role === 'admin')
                <div x-show="mobileMenuOpen" x-cloak
                     @click="mobileMenuOpen = false"
                     class="fixed inset-0 z-40 md:hidden bg-black/50 backdrop-blur-sm"></div>

                <div x-show="mobileMenuOpen" x-cloak
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="-translate-x-full"
                     x-transition:enter-end="translate-x-0"
                     class="fixed inset-y-0 left-0 z-50 w-72 bg-white shadow-2xl md:hidden p-6 flex flex-col">
                    {{-- Mobile menu content (same as sidebar) --}}
                    <nav class="flex-1 space-y-2">
                         <a href="{{ route('admin.dashboard') }}" class="block px-4 py-3 rounded-xl font-bold bg-gray-100 text-black">Dashboard</a>
                         <a href="{{ route('products.index') }}" class="block px-4 py-3 rounded-xl font-bold text-gray-600">Produk</a>
                    </nav>
                </div>
                @endif
            @endauth

            {{-- ========================================== --}}
            {{-- FLOATING LOGIN MODAL (GUEST ONLY) --}}
            {{-- ========================================== --}}
            @guest
            <div x-show="loginModal" 
                 @open-login.window="loginModal = true" 
                 x-cloak 
                 class="fixed inset-0 z-[99] flex items-center justify-center p-4">
                
                <div x-show="loginModal" 
                     x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                     @click="loginModal = false"
                     class="fixed inset-0 bg-black/60 backdrop-blur-sm"></div>

                <div x-show="loginModal" 
                     x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                     class="relative bg-white w-full max-w-md p-8 md:p-12 shadow-2xl rounded-none border border-gray-100">
                    
                    <button @click="loginModal = false" class="absolute top-4 right-4 text-gray-400 hover:text-black transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>

                    <div class="text-center mb-8">
                        <h2 class="text-[10px] font-black uppercase tracking-[0.4em] text-[#5A5A00] mb-2">Welcome Back</h2>
                        <p class="text-[11px] text-gray-400 uppercase tracking-widest italic">Sign in to continue</p>
                    </div>

                    <form method="POST" action="{{ route('login') }}" class="space-y-6">
                        @csrf
                        <div>
                            <label class="text-[9px] font-bold uppercase tracking-widest text-gray-400 block mb-1">Email</label>
                            <input type="email" name="email" required class="w-full border-gray-200 border-x-0 border-t-0 border-b p-2 text-sm focus:ring-0 focus:border-[#5A5A00] bg-transparent">
                        </div>
                        <div>
                            <label class="text-[9px] font-bold uppercase tracking-widest text-gray-400 block mb-1">Password</label>
                            <input type="password" name="password" required class="w-full border-gray-200 border-x-0 border-t-0 border-b p-2 text-sm focus:ring-0 focus:border-[#5A5A00] bg-transparent">
                        {{-- Di dalam form modal, di bawah input password --}}
                        @if ($errors->any())
                            <div class="mb-4">
                                <p class="text-[9px] uppercase tracking-widest text-red-500 font-bold">
                                    {{ $errors->first() }}
                                </p>
                            </div>
                        @endif
                        </div>
                        <button type="submit" class="w-full bg-black text-white py-4 text-[10px] font-bold uppercase tracking-[0.3em] hover:bg-[#5A5A00] transition">
                            Sign In
                        </button>
                    </form>

                    <div class="mt-8 text-center pt-6 border-t border-gray-50">
                        <p class="text-[10px] uppercase tracking-widest text-gray-400">
                            New here? <a href="{{ route('register') }}" class="text-black font-bold border-b border-black ml-1">Create Account</a>
                        </p>
                    </div>
                </div>
            </div>
            @endguest

        </div>
    @if ($errors->has('email') || $errors->has('password'))
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Menggunakan dispatch agar Alpine mendeteksi dan membuka modal otomatis
            window.dispatchEvent(new CustomEvent('open-login'));
        });
    </script>
    @endif

    </body>
</html>