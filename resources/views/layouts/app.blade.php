<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Farhana Web') }}</title>

        <link rel="icon" type="image/svg+xml" href="{{ asset('farhana.svg') }}">

        <style>
            [x-cloak] { display: none !important; }

            *, *::before, *::after {
                font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            }

            :root {
                --primary:    #2F3526;
                --white:      #FFFFFF;
                --black:      #000000;
                --olive-tint: #6B705C;
                --light-gray: #E9E9E9;
            }

            /* ── Scrollbar ── */
            ::-webkit-scrollbar       { width: 3px; height: 3px; }
            ::-webkit-scrollbar-track { background: var(--light-gray); }
            ::-webkit-scrollbar-thumb { background: var(--primary); border-radius: 99px; }

            /* ── Sidebar nav link ── */
            .nav-link {
                display: flex;
                align-items: center;
                padding: 10px 16px;
                font-size: 11px;
                font-weight: 700;
                letter-spacing: .15em;
                text-transform: uppercase;
                border-radius: 12px;
                transition: background .2s ease, color .2s ease;
                color: var(--olive-tint);
                text-decoration: none;
            }
            .nav-link:hover {
                background: rgba(47,53,38,.07);
                color: var(--primary);
            }
            .nav-link.active {
                background: var(--primary);
                color: var(--white);
            }

            /* ── Modal transitions ── */
            [x-transition\:enter] { transition-property: opacity, transform; }

            /* ── Mobile drawer ── */
            @keyframes slideInLeft {
                from { transform: translateX(-100%); opacity: 0; }
                to   { transform: translateX(0);     opacity: 1; }
            }
            .mobile-drawer-enter { animation: slideInLeft .28s cubic-bezier(.22,.68,0,1.2) both; }

            /* ── Page load fade ── */
            @keyframes fadeUp {
                from { opacity: 0; transform: translateY(10px); }
                to   { opacity: 1; transform: translateY(0); }
            }
            main > div { animation: fadeUp .4s cubic-bezier(.22,.68,0,1.2) both; }

            /* ── Input underline focus ── */
            .modal-input {
                width: 100%;
                border: none;
                border-bottom: 1.5px solid var(--light-gray);
                padding: 8px 0;
                font-size: 13px;
                background: transparent;
                outline: none;
                color: var(--black);
                transition: border-color .2s ease;
                font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            }
            .modal-input:focus { border-bottom-color: var(--primary); }
        </style>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>

    <body class="antialiased" style="background: var(--light-gray); color: var(--black);"
          x-data="{ mobileMenuOpen: false, loginModal: false }">

        <div class="flex min-h-screen overflow-hidden">

            {{-- ── SIDEBAR: ADMIN ONLY ── --}}
            @auth
                @if(auth()->user()->role === 'admin' && !request()->routeIs('admin.dashboard'))
                <aside class="w-64 hidden md:flex flex-col flex-shrink-0"
                       style="background: var(--primary); min-height: 100vh;">

                    {{-- Logo --}}
                    <div class="px-7 py-7 flex-shrink-0" style="border-bottom: 1px solid rgba(255,255,255,.08);">
                        <span class="text-[13px] font-black tracking-[.35em] uppercase" style="color: var(--white);">
                            Farhana
                        </span>
                        <span class="block text-[8px] tracking-[.4em] uppercase mt-0.5"
                              style="color: rgba(255,255,255,.4);">Admin Panel</span>
                    </div>

                    {{-- Nav --}}
                    <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">

                        <p class="text-[8px] font-black uppercase tracking-[.35em] px-3 mb-3"
                           style="color: rgba(255,255,255,.35);">Utama</p>

                        <a href="{{ route('admin.dashboard') }}"
                           class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                           style="{{ request()->routeIs('admin.dashboard') ? '' : 'color: rgba(255,255,255,.6);' }}">
                            Dashboard
                        </a>

                        <p class="text-[8px] font-black uppercase tracking-[.35em] px-3 mt-6 mb-3"
                           style="color: rgba(255,255,255,.35);">Katalog</p>

                        <a href="{{ route('categories.index') }}"
                           class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}"
                           style="{{ request()->routeIs('categories.*') ? '' : 'color: rgba(255,255,255,.6);' }}">
                            Kelola Kategori
                        </a>

                        <a href="{{ route('products.index') }}"
                           class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}"
                           style="{{ request()->routeIs('products.*') ? '' : 'color: rgba(255,255,255,.6);' }}">
                            Kelola Produk
                        </a>

                        <a href="{{ route('sliders.index') }}"
                           class="nav-link {{ request()->routeIs('sliders.*') ? 'active' : '' }}"
                           style="{{ request()->routeIs('sliders.*') ? '' : 'color: rgba(255,255,255,.6);' }}">
                            Kelola Banner
                        </a>
                    </nav>

                    {{-- Logout --}}
                    <div class="px-4 py-5 flex-shrink-0" style="border-top: 1px solid rgba(255,255,255,.08);">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                    class="w-full text-left px-4 py-3 rounded-xl text-[10px] font-bold uppercase tracking-[.2em] transition-all hover:bg-red-500/20"
                                    style="color: rgba(255,100,100,.7);">
                                Logout
                            </button>
                        </form>
                    </div>
                </aside>
                @endif
            @endauth

            {{-- ── CONTENT AREA ── --}}
            <div class="flex-1 flex flex-col min-w-0 h-screen overflow-hidden">

                {{-- ── HEADER ── --}}
               {{-- ── HEADER ── --}}
