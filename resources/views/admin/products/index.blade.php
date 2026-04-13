<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Daftar Produk') }}
            </h2>
            <a href="{{ route('admin.dashboard') }}" class="text-sm text-gray-600 hover:text-black underline">
                &larr; Kembali ke Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-6 bg-black text-white px-6 py-4 rounded-lg shadow-lg flex justify-between items-center animate-fade-in">
                    <span class="text-xs font-bold uppercase tracking-widest">{{ session('success') }}</span>
                    <button onclick="this.parentElement.remove()" class="text-white">&times;</button>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8 border border-gray-100">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 uppercase tracking-tight">Katalog Produk</h3>
                        <p class="text-xs text-gray-500 mt-1">Kelola stok, varian, dan foto produk toko Anda.</p>
                    </div>
                    <a href="{{ route('products.create') }}" class="bg-black text-white px-6 py-3 rounded-lg text-[10px] font-bold uppercase tracking-[0.2em] hover:bg-gray-800 transition shadow-md">
                        + Tambah Produk Baru
                    </a>
                </div>

                <div class="overflow-x-auto border border-gray-100 rounded-xl">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50/50 border-b border-gray-100">
                                <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Produk</th>
                                <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Kategori</th>
                                <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Harga</th>
                                <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Stok Total</th>
                                <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($products as $product)
                                <tr class="hover:bg-gray-50/80 transition group">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-4">
                                            <div class="relative">
                                                @php $primaryImage = $product->images->where('is_primary', true)->first(); @endphp
                                                @if($primaryImage)
                                                    <img src="{{ asset('storage/' . $primaryImage->image_path) }}" 
                                                         alt="{{ $product->name }}" 
                                                         class="w-14 h-14 object-cover rounded-lg shadow-sm border border-gray-100">
                                                @else
                                                    <div class="w-14 h-14 bg-gray-100 flex items-center justify-center rounded-lg border border-dashed border-gray-200">
                                                        <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <div class="text-sm font-bold text-gray-900 mb-0.5">{{ $product->name }}</div>
                                                <div class="text-[10px] text-gray-400 uppercase tracking-tighter">{{ $product->variants->count() }} Varian</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-xs font-medium text-gray-600">
                                        <span class="px-2.5 py-1 bg-gray-100 rounded-full">{{ $product->category->name ?? 'Uncategorized' }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-sm font-bold text-gray-900">
                                        Rp {{ number_format($product->price, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @php $totalStock = $product->variants->sum('stock'); @endphp
                                        <span class="text-sm font-bold {{ $totalStock <= 5 ? 'text-red-500' : 'text-gray-900' }}">
                                            {{ $totalStock }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex justify-end gap-3 opacity-0 group-hover:opacity-100 transition">
                                            <a href="{{ route('products.edit', $product->id) }}" class="p-2 bg-gray-100 text-gray-600 rounded-md hover:bg-black hover:text-white transition shadow-sm">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>
                                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Hapus produk ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2 bg-red-50 text-red-500 rounded-md hover:bg-red-500 hover:text-white transition shadow-sm border border-red-100">
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
                                    <td colspan="5" class="px-6 py-20 text-center">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-12 h-12 text-gray-200 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                            <p class="text-sm text-gray-500 font-medium">Belum ada produk di etalase Anda.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>