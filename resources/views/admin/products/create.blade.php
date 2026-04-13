<x-app-layout>
    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">
                <div class="flex items-center justify-between mb-8 border-b border-gray-100 pb-4">
                    <h2 class="text-xl font-semibold text-gray-800 tracking-tight">Tambah Produk & Varian</h2>
                    <span class="text-[10px] bg-gray-100 text-gray-500 px-3 py-1 rounded-full uppercase tracking-widest font-bold">Farhana Admin</span>
                </div>

                <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="space-y-10">
                        {{-- Basic Information --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[11px] font-bold text-gray-700 uppercase tracking-widest mb-2">Nama Produk</label>
                                <input type="text" name="name" placeholder="Contoh: Abaya D1 - Midnight Black" 
                                    class="w-full border-gray-200 rounded-lg shadow-sm focus:border-black focus:ring-black" required>
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-gray-700 uppercase tracking-widest mb-2">Kategori</label>
                                <select name="category_id" class="w-full border-gray-200 rounded-lg shadow-sm focus:border-black focus:ring-black">
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[11px] font-bold text-gray-700 uppercase tracking-widest mb-2">Harga (Rp)</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-2.5 text-gray-400 text-sm">Rp</span>
                                    <input type="number" name="price" placeholder="1099000" 
                                        class="w-full pl-10 border-gray-200 rounded-lg shadow-sm focus:border-black focus:ring-black" required>
                                </div>
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-gray-700 uppercase tracking-widest mb-2">Tags / Label Koleksi</label>
                                <div class="flex flex-wrap gap-3 p-3 border border-gray-100 rounded-lg bg-gray-50/50">
                                    @foreach($tags as $tag)
                                        <label class="inline-flex items-center cursor-pointer group">
                                            <input type="checkbox" name="tags[]" value="{{ $tag->id }}" 
                                                class="rounded border-gray-300 text-black focus:ring-black mr-2 w-4 h-4">
                                            <span class="text-[10px] text-gray-600 uppercase font-bold tracking-tighter">{{ $tag->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        {{-- Variant Section --}}
                        <div class="p-6 border border-gray-100 rounded-xl bg-gray-50/30">
                            <div class="flex justify-between items-center mb-6">
                                <div>
                                    <label class="block text-[11px] font-bold text-gray-700 uppercase tracking-widest">Stok Per Varian</label>
                                    <p class="text-[9px] text-gray-400 uppercase mt-1 italic">*Tiap kombinasi warna & size bisa punya stok berbeda</p>
                                </div>
                                <button type="button" onclick="addVariantRow()" class="text-[10px] bg-black text-white px-4 py-2 rounded uppercase tracking-widest font-bold hover:bg-gray-800 transition">
                                    + Tambah Varian
                                </button>
                            </div>

                            <div id="variant-container" class="space-y-3">
                                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 p-4 bg-white border border-gray-100 rounded-lg shadow-sm variant-row">
                                    <div class="md:col-span-2">
                                        {{-- Tambahkan class 'variant-color-input' untuk dideteksi JS --}}
                                        <input type="text" name="variant_color[]" onkeyup="updateColorOptions()" placeholder="Warna (Misal: Midnight Black)" 
                                            class="variant-color-input w-full text-xs border-gray-200 rounded-md focus:ring-black focus:border-black" required>
                                    </div>
                                    <div>
                                        <select name="variant_size[]" class="w-full text-xs border-gray-200 rounded-md focus:ring-black focus:border-black">
                                            @foreach(['S', 'M', 'L', 'XL', 'All Size'] as $size)
                                                <option value="{{ $size }}">{{ $size }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="relative">
                                        <input type="number" name="variant_stock[]" placeholder="Stok" 
                                            class="w-full text-xs border-gray-200 rounded-md focus:ring-black focus:border-black" required>
                                        <button type="button" onclick="removeVariantRow(this)" class="absolute -right-2 -top-2 bg-red-500 text-white rounded-full w-5 h-5 text-[10px] flex items-center justify-center hover:bg-red-600">×</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-gray-700 uppercase tracking-widest mb-2">Deskripsi Detail</label>
                            <textarea name="description" rows="5" placeholder="Tuliskan detail kain, jahitan, dll..." 
                                class="w-full border-gray-200 rounded-lg shadow-sm focus:border-black focus:ring-black"></textarea>
                        </div>

                        {{-- Image Upload Section --}}
                        <div class="p-6 border-2 border-dashed border-gray-200 rounded-xl bg-gray-50">
                            <div class="flex justify-between items-center mb-4">
                                <label class="block text-[11px] font-bold text-gray-700 uppercase tracking-widest">Foto Produk & Mapping Warna</label>
                                <button type="button" onclick="addImageRow()" class="text-[10px] text-black underline uppercase font-bold tracking-widest">Tambah Slot Foto</button>
                            </div>
                            
                            <div id="image-container" class="space-y-4">
                                <div class="flex flex-col md:flex-row gap-4 p-4 bg-white border border-gray-100 rounded-lg items-center image-row">
                                    <input type="file" name="images[]" class="block w-full text-[10px] text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-bold file:bg-black file:text-white hover:file:bg-gray-800" required />
                                    
                                    {{-- Mengubah input text menjadi SELECT agar sinkron dengan varian warna --}}
                                    <select name="image_colors[]" class="color-selector w-full md:w-1/3 text-xs border-gray-200 rounded-md focus:ring-black focus:border-black">
                                        <option value="">Pilih Warna Foto</option>
                                    </select>
                                    
                                    <button type="button" onclick="removeImageRow(this)" class="text-red-500 text-xs font-bold uppercase tracking-widest ml-2">Hapus</button>
                                </div>
                            </div>
                            <p class="text-[9px] text-gray-400 mt-4 italic uppercase tracking-wider">*Pilih 'Warna' agar saat user klik pilihan warna, gambar utama otomatis berubah.</p>
                        </div>

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
        // 1. Fungsi Tambah Baris Varian
        function addVariantRow() {
            const container = document.getElementById('variant-container');
            const newRow = container.querySelector('.variant-row').cloneNode(true);
            newRow.querySelectorAll('input').forEach(input => input.value = '');
            container.appendChild(newRow);
            updateColorOptions(); // Update list warna di bagian image
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

        // 2. Fungsi Tambah Slot Foto
        function addImageRow() {
            const container = document.getElementById('image-container');
            const newRow = container.querySelector('.image-row').cloneNode(true);
            newRow.querySelector('input[type="file"]').value = '';
            container.appendChild(newRow);
            updateColorOptions(); // Pastikan slot baru punya pilihan warna
        }

        function removeImageRow(btn) {
            const rows = document.querySelectorAll('.image-row');
            if(rows.length > 1) {
                btn.closest('.image-row').remove();
            }
        }

        // 3. LOGIKA UTAMA: Sinkronisasi Warna Varian ke Dropdown Foto
        function updateColorOptions() {
            // Ambil semua warna unik dari input varian
            const colorInputs = document.querySelectorAll('.variant-color-input');
            let colors = [];
            
            colorInputs.forEach(input => {
                const val = input.value.trim();
                if (val && !colors.includes(val)) {
                    colors.push(val);
                }
            });

            // Update semua dropdown warna di bagian upload foto
            const selectors = document.querySelectorAll('.color-selector');
            selectors.forEach(select => {
                const currentValue = select.value; // Simpan nilai yang sedang terpilih
                
                // Reset options tapi sisakan default
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
</script>
</x-app-layout>