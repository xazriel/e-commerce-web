<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel Admin') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-100">
        <div class="flex min-h-screen">
            
            <aside class="w-64 bg-white border-r border-gray-200 hidden md:block flex-shrink-0 shadow-sm">
                <div class="h-full flex flex-col">
                    <div class="p-6 border-b border-gray-100">
                        <span class="text-xl font-bold tracking-tighter text-black uppercase">FARHANA ADMIN</span>
                    </div>

                    <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
                        <p class="text-xs font-semibold text-gray-400 uppercase px-3 mb-2 tracking-widest">Utama</p>
                        
                        <a href="{{ route('admin.dashboard') }}" 
                           class="flex items-center px-4 py-3 text-sm rounded-lg transition {{ request()->routeIs('admin.dashboard') ? 'bg-black text-white shadow-md font-bold' : 'text-gray-600 hover:bg-gray-50' }}">
                            Dashboard
                        </a>

                        <p class="text-xs font-semibold text-gray-400 uppercase px-3 mt-6 mb-2 tracking-widest">Katalog</p>

                        <a href="{{ route('categories.index') }}" 
                           class="flex items-center px-4 py-3 text-sm rounded-lg transition {{ request()->routeIs('categories.*') ? 'bg-black text-white shadow-md font-bold' : 'text-gray-600 hover:bg-gray-50' }}">
                            Kelola Kategori
                        </a>

                        <a href="{{ route('products.index') }}" 
                           class="flex items-center px-4 py-3 text-sm rounded-lg transition {{ request()->routeIs('products.*') ? 'bg-black text-white shadow-md font-bold' : 'text-gray-600 hover:bg-gray-50' }}">
                            Kelola Produk
                        </a>

                         <a href="{{ route('sliders.index') }}" 
                           class="flex items-center px-4 py-3 text-sm rounded-lg transition {{ request()->routeIs('products.*') ? 'bg-black text-white shadow-md font-bold' : 'text-gray-600 hover:bg-gray-50' }}">
                            Kelola Banner
                        </a>
                    </nav>

                    <div class="p-4 border-t border-gray-100">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-3 text-sm text-red-500 hover:bg-red-50 rounded-lg font-bold transition">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </aside>

            <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
                @include('layouts.navigation')

                @isset($header)
                    <header class="bg-white shadow-sm border-b border-gray-100">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <main class="flex-1 relative z-0 overflow-y-auto focus:outline-none py-6">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        {{ $slot }}
                    </div>
                </main>
            </div>
        </div>
    </body>
</html>