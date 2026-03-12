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
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-bold">Produk Toko Anda</h3>
                    <a href="{{ route('products.create') }}" class="bg-black text-white px-4 py-2 rounded-md text-sm hover:bg-gray-800">
                        + Tambah Produk Baru
                    </a>
                </div>

                <div class="overflow-x-auto border rounded-lg">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b bg-gray-50">
                                <th class="px-4 py-3 text-sm font-semibold text-gray-700">Foto</th>
                                <th class="px-4 py-3 text-sm font-semibold text-gray-700">Nama Produk</th>
                                <th class="px-4 py-3 text-sm font-semibold text-gray-700">Kategori</th>
                                <th class="px-4 py-3 text-sm font-semibold text-gray-700">Harga</th>
                                <th class="px-4 py-3 text-sm font-semibold text-gray-700">Stok</th>
                                <th class="px-4 py-3 text-sm font-semibold text-gray-700 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($products as $product)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-4 py-3">
                                        @if($product->images->where('is_primary', true)->first())
                                            <img src="{{ asset('storage/' . $product->images->where('is_primary', true)->first()->image_path) }}" 
                                                 alt="{{ $product->name }}" 
                                                 class="w-16 h-16 object-cover rounded-md shadow-sm">
                                        @else
                                            <div class="w-16 h-16 bg-gray-200 flex items-center justify-center rounded-md text-xs text-gray-400">No Image</div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-sm font-bold text-gray-900">{{ $product->name }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-600">{{ $product->category->name ?? 'Tanpa Kategori' }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-600">{{ $product->stock }}</td>
                                    <td class="px-4 py-3 text-sm text-right">
                                        <div class="flex justify-end gap-2">
                                            <a href="{{ route('products.edit', $product->id) }}" class="text-indigo-600 hover:underline">Edit</a>
                                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Hapus produk ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-10 text-center text-gray-500">
                                        Belum ada produk. Silakan tambahkan produk pertama Anda.
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