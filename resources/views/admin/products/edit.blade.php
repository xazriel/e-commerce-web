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

                    <div class="space-y-10">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[11px] font-bold text-gray-700 uppercase tracking-widest mb-2">Nama Produk</label>
                                <input type="text" name="name" value="{{ $product->name }}" class="w-full border-gray-200 rounded-lg shadow-sm focus:border-black focus:ring-black" required>
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-gray-700 uppercase tracking-widest mb-2">Kategori</label>
                                <select name="category_id" class="w-full border-gray-200 rounded-lg shadow-sm focus:border-black focus:ring-black">
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ $product->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[11px] font-bold text-gray-700 uppercase tracking-widest mb-2">Harga (Rp)</label>
                                <input type="number" name="price" value="{{ $product->price }}" class="w-full border-gray-200 rounded-lg shadow-sm focus:border-black focus:ring-black" required>
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-gray-700 uppercase tracking-widest mb-2">Tags / Label Koleksi</label>
                                <div class="flex flex-wrap gap-3 p-3 border border-gray-100 rounded-lg bg-gray-50/50">
                                    @foreach($tags as $tag)
                                        <label class="inline-flex items-center cursor-pointer group">
                                            <input type="checkbox" name="tags[]" value="{{ $tag->id }}" 
                                                {{ $product->tags->contains($tag->id) ? 'checked' : '' }}
                                                class="rounded border-gray-300 text-black focus:ring-black mr-2 w-4 h-4">
                                            <span class="text-[10px] text-gray-600 uppercase font-bold tracking-tighter">{{ $tag->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="p-6 border border-gray-100 rounded-xl bg-gray-50/30">
                            <div class="flex justify-between items-center mb-6">
                                <label class="block text-[11px] font-bold text-gray-700 uppercase tracking-widest">Stok Per Varian</label>
                                <button type="button" onclick="addVariantRow()" class="text-[10px] bg-black text-white px-4 py-2 rounded uppercase tracking-widest font-bold hover:bg-gray-800 transition">
                                    + Tambah Varian
                                </button>
                            </div>

                            <div id="variant-container" class="space-y-3">
                                @forelse($product->variants as $variant)
                                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 p-4 bg-white border border-gray-100 rounded-lg shadow-sm variant-row">
                                        <div class="md:col-span-2">
                                            <input type="text" name="variant_color[]" value="{{ $variant->color }}" placeholder="Warna" class="w-full text-xs border-gray-200 rounded-md focus:ring-black focus:border-black" required>
                                        </div>
                                        <div>
                                            <select name="variant_size[]" class="w-full text-xs border-gray-200 rounded-md focus:ring-black focus:border-black">
                                                @foreach(['S', 'M', 'L', 'XL', 'All Size'] as $size)
                                                    <option value="{{ $size }}" {{ $variant->size == $size ? 'selected' : '' }}>{{ $size }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="relative">
                                            <input type="number" name="variant_stock[]" value="{{ $variant->stock }}" placeholder="Stok" class="w-full text-xs border-gray-200 rounded-md focus:ring-black focus:border-black" required>
                                            <button type="button" onclick="this.parentElement.parentElement.remove()" class="absolute -right-2 -top-2 bg-red-500 text-white rounded-full w-5 h-5 text-[10px] flex items-center justify-center hover:bg-red-600">×</button>
                                        </div>
                                    </div>
                                @empty
                                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 p-4 bg-white border border-gray-100 rounded-lg shadow-sm variant-row">
                                        <div class="md:col-span-2">
                                            <input type="text" name="variant_color[]" placeholder="Warna" class="w-full text-xs border-gray-200 rounded-md" required>
                                        </div>
                                        <div>
                                            <select name="variant_size[]" class="w-full text-xs border-gray-200 rounded-md">
                                                @foreach(['S', 'M', 'L', 'XL', 'All Size'] as $size)
                                                    <option value="{{ $size }}">{{ $size }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <input type="number" name="variant_stock[]" placeholder="Stok" class="w-full text-xs border-gray-200 rounded-md" required>
                                        </div>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-gray-700 uppercase tracking-widest mb-2">Deskripsi Produk</label>
                            <textarea name="description" rows="5" class="w-full border-gray-200 rounded-lg shadow-sm focus:border-black focus:ring-black">{{ $product->description }}</textarea>
                        </div>

                        <div class="mb-10">
                            <label class="block text-[11px] font-bold text-gray-900 uppercase tracking-widest mb-4 pb-2 border-b">Galeri Foto Produk</label>
                            
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                                @foreach($product->images as $img)
                                    <div class="relative group border p-3 rounded-lg bg-gray-50 {{ $img->is_primary ? 'border-black ring-1 ring-black' : 'border-gray-100' }}">
                                        <img src="{{ asset('storage/' . $img->image_path) }}" class="w-full h-40 object-cover rounded-md mb-3">
                                        
                                        <div class="mb-2">
                                            <span class="text-[9px] uppercase font-bold text-gray-400">Warna: {{ $img->color ?? 'Global' }}</span>
                                        </div>

                                        <div class="flex gap-1">
                                            @if(!$img->is_primary)
                                                <button type="button" 
                                                    onclick="event.preventDefault(); document.getElementById('set-primary-{{ $img->id }}').submit();"
                                                    class="flex-1 bg-white border border-gray-200 text-[9px] font-bold uppercase py-1.5 rounded hover:bg-black hover:text-white transition">
                                                    Main
                                                </button>
                                            @endif
                                            <button type="button" 
                                                onclick="event.preventDefault(); if(confirm('Hapus foto ini?')) document.getElementById('delete-img-{{ $img->id }}').submit();"
                                                class="bg-red-50 text-red-500 px-3 py-1.5 rounded hover:bg-red-500 hover:text-white transition border border-red-100">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="bg-gray-50 p-6 border-2 border-dashed border-gray-200 rounded-xl">
                                <label class="block text-[11px] font-bold text-gray-700 uppercase tracking-widest mb-4">Tambah Foto Baru</label>
                                <div id="new-image-container" class="space-y-4">
                                    <div class="flex flex-col md:flex-row gap-4 p-4 bg-white border border-gray-100 rounded-lg items-center">
                                        <input type="file" name="images[]" class="block w-full text-[10px] text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-bold file:bg-black file:text-white" />
                                        <input type="text" name="image_colors_new[]" placeholder="Warna untuk foto baru ini" class="w-full md:w-1/3 text-xs border-gray-200 rounded-md focus:ring-black">
                                    </div>
                                </div>
                                <button type="button" onclick="addNewImageRow()" class="mt-4 text-[10px] text-black underline uppercase font-bold tracking-widest">Tambah Slot Foto Baru</button>
                            </div>
                        </div>

                        <div class="flex justify-end items-center gap-4 border-t pt-8">
                            <a href="{{ route('products.index') }}" class="text-xs font-bold uppercase tracking-widest text-gray-400 hover:text-black transition">Batal</a>
                            <button type="submit" class="bg-black text-white px-10 py-4 rounded-lg text-xs font-bold uppercase tracking-[0.3em] shadow-xl hover:bg-gray-800 transition">
                                Update Produk & Simpan Perubahan
                            </button>
                        </div>
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

    <script>
        function addVariantRow() {
            const container = document.getElementById('variant-container');
            const newRow = container.querySelector('.variant-row').cloneNode(true);
            newRow.querySelectorAll('input').forEach(input => input.value = '');
            container.appendChild(newRow);
        }

        function addNewImageRow() {
            const container = document.getElementById('new-image-container');
            const newRow = container.querySelector('div').cloneNode(true);
            newRow.querySelector('input[type="file"]').value = '';
            newRow.querySelector('input[type="text"]').value = '';
            container.appendChild(newRow);
        }
    </script>
</x-app-layout>