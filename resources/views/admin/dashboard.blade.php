<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center font-helvetica">
            <h2 class="font-bold text-xs text-brand-primary tracking-widest uppercase">
                {{ __('Admin Dashboard') }}
            </h2>
            <div class="flex items-center gap-2">
                <span class="relative flex h-2 w-2">
                </span>
            </div>
        </div>
    </x-slot>

    <div class="bg-brand-light/20 min-h-screen pb-20 font-helvetica pt-6">
        {{-- Hero Background --}}
        <div class="bg-brand-primary pt-16 pb-32 px-4 sm:px-6 lg:px-8 rounded-b-[3rem] relative overflow-hidden mx-4 sm:mx-6 lg:mx-8">
            <div class="absolute top-0 right-0 w-[40rem] h-[40rem] bg-brand-white opacity-[0.02] rounded-full blur-3xl -translate-y-1/2 translate-x-1/3"></div>
            <div class="max-w-7xl mx-auto relative z-10">
                <div class="max-w-2xl">
                    <h3 class="text-4xl sm:text-5xl font-light text-brand-white tracking-wide leading-tight">Dashboard<br><span class="opacity-80">Overview.</span></h3>
                    <p class="text-[10px] uppercase tracking-[0.3em] text-brand-white/60 mt-6 font-medium leading-relaxed">Farhana Inventory Control & Management</p>
                    <div class="h-px w-16 bg-brand-white/20 mt-8"></div>
                </div>
            </div>
        </div>

        {{-- Main Navigation Grid --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-20 relative z-10">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-6 lg:gap-8">

                {{-- KELOLA PESANAN --}}
<a href="{{ route('admin.orders.index') }}" class="group bg-brand-black p-8 rounded-[2rem] transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl hover:shadow-brand-black/20 flex flex-col justify-between min-h-[220px]">
    <div class="w-14 h-14 bg-brand-white/10 rounded-2xl flex items-center justify-center transition-colors duration-500 group-hover:bg-brand-white group-hover:text-brand-black">
        <svg class="w-6 h-6 text-brand-white group-hover:text-brand-black transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
        </svg>
    </div>
    <div>
        <p class="text-brand-white text-[11px] font-bold uppercase tracking-[0.2em] mb-2">Kelola Pesanan</p>
        <p class="text-brand-white/50 text-[9px] uppercase tracking-widest">Cek Transaksi</p>
    </div>
</a>

{{-- 1. KELOLA KATEGORI --}}
<a href="{{ route('admin.categories.index') }}" class="group bg-brand-white p-8 rounded-[2rem] shadow-sm border border-brand-light/50 transition-all duration-500 hover:-translate-y-2 hover:shadow-xl hover:shadow-brand-primary/5 flex flex-col justify-between min-h-[220px]">
    <div class="w-14 h-14 bg-brand-light rounded-2xl flex items-center justify-center transition-colors duration-500 group-hover:bg-brand-primary group-hover:text-brand-white">
        <svg class="w-6 h-6 text-brand-black group-hover:text-brand-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
        </svg>
    </div>
    <div>
        <p class="text-brand-black text-[11px] font-bold uppercase tracking-[0.2em] mb-2">Kelola Kategori</p>
        <p class="text-brand-olive text-[9px] uppercase tracking-widest">Pengaturan Menu</p>
    </div>
</a>

{{-- 2. SIZE GUIDES --}}
<a href="{{ route('admin.size-guides.index') }}" class="group bg-brand-white p-8 rounded-[2rem] shadow-sm border border-brand-light/50 transition-all duration-500 hover:-translate-y-2 hover:shadow-xl hover:shadow-brand-primary/5 flex flex-col justify-between min-h-[220px]">
    <div class="w-14 h-14 bg-brand-light rounded-2xl flex items-center justify-center transition-colors duration-500 group-hover:bg-brand-olive group-hover:text-brand-white">
        <svg class="w-6 h-6 text-brand-black group-hover:text-brand-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
        </svg>
    </div>
    <div>
        <p class="text-brand-black text-[11px] font-bold uppercase tracking-[0.2em] mb-2">Size Guides</p>
        <p class="text-brand-olive text-[9px] uppercase tracking-widest">Master Tabel Ukuran</p>
    </div>
</a>

{{-- 3. DAFTAR PRODUK --}}
<a href="{{ route('admin.products.index') }}" class="group bg-brand-white p-8 rounded-[2rem] shadow-sm border border-brand-light/50 transition-all duration-500 hover:-translate-y-2 hover:shadow-xl hover:shadow-brand-primary/5 flex flex-col justify-between min-h-[220px]">
    <div class="w-14 h-14 bg-brand-light rounded-2xl flex items-center justify-center transition-colors duration-500 group-hover:bg-brand-primary group-hover:text-brand-white">
        <svg class="w-6 h-6 text-brand-black group-hover:text-brand-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
        </svg>
    </div>
    <div>
        <p class="text-brand-black text-[11px] font-bold uppercase tracking-[0.2em] mb-2">Daftar Produk</p>
        <p class="text-brand-olive text-[9px] uppercase tracking-widest">Update Stock & Harga</p>
    </div>
</a>

{{-- 4. BANNER PROMO --}}
<a href="{{ route('admin.sliders.index') }}" class="group bg-brand-white p-8 rounded-[2rem] shadow-sm border border-brand-light/50 transition-all duration-500 hover:-translate-y-2 hover:shadow-xl hover:shadow-brand-primary/5 flex flex-col justify-between min-h-[220px]">
    <div class="w-14 h-14 bg-brand-light rounded-2xl flex items-center justify-center transition-colors duration-500 group-hover:bg-brand-primary group-hover:text-brand-white">
        <svg class="w-6 h-6 text-brand-black group-hover:text-brand-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
        </svg>
    </div>
    <div>
        <p class="text-brand-black text-[11px] font-bold uppercase tracking-[0.2em] mb-2">Banner Promo</p>
        <p class="text-brand-olive text-[9px] uppercase tracking-widest">Visual Marketing</p>
    </div>
</a>

            </div>

            {{-- Quick Actions & External Links --}}
            <div class="mt-12 flex flex-col lg:flex-row items-center justify-between p-8 bg-brand-white rounded-[2.5rem] border border-brand-light/50 shadow-sm gap-8">
                <div class="flex flex-wrap justify-center gap-8">
                    <a href="{{ route('admin.products.create') }}" class="group flex items-center gap-3">
                        <span class="w-8 h-8 bg-brand-light rounded-full flex items-center justify-center text-brand-black text-xs font-light group-hover:bg-brand-primary group-hover:text-brand-white transition-all duration-300">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        </span>
                        <span class="text-[10px] text-brand-black font-bold uppercase tracking-widest opacity-70 group-hover:opacity-100 transition-opacity">Tambah Produk</span>
                    </a>
                    <a href="{{ route('admin.categories.create') }}" class="group flex items-center gap-3">
                        <span class="w-8 h-8 bg-brand-light rounded-full flex items-center justify-center text-brand-black text-xs font-light group-hover:bg-brand-primary group-hover:text-brand-white transition-all duration-300">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        </span>
                        <span class="text-[10px] text-brand-black font-bold uppercase tracking-widest opacity-70 group-hover:opacity-100 transition-opacity">Kategori Baru</span>
                    </a>
                    <a href="{{ route('admin.size-guides.create') }}" class="group flex items-center gap-3">
                        <span class="w-8 h-8 bg-brand-light rounded-full flex items-center justify-center text-brand-black text-xs font-light group-hover:bg-brand-olive group-hover:text-brand-white transition-all duration-300">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        </span>
                        <span class="text-[10px] text-brand-black font-bold uppercase tracking-widest opacity-70 group-hover:opacity-100 transition-opacity">Template Size</span>
                    </a>
                </div>

                <a href="{{ route('home') }}" target="_blank" class="px-8 py-4 bg-[#2F3526] rounded-full text-[10px] font-bold text-white uppercase tracking-widest hover:bg-[#6B705C] transition-all duration-300 flex items-center gap-3 shadow-lg shadow-[#2F3526]/20 hover:-translate-y-1">
                    Buka Website Utama
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
