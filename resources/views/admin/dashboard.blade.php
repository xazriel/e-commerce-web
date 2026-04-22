<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight tracking-tight">
                {{ __('Admin Dashboard') }}
            </h2>
            <div class="flex items-center gap-2">
                <span class="relative flex h-2 w-2">
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Welcome Section --}}
            <div class="mb-8 px-4 sm:px-0">
                <h3 class="text-2xl font-bold text-gray-900">Selamat Datang, Admin</h3>
                <p class="text-[11px] uppercase tracking-[0.3em] text-gray-400 mt-1">Farhana Inventory Control & Management</p>
                <div class="h-1 w-12 bg-black mt-4"></div>
            </div>

            {{-- Main Navigation Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                
                {{-- KELOLA PESANAN --}}
                <a href="{{ route('admin.orders.index') }}" class="group relative overflow-hidden bg-black p-8 rounded-2xl transition-all duration-300 hover:shadow-2xl hover:shadow-gray-300 active:scale-95">
                    <div class="relative z-10">
                        <div class="w-12 h-12 bg-white/10 rounded-xl flex items-center justify-center mb-6 group-hover:bg-white group-hover:text-black transition-colors duration-300">
                            <svg class="w-6 h-6 text-white group-hover:text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                        </div>
                        <p class="text-white text-[11px] font-bold uppercase tracking-[0.2em]">Kelola Pesanan</p>
                        <p class="text-zinc-500 text-[9px] uppercase mt-1 tracking-widest italic">Cek Transaksi & Resi</p>
                    </div>
                    <div class="absolute -right-4 -bottom-4 text-white opacity-[0.03] group-hover:opacity-[0.07] transition-opacity">
                        <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24"><path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    </div>
                </a>

                {{-- DAFTAR PRODUK --}}
                <a href="{{ route('products.index') }}" class="group bg-white border border-gray-100 p-8 rounded-2xl transition-all duration-300 hover:border-black hover:shadow-xl hover:shadow-gray-100 active:scale-95">
                    <div class="w-12 h-12 bg-gray-50 rounded-xl flex items-center justify-center mb-6 group-hover:bg-black transition-colors duration-300">
                        <svg class="w-6 h-6 text-gray-400 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <p class="text-gray-900 text-[11px] font-bold uppercase tracking-[0.2em]">Daftar Produk</p>
                    <p class="text-gray-400 text-[9px] uppercase mt-1 tracking-widest">Update Stock & Harga</p>
                </a>

                {{-- KELOLA KATEGORI --}}
                <a href="{{ route('categories.index') }}" class="group bg-white border border-gray-100 p-8 rounded-2xl transition-all duration-300 hover:border-black hover:shadow-xl hover:shadow-gray-100 active:scale-95">
                    <div class="w-12 h-12 bg-gray-50 rounded-xl flex items-center justify-center mb-6 group-hover:bg-black transition-colors duration-300">
                        <svg class="w-6 h-6 text-gray-400 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                        </svg>
                    </div>
                    <p class="text-gray-900 text-[11px] font-bold uppercase tracking-[0.2em]">Kelola Kategori</p>
                    <p class="text-gray-400 text-[9px] uppercase mt-1 tracking-widest">Pengaturan Menu</p>
                </a>

                {{-- SIZE GUIDE (MENU BARU) --}}
                <a href="{{ route('size-guides.index') }}" class="group bg-white border border-gray-100 p-8 rounded-2xl transition-all duration-300 hover:border-[#5A5A00] hover:shadow-xl hover:shadow-gray-100 active:scale-95">
                    <div class="w-12 h-12 bg-gray-50 rounded-xl flex items-center justify-center mb-6 group-hover:bg-[#5A5A00] transition-colors duration-300">
                        <svg class="w-6 h-6 text-gray-400 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <p class="text-gray-900 text-[11px] font-bold uppercase tracking-[0.2em]">Size Guides</p>
                    <p class="text-gray-400 text-[9px] uppercase mt-1 tracking-widest">Master Tabel Ukuran</p>
                </a>

                {{-- BANNER PROMO --}}
                <a href="{{ route('sliders.index') }}" class="group bg-white border border-gray-100 p-8 rounded-2xl transition-all duration-300 hover:border-black hover:shadow-xl hover:shadow-gray-100 active:scale-95">
                    <div class="w-12 h-12 bg-gray-50 rounded-xl flex items-center justify-center mb-6 group-hover:bg-black transition-colors duration-300">
                        <svg class="w-6 h-6 text-gray-400 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <p class="text-gray-900 text-[11px] font-bold uppercase tracking-[0.2em]">Banner Promo</p>
                    <p class="text-gray-400 text-[9px] uppercase mt-1 tracking-widest">Visual Marketing</p>
                </a>

            </div>

            {{-- Quick Actions & External Links --}}
            <div class="mt-12 flex flex-col md:flex-row items-center justify-between p-6 bg-gray-50/50 rounded-2xl border border-dashed border-gray-200 gap-6">
                <div class="flex flex-wrap justify-center gap-8">
                    <a href="{{ route('products.create') }}" class="group flex items-center gap-2">
                        <span class="w-6 h-6 bg-white border border-gray-200 rounded-full flex items-center justify-center text-[10px] group-hover:bg-black group-hover:text-white transition-all">+</span>
                        <span class="text-[10px] text-gray-500 font-bold uppercase tracking-widest group-hover:text-black">Tambah Produk</span>
                    </a>
                    <a href="{{ route('categories.create') }}" class="group flex items-center gap-2">
                        <span class="w-6 h-6 bg-white border border-gray-100 rounded-full flex items-center justify-center text-[10px] group-hover:bg-black group-hover:text-white transition-all">+</span>
                        <span class="text-[10px] text-gray-500 font-bold uppercase tracking-widest group-hover:text-black">Kategori Baru</span>
                    </a>
                    {{-- QUICK LINK SIZE GUIDE --}}
                    <a href="{{ route('size-guides.create') }}" class="group flex items-center gap-2">
                        <span class="w-6 h-6 bg-white border border-[#5A5A00]/20 rounded-full flex items-center justify-center text-[10px] group-hover:bg-[#5A5A00] group-hover:text-white transition-all">+</span>
                        <span class="text-[10px] text-gray-500 font-bold uppercase tracking-widest group-hover:text-[#5A5A00]">Template Size</span>
                    </a>
                </div>
                
                <a href="{{ route('home') }}" target="_blank" class="px-6 py-2 bg-white border border-gray-200 rounded-full text-[10px] font-bold text-gray-400 uppercase tracking-widest hover:border-black hover:text-black transition-all flex items-center gap-2">
                    Buka Website Utama
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>