<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 tracking-tight italic uppercase">Edit Produk: {{ $product->name }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-8 shadow-sm rounded-lg border border-gray-100">
                
                {{-- Form Utama --}}
                <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data" id="main-form">
                    @csrf
                    @method('PUT')

                    <div class="space-y-10">
                        {{-- 1. Nama, Kategori & Size Guide --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-[11px] font-bold text-gray-700 uppercase tracking-widest mb-2">Nama Produk</label>
                                <input type="text" name="name" value="{{ old('name', $product->name) }}" class="w-full border-gray-200 rounded-lg shadow-sm focus:border-black focus:ring-black" required>
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-gray-700 uppercase tracking-widest mb-2">Kategori</label>
                                <select name="category_id" id="category_id" onchange="toggleSizeOptions()" class="w-full border-gray-200 rounded-lg shadow-sm focus:border-black focus:ring-black">
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" data-slug="{{ Str::slug($cat->name) }}" {{ $product->category_id == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            {{-- TAMBAHAN: Dropdown Size Guide Template --}}
                            <div>
                                <label class="block text-[11px] font-bold text-[#5A5A00] uppercase tracking-widest mb-2">Size Guide Template</label>
                                <select name="size_guide_template_id" class="w-full border-gray-200 rounded-lg shadow-sm focus:border-[#5A5A00] focus:ring-[#5A5A00]">
                                    <option value="">-- No Size Guide --</option>
                                    @foreach($sizeGuides as $guide)
                                        <option value="{{ $guide->id }}" {{ $product->size_guide_template_id == $guide->id ? 'selected' : '' }}>
                                            {{ $guide->name }} ({{ strtoupper($guide->type) }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- 2. Harga Dasar --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[11px] font-bold text-gray-700 uppercase tracking-widest mb-2">Harga Dasar (Rp)</label>
                                <input type="number" name="price" value="{{ old('price', $product->price) }}" class="w-full border-gray-200 rounded-lg shadow-sm focus:border-black focus:ring-black" required>
                                <p class="text-[10px] text-gray-400 mt-1 italic">*Harga ini akan ditambah dengan Additional Price varian jika ada.</p>
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-gray-700 uppercase tracking-widest mb-2">Custom Tag (Opsional)</label>
                                <input type="text" name="custom_tag" value="{{ old('custom_tag', $product->custom_tag) }}" placeholder="Misal: Best Seller" 
                                    class="w-full border-gray-200 rounded-lg shadow-sm focus:border-black focus:ring-black">
                            </div>
                        </div>

                        {{-- 3. Status & Countdown --}}
                        <div class="p-6 border border-gray-100 rounded-xl bg-gray-50/50">
                            <label class="block text-[11px] font-bold text-gray-700 uppercase tracking-widest mb-4">Pengaturan Label & Rilis</label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div class="flex gap-6">
                                    <label class="flex items-center cursor-pointer group">
                                        <input type="checkbox" name="is_preorder" value="1" {{ $product->is_preorder ? 'checked' : '' }}
                                            class="rounded border-gray-300 text-black focus:ring-black mr-2 w-4 h-4">
                                        <span class="text-[10px] text-gray-600 uppercase font-bold tracking-tight">Set Pre-Order</span>
                                    </label>
                                    <label class="flex items-center cursor-pointer group">
                                        <input type="checkbox" name="is_limited" value="1" {{ $product->is_limited ? 'checked' : '' }}
                                            class="rounded border-gray-300 text-black focus:ring-black mr-2 w-4 h-4">
                                        <span class="text-[10px] text-gray-600 uppercase font-bold tracking-tight">Limited Edition</span>
                                    </label>
                                </div>
                                <div>
                                    <label class="block text-[9px] font-bold text-gray-400 uppercase mb-1">Tanggal Rilis (Countdown)</label>
                                    <input type="datetime-local" name="release_date" 
                                        value="{{ $product->release_date ? \Carbon\Carbon::parse($product->release_date)->format('Y-m-d\TH:i') : '' }}"
                                        class="w-full text-xs border-gray-200 rounded-md focus:ring-black focus:border-black">
                                </div>
                            </div>
                        </div>

                        {{-- 4. Section Varian --}}
                        <div class="p-6 border border-gray-100 rounded-xl bg-gray-50/30">
                            <div class="flex justify-between items-center mb-6">
                                <div>
                                    <label class="block text-[11px] font-bold text-gray-700 uppercase tracking-widest">Stok Per Varian</label>
                                    <p class="text-[9px] text-gray-400 uppercase tracking-tighter">Atur warna, ukuran, stok, dan biaya tambahan.</p>
                                </div>
                                <button type="button" onclick="addVariantRow()" class="text-[10px] bg-black text-white px-4 py-2 rounded uppercase tracking-widest font-bold hover:bg-gray-800 transition">
                                    + Tambah Varian
                                </button>
                            </div>

                            <div id="variant-container" class="space-y-3">
                                @forelse($product->variants as $variant)
                                    <div class="grid grid-cols-1 md:grid-cols-12 gap-3 p-4 bg-white border border-gray-100 rounded-lg shadow-sm variant-row items-end">
                                        <input type="hidden" name="variant_ids[]" value="{{ $variant->id }}">
                                        
                                        <div class="md:col-span-3">
                                            <label class="text-[9px] font-bold text-gray-400 uppercase mb-1 block">Warna</label>
                                            <input type="text" name="variant_color[]" value="{{ $variant->color }}" oninput="updateColorOptions()" placeholder="Warna" class="variant-color-input w-full text-xs border-gray-200 rounded-md focus:ring-black focus:border-black" required>
                                        </div>

                                        <div class="md:col-span-3">
                                            <label class="text-[9px] font-bold text-gray-400 uppercase mb-1 block">Size</label>
                                            <select name="variant_size[]" class="variant-size-select w-full text-xs border-gray-200 rounded-md focus:ring-black focus:border-black" data-selected="{{ $variant->size }}">
                                            </select>
                                        </div>

                                        <div class="md:col-span-2">
                                            <label class="text-[9px] font-bold text-gray-400 uppercase mb-1 block">Stok</label>
                                            <input type="number" name="variant_stock[]" value="{{ $variant->stock }}" placeholder="Stok" class="w-full text-xs border-gray-200 rounded-md focus:ring-black focus:border-black" required>
                                        </div>

                                        <div class="md:col-span-3">
                                            <label class="text-[9px] font-bold text-gray-400 uppercase mb-1 block">+ Harga</label>
                                            <div class="relative">
                                                <span class="absolute inset-y-0 left-0 pl-2 flex items-center text-[10px] text-gray-400">Rp</span>
                                                <input type="number" name="additional_price[]" value="{{ $variant->additional_price ?? 0 }}" class="w-full pl-7 text-xs border-gray-200 rounded-md focus:ring-black focus:border-black">
                                            </div>
                                        </div>

                                        <div class="md:col-span-1 flex justify-center pb-1">
                                            <button type="button" onclick="removeVariantRow(this)" class="bg-red-50 text-red-500 rounded-full w-7 h-7 flex items-center justify-center hover:bg-red-500 hover:text-white transition border border-red-100">×</button>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-center text-gray-400 text-xs py-4">Klik tambah varian untuk memulai.</p>
                                @endforelse
                            </div>
                        </div>

                        {{-- 5. Deskripsi --}}
                        <div>
                            <label class="block text-[11px] font-bold text-gray-700 uppercase tracking-widest mb-2">Deskripsi Produk</label>
                            <textarea name="description" rows="5" class="w-full border-gray-200 rounded-lg shadow-sm focus:border-black focus:ring-black">{{ old('description', $product->description) }}</textarea>
                        </div>

                        {{-- 6. Galeri Foto --}}
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
                                                    onclick="submitHiddenForm('set-primary-{{ $img->id }}')"
                                                    class="flex-1 bg-white border border-gray-200 text-[9px] font-bold uppercase py-1.5 rounded hover:bg-black hover:text-white transition">
                                                    Main
                                                </button>
                                            @endif
                                            <button type="button" 
                                                onclick="if(confirm('Hapus foto ini?')) submitHiddenForm('delete-img-{{ $img->id }}')"
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

                                        <button type="button" onclick="this.closest('.image-row').remove()" class="text-red-500 hover:text-red-700">
                                             <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
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

                {{-- Hidden Forms --}}
                @foreach($product->images as $img)
                    <form id="set-primary-{{ $img->id }}" action="{{ route('products.images.setPrimary', $img->id) }}" method="POST" class="hidden">
                        @csrf @method('PATCH')
                    </form>
                    <form id="delete-img-{{ $img->id }}" action="{{ route('products.images.destroy', $img->id) }}" method="POST" class="hidden">
                        @csrf @method('DELETE')
                    </form>
                @endforeach
            </div>
        </div>
    </div>

    <script>
    const ADULT_SIZES = ['S', 'M', 'L', 'XL', 'XXL', 'All Size'];
    const KIDS_SIZES = ['3 - 4 Years', '5 - 6 Years', '7 - 8 Years', '9 - 10 Years', '11 - 12 Years'];

    function submitHiddenForm(formId) {
        const form = document.getElementById(formId);
        if(form) form.submit();
    }

    function toggleSizeOptions() {
        const categorySelect = document.getElementById('category_id');
        const selectedOption = categorySelect.options[categorySelect.selectedIndex];
        const slug = selectedOption.getAttribute('data-slug') || '';
        const sizeList = (slug.includes('kids') || slug.includes('anak')) ? KIDS_SIZES : ADULT_SIZES;

        document.querySelectorAll('.variant-size-select').forEach(select => {
            const currentVal = select.getAttribute('data-selected') || select.value;
            select.innerHTML = '';
            sizeList.forEach(size => {
                const option = document.createElement('option');
                option.value = size;
                option.textContent = size;
                if(size === currentVal) option.selected = true;
                select.appendChild(option);
            });
            select.setAttribute('data-selected', select.value);
        });
    }

    function addVariantRow() {
        const container = document.getElementById('variant-container');
        const html = `
            <div class="grid grid-cols-1 md:grid-cols-12 gap-3 p-4 bg-white border border-gray-100 rounded-lg shadow-sm variant-row items-end">
                <input type="hidden" name="variant_ids[]" value="">
                <div class="md:col-span-3">
                    <label class="text-[9px] font-bold text-gray-400 uppercase mb-1 block">Warna</label>
                    <input type="text" name="variant_color[]" oninput="updateColorOptions()" placeholder="Warna" class="variant-color-input w-full text-xs border-gray-200 rounded-md focus:ring-black focus:border-black" required>
                </div>
                <div class="md:col-span-3">
                    <label class="text-[9px] font-bold text-gray-400 uppercase mb-1 block">Size</label>
                    <select name="variant_size[]" class="variant-size-select w-full text-xs border-gray-200 rounded-md focus:ring-black focus:border-black"></select>
                </div>
                <div class="md:col-span-2">
                    <label class="text-[9px] font-bold text-gray-400 uppercase mb-1 block">Stok</label>
                    <input type="number" name="variant_stock[]" placeholder="Stok" class="w-full text-xs border-gray-200 rounded-md focus:ring-black focus:border-black" required>
                </div>
                <div class="md:col-span-3">
                    <label class="text-[9px] font-bold text-gray-400 uppercase mb-1 block">+ Harga</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-2 flex items-center text-[10px] text-gray-400">Rp</span>
                        <input type="number" name="additional_price[]" value="0" class="w-full pl-7 text-xs border-gray-200 rounded-md focus:ring-black focus:border-black">
                    </div>
                </div>
                <div class="md:col-span-1 flex justify-center pb-1">
                    <button type="button" onclick="removeVariantRow(this)" class="bg-red-50 text-red-500 rounded-full w-7 h-7 flex items-center justify-center hover:bg-red-500 hover:text-white transition border border-red-100">×</button>
                </div>
            </div>`;
        container.insertAdjacentHTML('beforeend', html);
        toggleSizeOptions();
        updateColorOptions();
    }

    function removeVariantRow(btn) {
        const rows = document.querySelectorAll('.variant-row');
        if(rows.length > 1) {
            btn.closest('.variant-row').remove();
            updateColorOptions();
        } else {
            alert('Minimal harus ada satu varian.');
        }
    }

    function addNewImageRow() {
        const container = document.getElementById('new-image-container');
        const firstRow = container.querySelector('.image-row');
        const newRow = firstRow.cloneNode(true);
        newRow.querySelector('input[type="file"]').value = '';
        const select = newRow.querySelector('select');
        select.innerHTML = '<option value="">Pilih Warna Foto</option>';
        select.removeAttribute('data-selected'); 
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
            const userChoice = select.value;
            const initialVal = select.getAttribute('data-selected');
            const activeValue = userChoice || initialVal;

            const isNewImage = select.name === 'image_colors_new[]';
            select.innerHTML = `<option value="">${isNewImage ? 'Pilih Warna Foto' : 'Global / No Color'}</option>`;
            
            colors.forEach(color => {
                const option = document.createElement('option');
                option.value = color;
                option.textContent = color;
                if(color === activeValue) option.selected = true;
                select.appendChild(option);
            });
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        toggleSizeOptions();
        updateColorOptions();
        
        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('color-selector')) {
                e.target.setAttribute('data-selected', e.target.value);
            }
        });
    });
</script>
</x-app-layout>