<header class="flex-shrink-0 sticky top-0 z-30"
        style="background: var(--white); border-bottom: 1px solid var(--light-gray);">

    {{-- Hamburger admin mobile (di luar navigation agar tidak tumpang tindih) --}}
    @auth
        @if(auth()->user()->role === 'admin' && !request()->routeIs('admin.dashboard'))
        <div class="absolute left-4 top-4 md:hidden z-50">
            <button @click="mobileMenuOpen = true"
                    class="p-2 rounded-xl transition-colors"
                    style="color: var(--olive-tint);"
                    onmouseover="this.style.background='var(--light-gray)'"
                    onmouseout="this.style.background='transparent'">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>
        @endif
    @endauth

    @include('layouts.navigation')
</header>

                {{-- ── MAIN ── --}}
                <main class="flex-1 overflow-y-auto focus:outline-none p-4 md:p-8"
                      style="background: var(--light-gray);">
                    <div class="max-w-7xl mx-auto">
                        <div class="{{ (auth()->check() && auth()->user()->role === 'admin') ? 'rounded-3xl p-6 shadow-sm' : '' }} min-h-[80vh]"
                             style="{{ (auth()->check() && auth()->user()->role === 'admin') ? 'background: var(--white); border: 1px solid var(--light-gray);' : '' }}">
                            {{ $slot }}
                        </div>
                    </div>
                </main>
            </div>


            {{-- ── MOBILE DRAWER: ADMIN ONLY ── --}}
            @auth
                @if(auth()->user()->role === 'admin' && !request()->routeIs('admin.dashboard'))

                {{-- Backdrop --}}
                <div x-show="mobileMenuOpen" x-cloak
                     @click="mobileMenuOpen = false"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="fixed inset-0 z-40 md:hidden"
                     style="background: rgba(47,53,38,.55); backdrop-filter: blur(4px);"></div>

                {{-- Drawer --}}
                <div x-show="mobileMenuOpen" x-cloak
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="-translate-x-full opacity-0"
                     x-transition:enter-end="translate-x-0 opacity-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="translate-x-0 opacity-100"
                     x-transition:leave-end="-translate-x-full opacity-0"
                     class="fixed inset-y-0 left-0 z-50 w-72 md:hidden flex flex-col shadow-2xl"
                     style="background: var(--primary);">

                    {{-- Drawer logo --}}
                    <div class="px-7 py-7 flex items-center justify-between flex-shrink-0"
                         style="border-bottom: 1px solid rgba(255,255,255,.08);">
                        <div>
                            <span class="text-[13px] font-black tracking-[.35em] uppercase" style="color: var(--white);">Farhana</span>
                            <span class="block text-[8px] tracking-[.4em] uppercase mt-0.5" style="color: rgba(255,255,255,.4);">Admin Panel</span>
                        </div>
                        <button @click="mobileMenuOpen = false"
                                class="w-8 h-8 rounded-full flex items-center justify-center text-lg"
                                style="background: rgba(255,255,255,.1); color: var(--white);">
                            &times;
                        </button>
                    </div>

                    {{-- Drawer nav --}}
                    <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
                        <p class="text-[8px] font-black uppercase tracking-[.35em] px-3 mb-3"
                           style="color: rgba(255,255,255,.35);">Utama</p>

                        <a href="{{ route('admin.dashboard') }}"
                           class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                           style="{{ request()->routeIs('admin.dashboard') ? '' : 'color: rgba(255,255,255,.6);' }}">
                            Dashboard
                        </a>

                        <p class="text-[8px] font-black uppercase tracking-[.35em] px-3 mt-6 mb-3"
                           style="color: rgba(255,255,255,.35);">Katalog</p>

                        <a href="{{ route('categories.index') }}"
                           class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}"
                           style="{{ request()->routeIs('categories.*') ? '' : 'color: rgba(255,255,255,.6);' }}">
                            Kelola Kategori
                        </a>
                        <a href="{{ route('products.index') }}"
                           class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}"
                           style="{{ request()->routeIs('products.*') ? '' : 'color: rgba(255,255,255,.6);' }}">
                            Kelola Produk
                        </a>
                        <a href="{{ route('sliders.index') }}"
                           class="nav-link {{ request()->routeIs('sliders.*') ? 'active' : '' }}"
                           style="{{ request()->routeIs('sliders.*') ? '' : 'color: rgba(255,255,255,.6);' }}">
                            Kelola Banner
                        </a>
                    </nav>

                    {{-- Drawer logout --}}
                    <div class="px-4 py-5 flex-shrink-0" style="border-top: 1px solid rgba(255,255,255,.08);">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                    class="w-full text-left px-4 py-3 rounded-xl text-[10px] font-bold uppercase tracking-[.2em] transition-all hover:bg-red-500/20"
                                    style="color: rgba(255,100,100,.7);">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
                @endif
            @endauth


            {{-- ── FLOATING LOGIN MODAL (GUEST ONLY) ── --}}
            @guest
            <div x-show="loginModal"
                 @open-login.window="loginModal = true"
                 x-cloak
                 class="fixed inset-0 z-[99] flex items-center justify-center p-4">

                {{-- Backdrop --}}
                <div x-show="loginModal"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     @click="loginModal = false"
                     class="fixed inset-0"
                     style="background: rgba(47,53,38,.6); backdrop-filter: blur(6px);"></div>

                {{-- Modal card --}}
                <div x-show="loginModal"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                     x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                     x-transition:leave-end="opacity-0 scale-95 translate-y-4"
                     class="relative w-full max-w-md rounded-3xl shadow-2xl overflow-hidden"
                     style="background: var(--white);">

                    {{-- Top accent bar --}}
                    <div class="h-1.5 w-full" style="background: var(--primary);"></div>

                    <div class="px-10 py-10">

                        {{-- Close --}}
                        <button @click="loginModal = false"
                                class="absolute top-6 right-6 w-8 h-8 rounded-full flex items-center justify-center text-lg transition-colors"
                                style="background: var(--light-gray); color: var(--olive-tint);"
                                onmouseover="this.style.background='var(--primary)'; this.style.color='var(--white)';"
                                onmouseout="this.style.background='var(--light-gray)'; this.style.color='var(--olive-tint)';">
                            &times;
                        </button>

                        {{-- Title --}}
                        <div class="mb-9">
                            <p class="text-[8px] font-black uppercase tracking-[.4em] mb-1"
                               style="color: var(--olive-tint);">Welcome Back</p>
                            <h2 class="text-[22px] font-black uppercase tracking-[.15em]"
                                style="color: var(--primary);">Sign In</h2>
                            <p class="text-[10px] uppercase tracking-[.25em] mt-1"
                               style="color: var(--olive-tint);">to your account</p>
                        </div>

                        {{-- Error --}}
                        @if ($errors->any())
                        <div class="mb-6 px-4 py-3 rounded-2xl"
                             style="background: #FEF2F2; color: #B91C1C;">
                            <p class="text-[9px] uppercase tracking-widest font-bold">{{ $errors->first() }}</p>
                        </div>
                        @endif

                        {{-- Form --}}
                        <form method="POST" action="{{ route('login') }}" class="space-y-7">
                            @csrf

                            <div>
                                <label class="block text-[9px] font-bold uppercase tracking-[.2em] mb-2"
                                       style="color: var(--olive-tint);">Email</label>
                                <input type="email" name="email" required
                                       class="modal-input"
                                       placeholder="your@email.com">
                            </div>

                            <div>
                                <label class="block text-[9px] font-bold uppercase tracking-[.2em] mb-2"
                                       style="color: var(--olive-tint);">Password</label>
                                <input type="password" name="password" required
                                       class="modal-input"
                                       placeholder="••••••••">
                            </div>

                            <button type="submit"
                                    class="w-full rounded-full py-4 text-[10px] font-black uppercase tracking-[.3em] transition-opacity hover:opacity-80"
                                    style="background: var(--primary); color: var(--white);">
                                Sign In
                            </button>
                        </form>

                        {{-- Register link --}}
                        <div class="mt-8 pt-7 text-center" style="border-top: 1px solid var(--light-gray);">
                            <p class="text-[10px] uppercase tracking-[.2em]" style="color: var(--olive-tint);">
                                New here?
                                <a href="{{ route('register') }}"
                                   class="ml-1 font-black transition-opacity hover:opacity-60"
                                   style="color: var(--primary); border-bottom: 1.5px solid var(--primary);">
                                    Create Account
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            @endguest

        </div>

        {{-- Auto-open login modal on validation error --}}
        @if ($errors->has('email') || $errors->has('password'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                window.dispatchEvent(new CustomEvent('open-login'));
            });
        </script>
        @endif

    </body>
</html>