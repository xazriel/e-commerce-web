<x-app-layout>
    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">
                {{-- Header --}}
                <div class="flex items-center justify-between mb-8 border-b border-gray-100 pb-4">
                    <h2 class="text-xl font-semibold text-gray-800 tracking-tight">Tambah Produk & Varian</h2>
                    <span class="text-[10px] bg-gray-100 text-gray-500 px-3 py-1 rounded-full uppercase tracking-widest font-bold">Farhana Admin</span>
                </div>

                <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="space-y-10">
                        {{-- 1. Informasi Dasar & Harga --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[11px] font-bold text-gray-700 uppercase tracking-widest mb-2">Nama Produk</label>
                                <input type="text" name="name" placeholder="Contoh: Abaya D1 - Midnight Black" 
                                    class="w-full border-gray-200 rounded-lg shadow-sm focus:border-black focus:ring-black" required>
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-gray-700 uppercase tracking-widest mb-2">Kategori</label>
                                <select name="category_id" id="category_select" class="w-full border-gray-200 rounded-lg shadow-sm focus:border-black focus:ring-black">
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" data-type="{{ $cat->type }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[11px] font-bold text-gray-700 uppercase tracking-widest mb-2">Harga Utama (Rp)</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-2.5 text-gray-400 text-sm">Rp</span>
                                    <input type="number" name="price" id="base_price" placeholder="185000" 
                                        class="w-full pl-10 border-gray-200 rounded-lg shadow-sm focus:border-black focus:ring-black" required>
                                </div>
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-gray-700 uppercase tracking-widest mb-2">Custom Tag (Opsional)</label>
                                <input type="text" name="custom_tag" placeholder="Misal: Best Seller / Trending" 
                                    class="w-full border-gray-200 rounded-lg shadow-sm focus:border-black focus:ring-black">
                            </div>
                        </div>

                        {{-- 2. Status & Countdown --}}
                        <div class="p-6 border border-gray-100 rounded-xl bg-gray-50/50">
                            <label class="block text-[11px] font-bold text-gray-700 uppercase tracking-widest mb-4">Pengaturan Label & Rilis</label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div class="flex gap-6">
                                    <label class="flex items-center cursor-pointer group">
                                        <input type="checkbox" name="is_preorder" value="1" class="rounded border-gray-300 text-black focus:ring-black mr-2 w-4 h-4">
                                        <span class="text-[10px] text-gray-600 uppercase font-bold tracking-tight">Set Pre-Order</span>
                                    </label>
                                    <label class="flex items-center cursor-pointer group">
                                        <input type="checkbox" name="is_limited" value="1" class="rounded border-gray-300 text-black focus:ring-black mr-2 w-4 h-4">
                                        <span class="text-[10px] text-gray-600 uppercase font-bold tracking-tight">Limited Edition</span>
                                    </label>
                                </div>
                                <div>
                                    <label class="block text-[9px] font-bold text-gray-400 uppercase mb-1">Tanggal Rilis (Untuk Countdown)</label>
                                    <input type="datetime-local" name="release_date" 
                                        class="w-full text-xs border-gray-200 rounded-md focus:ring-black focus:border-black">
                                </div>
                            </div>
                        </div>

                        {{-- NEW SECTION: Size Guide Template Selection --}}
                        <div class="p-6 border border-[#5A5A00]/20 rounded-xl bg-[#5A5A00]/5">
                            <div class="flex items-center mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#5A5A00] mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                                <label class="block text-[11px] font-bold text-[#5A5A00] uppercase tracking-widest">Dynamic Size Guide</label>
                            </div>
                            <div class="grid grid-cols-1 gap-4">
                                <select name="size_guide_template_id" class="w-full border-gray-200 rounded-lg shadow-sm focus:border-[#5A5A00] focus:ring-[#5A5A00] text-sm">
                                    <option value="">-- Tanpa Template (Kosong) --</option>
                                    @foreach($templates as $template)
                                        <option value="{{ $template->id }}">{{ $template->name }}</option>
                                    @endforeach
                                </select>
                                <p class="text-[9px] text-gray-400 uppercase tracking-wider italic">Pilih template tabel ukuran yang akan muncul di modal "Size Guide" produk ini.</p>
                            </div>
                        </div>

                        {{-- 3. Variant Section --}}
                        <div class="p-6 border border-gray-100 rounded-xl bg-gray-50/30">
                            <div class="flex justify-between items-center mb-6">
                                <div>
                                    <label class="block text-[11px] font-bold text-gray-700 uppercase tracking-widest">Varian & Harga Tambahan</label>
                                    <p class="text-[9px] text-gray-400 uppercase mt-1 italic">*Additional price akan ditambahkan ke harga utama</p>
                                </div>
                                <button type="button" onclick="addVariantRow()" class="text-[10px] bg-black text-white px-4 py-2 rounded uppercase tracking-widest font-bold hover:bg-gray-800 transition">
                                    + Tambah Varian
                                </button>
                            </div>

                            <div id="variant-container" class="space-y-3">
                                {{-- Baris Varian --}}
                                <div class="grid grid-cols-1 md:grid-cols-5 gap-4 p-4 bg-white border border-gray-100 rounded-lg shadow-sm variant-row items-end">
                                    <div class="md:col-span-1">
                                        <label class="block text-[9px] font-bold text-gray-400 uppercase mb-1">Warna</label>
                                        <input type="text" name="variant_color[]" onkeyup="updateColorOptions()" placeholder="Midnight Black" 
                                            class="variant-color-input w-full text-xs border-gray-200 rounded-md focus:ring-black focus:border-black" required>
                                    </div>
                                    <div>
                                        <label class="block text-[9px] font-bold text-gray-400 uppercase mb-1">Ukuran</label>
                                        <select name="variant_size[]" onchange="autoCalculatePrice(this)" class="size-selector w-full text-xs border-gray-200 rounded-md focus:ring-black focus:border-black">
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-[9px] font-bold text-gray-400 uppercase mb-1">Stok</label>
                                        <input type="number" name="variant_stock[]" placeholder="0" 
                                            class="w-full text-xs border-gray-200 rounded-md focus:ring-black focus:border-black" required>
                                    </div>
                                    <div>
                                        <label class="block text-[9px] font-bold text-gray-400 uppercase mb-1">Harga Tambahan</label>
                                        <div class="relative">
                                            <span class="absolute left-2 top-2.5 text-[10px] text-gray-400">+</span>
                                            <input type="number" name="additional_price[]" placeholder="0" value="0"
                                                class="additional-price-input w-full pl-5 text-xs border-gray-200 rounded-md focus:ring-black focus:border-black">
                                        </div>
                                    </div>
                                    <div class="flex justify-center pb-2">
                                        <button type="button" onclick="removeVariantRow(this)" class="bg-red-500 text-white rounded-full w-6 h-6 text-xs flex items-center justify-center hover:bg-red-600 transition">×</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- 4. Deskripsi --}}
                        <div>
                            <label class="block text-[11px] font-bold text-gray-700 uppercase tracking-widest mb-2">Deskripsi Detail</label>
                            <textarea name="description" rows="5" placeholder="Tuliskan detail kain, jahitan, dll..." 
                                class="w-full border-gray-200 rounded-lg shadow-sm focus:border-black focus:ring-black"></textarea>
                        </div>

                        {{-- 5. Image Upload Section --}}
                        <div class="p-6 border-2 border-dashed border-gray-200 rounded-xl bg-gray-50">
                            <div class="flex justify-between items-center mb-4">
                                <label class="block text-[11px] font-bold text-gray-700 uppercase tracking-widest">Foto Produk & Mapping Warna</label>
                                <button type="button" onclick="addImageRow()" class="text-[10px] text-black underline uppercase font-bold tracking-widest">Tambah Slot Foto</button>
                            </div>
                            
                            <div id="image-container" class="space-y-4">
                                <div class="flex flex-col md:flex-row gap-4 p-4 bg-white border border-gray-100 rounded-lg items-center image-row">
                                    <input type="file" name="images[]" class="block w-full text-[10px] text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-bold file:bg-black file:text-white hover:file:bg-gray-800" required />
                                    
                                    <select name="image_colors[]" class="color-selector w-full md:w-1/3 text-xs border-gray-200 rounded-md focus:ring-black focus:border-black">
                                        <option value="">Pilih Warna Foto</option>
                                    </select>
                                    
                                    <button type="button" onclick="removeImageRow(this)" class="text-red-500 text-xs font-bold uppercase tracking-widest ml-2">Hapus</button>
                                </div>
                            </div>
                        </div>

                        {{-- Submit Button --}}
                        <div class="pt-6">
                            <button type="submit" class="w-full bg-black text-white px-8 py-5 rounded-lg text-[11px] font-bold uppercase tracking-[0.4em] hover:bg-gray-800 transition duration-300 shadow-xl">
                                Simpan & Publikasi Produk
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // 1. DATA CONFIGURATION
        const sizes = {
            standard: ['S', 'M', 'L', 'XL', 'All Size'],
            kids: [
                '3 - 4 Years', 
                '5 - 6 Years', 
                '7 - 8 Years', 
                '9 - 10 Years', 
                '11 - 12 Years'
            ]
        };

        // 2. LOGIC OTOMATIS HARGA (KIDS CASE)
        function autoCalculatePrice(selectElement) {
            const row = selectElement.closest('.variant-row');
            if(!row) return;
            const addPriceInput = row.querySelector('.additional-price-input');
            const categorySelect = document.getElementById('category_select');
            const selectedType = categorySelect.options[categorySelect.selectedIndex].getAttribute('data-type');
            
            if (selectedType === 'kids') {
                const bigSizes = ['7 - 8 Years', '9 - 10 Years', '11 - 12 Years'];
                addPriceInput.value = bigSizes.includes(selectElement.value) ? 20000 : 0;
            }
        }

        // 3. SIZE SELECTOR MANAGEMENT
        function updateAllSizeSelectors() {
            const categorySelect = document.getElementById('category_select');
            if(!categorySelect) return;

            const selectedOption = categorySelect.options[categorySelect.selectedIndex];
            const type = selectedOption ? selectedOption.getAttribute('data-type') : 'standard';
            
            const currentSizes = sizes[type] || sizes.standard;
            const selectors = document.querySelectorAll('.size-selector');

            selectors.forEach(select => {
                const prevValue = select.value;
                select.innerHTML = '';
                
                currentSizes.forEach(size => {
                    const option = document.createElement('option');
                    option.value = size;
                    option.textContent = size;
                    if(size === prevValue) option.selected = true;
                    select.appendChild(option);
                });
                
                autoCalculatePrice(select);
            });
        }

        // 4. VARIANT ROW MANAGEMENT
        function addVariantRow() {
            const container = document.getElementById('variant-container');
            const rows = container.querySelectorAll('.variant-row');
            const newRow = rows[0].cloneNode(true);
            
            newRow.querySelectorAll('input').forEach(input => {
                if(input.name === 'additional_price[]') {
                    input.value = 0;
                } else {
                    input.value = '';
                }
            });
            
            container.appendChild(newRow);
            updateAllSizeSelectors();
            updateColorOptions();
        }

        function removeVariantRow(btn) {
            const rows = document.querySelectorAll('.variant-row');
            if(rows.length > 1) {
                btn.closest('.variant-row').remove();
                updateColorOptions();
            } else {
                alert('Minimal harus ada 1 varian');
            }
        }

        // 5. COLOR MAPPING MANAGEMENT
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
                const currentValue = select.value;
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

        // 6. IMAGE ROW MANAGEMENT
        function addImageRow() {
            const container = document.getElementById('image-container');
            const rows = container.querySelectorAll('.image-row');
            const newRow = rows[0].cloneNode(true);
            newRow.querySelector('input[type="file"]').value = '';
            container.appendChild(newRow);
            updateColorOptions();
        }

        function removeImageRow(btn) {
            const rows = document.querySelectorAll('.image-row');
            if(rows.length > 1) {
                btn.closest('.image-row').remove();
            }
        }

        // 7. INITIALIZATION
        document.getElementById('category_select').addEventListener('change', updateAllSizeSelectors);
        
        window.onload = function() {
            updateAllSizeSelectors();
        };
    </script>
</x-app-layout>