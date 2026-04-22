<x-app-layout>
    <x-slot name="header">
        {{-- Header Responsive --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:justify-between sm:items-center">
            <div>
                <h2 class="font-semibold text-lg md:text-xl text-gray-800 leading-tight tracking-tight">
                    {{ __('Daftar Produk') }}
                </h2>
                <p class="text-[9px] text-gray-400 uppercase tracking-widest mt-1 hidden sm:block">
                    Admin Panel / Inventory Management
                </p>
            </div>

            <div class="flex items-center">
                <a href="{{ route('admin.dashboard') }}" 
                   class="inline-flex items-center text-[10px] font-bold text-gray-400 uppercase tracking-widest hover:text-black transition-all group">
                    <span class="mr-2 transform group-hover:-translate-x-1 transition-transform">&larr;</span>
                    <span class="underline decoration-gray-200 underline-offset-4 group-hover:decoration-black">Kembali ke Dashboard</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Alert Section --}}
            @if(session('success'))
                <div class="mb-6 bg-black text-white px-6 py-4 rounded-xl shadow-lg flex justify-between items-center animate-fade-in-down">
                    <div class="flex items-center gap-3">
                        <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span class="text-[10px] font-bold uppercase tracking-[0.2em]">{{ session('success') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-white hover:text-gray-400 transition">&times;</button>
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-2xl overflow-hidden border border-gray-100">
                
                {{-- Action Bar --}}
                <div class="p-8 border-b border-gray-50 flex flex-col gap-6 md:flex-row md:justify-between md:items-center bg-white">
                    <div>
                        <h3 class="text-sm font-bold text-gray-900 uppercase tracking-widest">Katalog Produk</h3>
                        <p class="text-[10px] text-gray-400 uppercase tracking-widest mt-1">Kelola stok, varian, dan foto produk.</p>
                    </div>
                    
                    <a href="{{ route('products.create') }}" 
                       class="inline-flex justify-center items-center bg-black text-white px-6 py-3 rounded-xl text-[10px] font-bold uppercase tracking-[0.2em] hover:bg-gray-800 transition shadow-xl shadow-gray-200 active:scale-95">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Produk Baru
                    </a>
                </div>

                {{-- Table Section --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse min-w-[800px]">
                        <thead>
                            <tr class="bg-gray-50/50">
                                <th class="px-8 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Info Produk</th>
                                <th class="px-6 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Kategori</th>
                                <th class="px-6 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Harga</th>
                                <th class="px-6 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Stok</th>
                                <th class="px-8 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($products as $product)
                                <tr class="hover:bg-gray-50/50 transition-all group">
                                    <td class="px-8 py-5">
                                        <div class="flex items-center gap-4">
                                            <div class="relative flex-shrink-0">
                                                @php $primaryImage = $product->images->where('is_primary', true)->first(); @endphp
                                                @if($primaryImage)
                                                    <img src="{{ asset('storage/' . $primaryImage->image_path) }}" 
                                                         alt="{{ $product->name }}" 
                                                         class="w-14 h-14 object-cover rounded-xl shadow-sm border border-gray-100 group-hover:scale-105 transition-transform duration-300">
                                                @else
                                                    <div class="w-14 h-14 bg-gray-50 flex items-center justify-center rounded-xl border border-dashed border-gray-200">
                                                        <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                        </svg>
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <div class="text-sm font-bold text-gray-900 leading-tight">{{ $product->name }}</div>
                                                <div class="text-[9px] text-gray-400 uppercase tracking-widest mt-1">{{ $product->variants->count() }} Varian Tersedia</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5">
                                        <span class="inline-flex text-[10px] font-bold text-gray-500 bg-gray-100 px-3 py-1 rounded-full uppercase tracking-tighter">
                                            {{ $product->category->name ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-5 text-sm font-bold text-gray-900">
                                        Rp {{ number_format($product->price, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-5 text-center">
                                        @php $totalStock = $product->variants->sum('stock'); @endphp
                                        <div class="flex flex-col items-center">
                                            <span class="text-sm font-black {{ $totalStock <= 5 ? 'text-red-500' : 'text-gray-900' }}">
                                                {{ $totalStock }}
                                            </span>
                                            @if($totalStock <= 5)
                                                <span class="text-[8px] text-red-400 uppercase font-bold tracking-tighter">Low Stock</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-8 py-5 text-right">
                                        <div class="flex justify-end items-center gap-3">
                                            <a href="{{ route('products.edit', $product->id) }}" 
                                               class="p-2.5 bg-gray-50 text-gray-400 rounded-xl hover:bg-black hover:text-white transition-all shadow-sm border border-gray-100">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>
                                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Hapus produk {{ $product->name }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2.5 bg-red-50/50 text-red-400 rounded-xl hover:bg-red-500 hover:text-white transition-all border border-red-50">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-24 text-center">
                                        <div class="flex flex-col items-center">
                                            <div class="p-4 bg-gray-50 rounded-full mb-4">
                                                <svg class="w-10 h-10 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                                </svg>
                                            </div>
                                            <p class="text-[10px] text-gray-400 uppercase tracking-[0.3em] font-bold">Katalog Kosong</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Footer Branding --}}
            <div class="mt-12 flex flex-col items-center">
                <div class="h-1 w-12 bg-gray-100 rounded-full"></div>
                <p class="mt-4 text-[8px] text-gray-300 uppercase tracking-[0.6em]">Premium Inventory System</p>
            </div>
        </div>
    </div>

    <style>
        @keyframes fade-in-down {
            0% { opacity: 0; transform: translateY(-8px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-down {
            animation: fade-in-down 0.4s ease-out;
        }
    </style>
</x-app-layout>