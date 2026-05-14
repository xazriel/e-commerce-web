<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 w-full">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16 relative">

            {{-- Spacer kiri untuk keseimbangan layout di mobile --}}
            <div class="w-8 sm:hidden"></div>

            {{-- LOGO TENGAH DI MOBILE, KIRI DI DESKTOP --}}
            <div class="absolute left-1/2 -translate-x-1/2 sm:static sm:translate-x-0 shrink-0 flex items-center">
                <a href="{{ route('home') }}">
                    <img src="{{ Storage::url('LOGO-FARHANA-NEW-TRANSPARENT.png') }}"
                         alt="Farhana"
                         class="h-12 sm:h-20 w-auto object-contain">
                </a>
            </div>

            {{-- KANAN: KERANJANG + USER --}}
            <div class="hidden sm:flex sm:items-center space-x-5">

                <x-cart-count />

                @auth
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center gap-2 px-3 py-2 text-[10px] font-bold tracking-widest uppercase text-gray-600 hover:text-black focus:outline-none transition">
                                @if(Auth::user()->role === 'admin')
                                    <span class="bg-black text-white text-[7px] px-2 py-0.5 rounded-full tracking-tighter">ADMIN</span>
                                @endif
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                {{ Auth::user()->name }}
                                <svg class="h-3 w-3 fill-current text-gray-400" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            @if(Auth::user()->role === 'admin')
                                <x-dropdown-link :href="route('admin.dashboard')">{{ __('Dashboard Admin') }}</x-dropdown-link>
                                <x-dropdown-link :href="route('admin.products.index')">{{ __('Katalog Produk') }}</x-dropdown-link>
                                <x-dropdown-link :href="route('admin.categories.index')">{{ __('Kategori') }}</x-dropdown-link>
                                <x-dropdown-link :href="route('admin.orders.index')">{{ __('Kelola Order') }}</x-dropdown-link>
                                <hr class="border-gray-100">
                            @endif
                            <x-dropdown-link :href="route('dashboard')">{{ __('My Account') }}</x-dropdown-link>
                            <x-dropdown-link :href="route('profile.edit')">{{ __('Settings') }}</x-dropdown-link>
                            <hr class="border-gray-100">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                    <span class="text-red-500">{{ __('Log Out') }}</span>
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <button @click="window.dispatchEvent(new CustomEvent('open-login'))"
                        class="text-[10px] font-bold uppercase tracking-[0.2em] text-gray-600 hover:text-black transition">
                        Login
                    </button>
                    <a href="{{ route('register') }}"
                        class="bg-black text-white px-5 py-2 text-[10px] font-bold uppercase tracking-[0.2em] hover:bg-[#2F3526] transition">
                        Register
                    </a>
                @endauth
            </div>

            {{-- HAMBURGER (MOBILE) --}}
            @php
                $showAdminDrawer = auth()->check() && auth()->user()->role === 'admin' && !request()->routeIs('admin.dashboard');
            @endphp
            @if(!$showAdminDrawer)
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': !open}" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        <path :class="{'hidden': !open, 'inline-flex': open}" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            @else
            <div class="-me-2 flex items-center sm:hidden w-10"></div>
            @endif
        </div>
    </div>

    {{-- MOBILE MENU --}}
    @if(!$showAdminDrawer)
    <div :class="{'block': open, 'hidden': !open}" class="hidden sm:hidden bg-white border-t border-gray-100">
        <div class="pt-4 pb-3">
            @auth
                <div class="px-4 flex justify-between items-center mb-4">
                    <div>
                        <div class="font-bold text-sm text-gray-800 uppercase tracking-widest">{{ Auth::user()->name }}</div>
                        <div class="text-xs text-gray-500">{{ Auth::user()->email }}</div>
                    </div>
                    <x-cart-count />
                </div>
                <div class="space-y-1">
                    @if(Auth::user()->role === 'admin')
                        <x-responsive-nav-link :href="route('admin.dashboard')">{{ __('Dashboard Admin') }}</x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('admin.products.index')">{{ __('Katalog Produk') }}</x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('admin.categories.index')">{{ __('Kategori') }}</x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('admin.orders.index')">{{ __('Kelola Order') }}</x-responsive-nav-link>
                    @endif
                    <x-responsive-nav-link :href="route('dashboard')">{{ __('My Account') }}</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('profile.edit')">{{ __('Settings') }}</x-responsive-nav-link>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();"
                            class="text-red-500">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            @else
                <div class="p-4 space-y-3">
                    <button @click="open = false; $dispatch('open-login')"
                        class="block w-full text-center bg-black text-white py-3 text-[10px] font-bold uppercase tracking-widest">
                        Login
                    </button>
                    <a href="{{ route('register') }}"
                        class="block w-full text-center border border-black py-3 text-[10px] font-bold uppercase tracking-widest">
                        Register
                    </a>
                </div>
            @endauth
        </div>
    </div>
    @endif
</nav>  