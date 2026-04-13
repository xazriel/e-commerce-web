<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100">
                <div class="p-8 text-gray-900">
                    <h3 class="text-lg font-bold mb-2">Selamat Datang, Admin!</h3>
                    <p class="mb-10 text-[12px] uppercase tracking-widest text-gray-400">Panel Kontrol Inventaris & Transaksi Farhana</p>
                    
                    {{-- Grid 2x2 untuk 4 menu utama --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        
                        {{-- KELOLA PESANAN (Terbaru) --}}
                        <a href="{{ route('admin.orders.index') }}" class="flex flex-col items-center p-8 bg-black text-white rounded-2xl shadow-lg hover:bg-zinc-800 transition duration-300 group">
                            <div class="p-4 bg-zinc-800 rounded-xl mb-4 group-hover:scale-110 transition duration-300">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                </svg>
                            </div>
                            <span class="font-bold text-[11px] uppercase tracking-[0.2em]">Kelola Pesanan</span>
                            <span class="text-[9px] text-zinc-500 mt-2 tracking-widest italic">Cek Transaksi & Resi</span>
                        </a>

                        {{-- DAFTAR PRODUK --}}
                        <a href="{{ route('products.index') }}" class="flex flex-col items-center p-8 bg-white border border-gray-100 rounded-2xl shadow-sm hover:border-black transition duration-300 group">
                            <div class="p-4 bg-blue-50 rounded-xl mb-4 group-hover:scale-110 transition duration-300">
                                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                            </div>
                            <span class="font-bold text-[11px] uppercase tracking-[0.2em] text-gray-700">Daftar Produk</span>
                        </a>

                        {{-- KELOLA KATEGORI --}}
                        <a href="{{ route('categories.index') }}" class="flex flex-col items-center p-8 bg-white border border-gray-100 rounded-2xl shadow-sm hover:border-black transition duration-300 group">
                            <div class="p-4 bg-purple-50 rounded-xl mb-4 group-hover:scale-110 transition duration-300">
                                <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                                </svg>
                            </div>
                            <span class="font-bold text-[11px] uppercase tracking-[0.2em] text-gray-700">Kelola Kategori</span>
                        </a>

                        {{-- BANNER PROMO --}}
                        <a href="{{ route('sliders.index') }}" class="flex flex-col items-center p-8 bg-white border border-gray-100 rounded-2xl shadow-sm hover:border-black transition duration-300 group">
                            <div class="p-4 bg-orange-50 rounded-xl mb-4 group-hover:scale-110 transition duration-300">
                                <svg class="w-8 h-8 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <span class="font-bold text-[11px] uppercase tracking-[0.2em] text-gray-700">Banner Promo</span>
                        </a>

                    </div>

                    {{-- Footer Links --}}
                    <div class="mt-12 pt-6 border-t border-gray-50 flex flex-col md:flex-row justify-between gap-4">
                         <div class="flex gap-6">
                             <a href="{{ route('products.create') }}" class="text-[10px] text-gray-400 hover:text-black uppercase tracking-widest transition">
                                + Tambah Produk Cepat
                             </a>
                             <a href="{{ route('categories.create') }}" class="text-[10px] text-gray-400 hover:text-black uppercase tracking-widest transition">
                                + Kategori Baru
                             </a>
                         </div>
                         <a href="{{ route('home') }}" target="_blank" class="text-[10px] text-gray-400 hover:text-black uppercase tracking-widest flex items-center gap-2">
                            Lihat Web Depan 
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                         </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>