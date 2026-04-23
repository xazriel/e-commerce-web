<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                {{-- LOGO --}}
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="font-bold text-xl tracking-widest uppercase italic">
                        Farhana
                    </a>
                </div>

                {{-- NAV LINKS (DESKTOP) --}}
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('home')" :active="request()->routeIs('home')">
                        {{ __('HOME') }}
                    </x-nav-link>

                    @auth
                        @if(Auth::user()->role === 'admin')
                            <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                                {{ __('DASHBOARD ADMIN') }}
                            </x-nav-link>
                            <x-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')">
                                {{ __('KATALOG PRODUK') }}
                            </x-nav-link>
                            <x-nav-link :href="route('categories.index')" :active="request()->routeIs('categories.*')">
                                {{ __('KATEGORI') }}
                            </x-nav-link>
                        @endif
                    @endauth
                </div>
            </div>

            {{-- RIGHT SIDE --}}
            <div class="hidden sm:flex sm:items-center sm:ms-6 space-x-6">
                
                <x-cart-count />

                @auth
                    {{-- DROPDOWN USER --}}
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                <div class="flex items-center tracking-widest uppercase text-[11px] font-bold">
                                    @if(Auth::user()->role === 'admin')
                                        <span class="bg-black text-white text-[8px] px-2 py-0.5 rounded-full mr-2 tracking-tighter">ADMIN</span>
                                    @endif
                                    {{ Auth::user()->name }}
                                </div>

                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('dashboard')">
                                {{ __('My Account') }}
                            </x-dropdown-link>

                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Settings') }}
                            </x-dropdown-link>

                            <hr class="border-gray-100">

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                    <span class="text-red-500">{{ __('Log Out') }}</span>
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    {{-- TOMBOL LOGIN/REGISTER UNTUK GUEST --}}
                    <div class="flex items-center space-x-6">
                       <button @click="window.dispatchEvent(new CustomEvent('open-login'))" class="...">
                        Login
                    </button>
                        <a href="{{ route('register') }}" class="bg-black text-white px-5 py-2 text-[10px] font-bold uppercase tracking-[0.2em] hover:bg-[#5A5A00] transition">
                            Register
                        </a>
                    </div>
                @endauth
            </div>

            {{-- HAMBURGER (MOBILE) --}}
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- RESPONSIVE MENU (MOBILE) --}}
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white border-t border-gray-50">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')">
                {{ __('HOME') }}
            </x-responsive-nav-link>
        </div>

        <div class="pt-4 pb-1 border-t border-gray-100">
            @auth
                <div class="px-4 flex justify-between items-center mb-4">
                    <div>
                        <div class="font-bold text-sm text-gray-800 uppercase tracking-widest">{{ Auth::user()->name }}</div>
                        <div class="font-medium text-xs text-gray-500">{{ Auth::user()->email }}</div>
                    </div>
                    <x-cart-count />
                </div>

                <div class="space-y-1">
                    <x-responsive-nav-link :href="route('dashboard')">{{ __('My Account') }}</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('profile.edit')">{{ __('Profile Settings') }}</x-responsive-nav-link>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="text-red-500">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            @else
                <div class="p-4 space-y-3">
                    <button @click="open = false; $dispatch('open-login')" class="block w-full text-center bg-black text-white py-3 text-[10px] font-bold uppercase tracking-widest">
                        Login
                    </button>
                    <a href="{{ route('register') }}" class="block w-full text-center border border-black py-3 text-[10px] font-bold uppercase tracking-widest">
                        Register
                    </a>
                </div>
            @endauth
        </div>
    </div>
</nav>