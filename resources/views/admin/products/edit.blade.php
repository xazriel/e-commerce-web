<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 tracking-tight italic uppercase">Edit Produk: {{ $product->name }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-8 shadow-sm rounded-lg border border-gray-100">
                
                <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-widest text-gray-500 mb-2">Nama Produk</label>
                            <input type="text" name="name" value="{{ $product->name }}" class="block w-full border-gray-200 rounded-sm focus:border-black focus:ring-0 transition" required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-widest text-gray-500 mb-2">Kategori</label>
                            <select name="category_id" class="block w-full border-gray-200 rounded-sm focus:border-black focus:ring-0 transition">
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ $product->category_id == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-widest text-gray-500 mb-2">Harga (Rp)</label>
                            <input type="number" name="price" value="{{ $product->price }}" class="block w-full border-gray-200 rounded-sm focus:border-black focus:ring-0 transition" required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-widest text-gray-500 mb-2">Stok</label>
                            <input type="number" name="stock" value="{{ $product->stock }}" class="block w-full border-gray-200 rounded-sm focus:border-black focus:ring-0 transition" required>
                        </div>
                    </div>

                    <div class="mb-8">
                        <label class="block text-xs font-bold uppercase tracking-widest text-gray-500 mb-2">Deskripsi Produk</label>
                        <textarea name="description" rows="5" class="block w-full border-gray-200 rounded-sm focus:border-black focus:ring-0 transition">{{ $product->description }}</textarea>
                    </div>

                    <div class="mb-10">
                        <label class="block text-xs font-bold uppercase tracking-widest text-gray-900 mb-4 pb-2 border-b">Manajemen Gambar</label>
                        
                        <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
                            @foreach($product->images as $img)
                                <div class="relative group border p-2 rounded-sm {{ $img->is_primary ? 'border-black ring-1 ring-black' : 'border-gray-100' }}">
                                    <img src="{{ asset('storage/' . $img->image_path) }}" class="w-full h-48 object-cover rounded-sm mb-2">
                                    
                                    <div class="flex gap-1">
                                        @if(!$img->is_primary)
                                            <button type="button" 
                                                onclick="event.preventDefault(); document.getElementById('set-primary-{{ $img->id }}').submit();"
                                                class="flex-1 bg-gray-100 text-[10px] font-bold uppercase py-1 hover:bg-black hover:text-white transition">
                                                Set Main
                                            </button>
                                        @else
                                            <span class="flex-1 bg-black text-white text-[10px] font-bold uppercase py-1 text-center italic">Primary</span>
                                        @endif

                                        <button type="button" 
                                            onclick="event.preventDefault(); if(confirm('Hapus foto ini?')) document.getElementById('delete-img-{{ $img->id }}').submit();"
                                            class="bg-red-500 text-white px-2 py-1 hover:bg-red-700 transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="bg-gray-50 p-6 border-2 border-dashed border-gray-200 rounded-sm">
                            <label class="block text-xs font-bold uppercase tracking-widest text-gray-700 mb-2">Tambah Foto Baru</label>
                            <input type="file" name="images[]" multiple class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-black file:text-white hover:file:bg-gray-800 cursor-pointer">
                            <p class="text-[10px] text-gray-400 mt-2 italic uppercase tracking-tighter">*Pilih beberapa file sekaligus jika perlu.</p>
                        </div>
                    </div>

                    <div class="flex justify-end items-center gap-4 border-t pt-8">
                        <a href="{{ route('products.index') }}" class="text-xs font-bold uppercase tracking-widest text-gray-400 hover:text-black transition">Batal</a>
                        <button type="submit" class="bg-black text-white px-10 py-3 text-xs font-bold uppercase tracking-[0.2em] shadow-lg hover:bg-gray-800 transition">
                            Update Produk & Simpan Foto
                        </button>
                    </div>
                </form>

                @foreach($product->images as $img)
                    <form id="set-primary-{{ $img->id }}" action="{{ route('products.images.setPrimary', $img->id) }}" method="POST" class="hidden">
                        @csrf
                        @method('PATCH')
                    </form>
                    <form id="delete-img-{{ $img->id }}" action="{{ route('products.images.destroy', $img->id) }}" method="POST" class="hidden">
                        @csrf
                        @method('DELETE')
                    </form>
                @endforeach

            </div>
        </div>
    </div>
</x-app-layout>