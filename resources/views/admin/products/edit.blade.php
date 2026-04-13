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
                        {{-- Nama & Kategori --}}
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

                        {{-- Harga & Tags --}}
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

                        {{-- Section Varian --}}
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
                                        <input type="hidden" name="variant_ids[]" value="{{ $variant->id }}">
                                        
                                        <div class="md:col-span-2">
                                            <input type="text" name="variant_color[]" value="{{ $variant->color }}" onkeyup="updateColorOptions()" placeholder="Warna" class="variant-color-input w-full text-xs border-gray-200 rounded-md focus:ring-black focus:border-black" required>
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
                                            <button type="button" onclick="removeVariantRow(this)" class="absolute -right-2 -top-2 bg-red-500 text-white rounded-full w-5 h-5 text-[10px] flex items-center justify-center hover:bg-red-600">×</button>
                                        </div>
                                    </div>
                                @empty
                                    {{-- Baris Default jika tidak ada varian --}}
                                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 p-4 bg-white border border-gray-100 rounded-lg shadow-sm variant-row">
                                        <input type="hidden" name="variant_ids[]" value="">
                                        <div class="md:col-span-2">
                                            <input type="text" name="variant_color[]" onkeyup="updateColorOptions()" placeholder="Warna" class="variant-color-input w-full text-xs border-gray-200 rounded-md" required>
                                        </div>
                                        <div>
                                            <select name="variant_size[]" class="w-full text-xs border-gray-200 rounded-md">
                                                @foreach(['S', 'M', 'L', 'XL', 'All Size'] as $size)
                                                    <option value="{{ $size }}">{{ $size }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="relative">
                                            <input type="number" name="variant_stock[]" placeholder="Stok" class="w-full text-xs border-gray-200 rounded-md" required>
                                        </div>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        {{-- Deskripsi --}}
                        <div>
                            <label class="block text-[11px] font-bold text-gray-700 uppercase tracking-widest mb-2">Deskripsi Produk</label>
                            <textarea name="description" rows="5" class="w-full border-gray-200 rounded-lg shadow-sm focus:border-black focus:ring-black">{{ $product->description }}</textarea>
                        </div>

                        {{-- Galeri Foto --}}
                        <div class="mb-10">
                            <label class="block text-[11px] font-bold text-gray-900 uppercase tracking-widest mb-4 pb-2 border-b">Galeri Foto Produk</label>
                            
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                                @foreach($product->images as $img)
                                    <div class="relative group border p-3 rounded-lg bg-gray-50 {{ $img->is_primary ? 'border-black ring-1 ring-black' : 'border-gray-100' }}">
                                        <img src="{{ asset('storage/' . $img->image_path) }}" class="w-full h-40 object-cover rounded-md mb-3">
                                        
                                        <div class="mb-2">
                                            <label class="text-[9px] uppercase font-bold text-gray-400 block mb-1">Mapping Warna:</label>
                                            <input type="hidden" name="existing_image_ids[]" value="{{ $img->id }}">
                                            <select name="existing_image_colors[]" class="color-selector w-full text-[10px] border-gray-200 rounded-md py-1" data-selected="{{ $img->color }}">
                                                <option value="">Global / No Color</option>
                                            </select>
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
                                    <div class="flex flex-col md:flex-row gap-4 p-4 bg-white border border-gray-100 rounded-lg items-center image-row">
                                        <input type="file" name="images[]" class="block w-full text-[10px] text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-bold file:bg-black file:text-white" />
                                        
                                        <select name="image_colors_new[]" class="color-selector w-full md:w-1/3 text-xs border-gray-200 rounded-md focus:ring-black">
                                            <option value="">Pilih Warna Foto</option>
                                        </select>

                                        <button type="button" onclick="this.closest('.image-row').remove()" class="text-red-500 hover:text-red-700 md:block hidden">
                                             <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <button type="button" onclick="addNewImageRow()" class="mt-4 text-[10px] text-black underline uppercase font-bold tracking-widest">Tambah Slot Foto Baru</button>
                            </div>
                        </div>

                        {{-- Tombol Simpan --}}
                        <div class="flex justify-end items-center gap-4 border-t pt-8">
                            <a href="{{ route('products.index') }}" class="text-xs font-bold uppercase tracking-widest text-gray-400 hover:text-black transition">Batal</a>
                            <button type="submit" class="bg-black text-white px-10 py-4 rounded-lg text-xs font-bold uppercase tracking-[0.3em] shadow-xl hover:bg-gray-800 transition">
                                Update Produk & Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </form>

                {{-- Form Tersembunyi untuk Action Foto --}}
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
        const rows = container.querySelectorAll('.variant-row');
        const newRow = rows[0].cloneNode(true);
        
        const idInput = newRow.querySelector('input[name="variant_ids[]"]');
        if(idInput) idInput.value = '';

        newRow.querySelectorAll('input[type="text"], input[type="number"]').forEach(input => {
            input.value = '';
        });

        const select = newRow.querySelector('select');
        if(select) select.selectedIndex = 0;
        
        container.appendChild(newRow);
        updateColorOptions();
    }

    function removeVariantRow(btn) {
        const rows = document.querySelectorAll('.variant-row');
        if(rows.length > 1) {
            btn.closest('.variant-row').remove();
            updateColorOptions();
        }
    }

    function addNewImageRow() {
        const container = document.getElementById('new-image-container');
        const rows = container.querySelectorAll('.image-row');
        const newRow = rows[0].cloneNode(true);
        
        newRow.querySelector('input[type="file"]').value = '';
        // Reset select dropdown
        const select = newRow.querySelector('select');
        select.innerHTML = '<option value="">Pilih Warna Foto</option>';
        
        container.appendChild(newRow);
        updateColorOptions();
    }

    function updateColorOptions() {
        const colorInputs = document.querySelectorAll('.variant-color-input');
        let colors = [];
        
        colorInputs.forEach(input => {
            const val = input.value.trim();
            if (val && !colors.includes(val)) {
                colors.push(val);
            }
        });

        const selectors = document.querySelectorAll('.color-selector');
        selectors.forEach(select => {
            const currentValue = select.getAttribute('data-selected') || select.value;
            
            select.innerHTML = '<option value="">Pilih Warna Foto</option>';
            
            colors.forEach(color => {
                const option = document.createElement('option');
                option.value = color;
                option.textContent = color;
                if(color === currentValue) option.selected = true;
                select.appendChild(option);
            });
        });
    }

    // Jalankan sekali saat halaman dimuat untuk mengisi dropdown foto yang sudah ada
    document.addEventListener('DOMContentLoaded', function() {
        updateColorOptions();
    });
</script>
</x-app-layout>