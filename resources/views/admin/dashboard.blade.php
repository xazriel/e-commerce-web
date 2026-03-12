<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-4">Selamat Datang, Admin!</h3>
                    <p class="mb-6 text-gray-600">Gunakan menu di bawah untuk mengelola konten dan inventaris toko Farhana:</p>
                    
                    {{-- Kita ubah grid menjadi 3 kolom di desktop agar slider punya tempat sendiri --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        
                        <a href="{{ route('products.index') }}" class="flex flex-col items-center p-6 bg-white border border-gray-200 rounded-xl shadow-sm hover:bg-gray-50 hover:border-black transition duration-300">
                            <div class="p-3 bg-blue-50 rounded-full mb-4">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                            </div>
                            <span class="font-bold text-sm uppercase tracking-widest">Daftar Produk</span>
                        </a>

                        <a href="{{ route('categories.index') }}" class="flex flex-col items-center p-6 bg-white border border-gray-200 rounded-xl shadow-sm hover:bg-gray-50 hover:border-black transition duration-300">
                            <div class="p-3 bg-purple-50 rounded-full mb-4">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                            </div>
                            <span class="font-bold text-sm uppercase tracking-widest">Kelola Kategori</span>
                        </a>

                        <a href="{{ route('sliders.index') }}" class="flex flex-col items-center p-6 bg-black text-white rounded-xl shadow-sm hover:bg-gray-800 transition duration-300">
                            <div class="p-3 bg-gray-800 rounded-full mb-4">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            <span class="font-bold text-sm uppercase tracking-widest">Banner Promo</span>
                        </a>

                    </div>

                    <div class="mt-10 pt-6 border-t border-gray-100 grid grid-cols-1 md:grid-cols-2 gap-4">
                         <a href="{{ route('products.create') }}" class="text-xs text-gray-500 hover:text-black uppercase tracking-tighter">
                            + Tambah Produk Cepat
                         </a>
                         <a href="{{ route('home') }}" target="_blank" class="text-xs text-gray-500 hover:text-black uppercase tracking-tighter md:text-right">
                            Lihat Web Depan →
                         </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>