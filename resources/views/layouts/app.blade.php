<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Farhana Web') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-50 text-gray-900" x-data="{ mobileMenuOpen: false }">
        <div class="flex min-h-screen overflow-hidden">
            
            {{-- SIDEBAR: HANYA MUNCUL UNTUK ADMIN --}}
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

            {{-- CONTENT AREA --}}
            <div class="flex-1 flex flex-col min-w-0 h-screen overflow-hidden">
                
                <header class="bg-white border-b border-gray-200 sticky top-0 z-30 flex-shrink-0">
                    <div class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">
                        {{-- Hamburger Button: Hanya muncul jika ada Sidebar (Admin) --}}
                        @if(auth()->user()->role === 'admin')
                        <button @click="mobileMenuOpen = true" class="md:hidden p-2 rounded-md text-gray-600 hover:bg-gray-100 focus:outline-none">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                        @endif

                        <div class="flex-1 flex justify-between items-center px-4 md:px-0">
                            <h2 class="font-bold text-lg text-gray-800 truncate">
                                @isset($header) {{ $header }} @else Farhana Official @endisset
                            </h2>
                            @include('layouts.navigation')
                        </div>
                    </div>
                </header>

                {{-- MAIN CONTENT --}}
                <main class="flex-1 overflow-y-auto bg-gray-50 focus:outline-none p-4 md:p-8">
                    <div class="max-w-7xl mx-auto">
                        {{-- Card wrapper: Kita buat transparan saja jika bukan admin agar lebih clean --}}
                        <div class="{{ auth()->user()->role === 'admin' ? 'bg-white rounded-3xl p-6 shadow-sm border border-gray-100' : '' }} min-h-[80vh]">
                            {{ $slot }}
                        </div>
                    </div>
                </main>
            </div>

            {{-- MOBILE MENU: HANYA UNTUK ADMIN --}}
            @if(auth()->user()->role === 'admin')
            <div x-show="mobileMenuOpen" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 z-40 md:hidden bg-black/50 backdrop-blur-sm" 
                 @click="mobileMenuOpen = false">
            </div>

            <div x-show="mobileMenuOpen" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="-translate-x-full"
                 x-transition:enter-end="translate-x-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="translate-x-0"
                 x-transition:leave-end="-translate-x-full"
                 class="fixed inset-y-0 left-0 z-50 w-72 bg-white shadow-2xl md:hidden p-6 flex flex-col">
                
                <div class="flex items-center justify-between mb-8">
                    <span class="text-lg font-black uppercase tracking-tighter font-bold">Admin Menu</span>
                    <button @click="mobileMenuOpen = false" class="text-gray-400">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <nav class="flex-1 space-y-2">
                     <a href="{{ route('admin.dashboard') }}" class="block px-4 py-3 rounded-xl font-bold bg-gray-100 text-black">Dashboard</a>
                     <a href="{{ route('products.index') }}" class="block px-4 py-3 rounded-xl font-bold text-gray-600">Produk</a>
                </nav>

                <div class="mt-auto pt-6 border-t border-gray-100">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-center py-4 text-red-600 font-bold italic">Logout</button>
                    </form>
                </div>
            </div>
            @endif

        </div>
    </body>
</html>