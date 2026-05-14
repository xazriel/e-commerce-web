<x-app-layout>
    <style>
        :root {
            --primary: #2F3526;
            --white: #FFFFFF;
            --black: #000000;
            --olive: #6B705C;
            --light-gray: #E9E9E9;
        }

        .btn-primary {
            background-color: var(--primary);
            color: var(--white);
        }
        .btn-primary:hover {
            background-color: #1e2218;
        }
        .label-primary {
            color: var(--primary);
        }
        .border-primary {
            border-color: var(--primary);
        }
        .section-card {
            background: var(--white);
            border: 1px solid var(--light-gray);
            border-radius: 12px;
            padding: 24px;
        }
        .section-accent {
            background: #f5f5f0;
            border: 1px solid #d8dace;
            border-radius: 12px;
            padding: 24px;
        }
        .form-input {
            width: 100%;
            border: 1px solid var(--light-gray);
            border-radius: 8px;
            font-size: 13px;
            padding: 8px 12px;
            color: var(--black);
            background: var(--white);
            transition: border-color 0.15s;
        }
        .form-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 2px rgba(47, 53, 38, 0.1);
        }
        .field-label {
            display: block;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--olive);
            margin-bottom: 6px;
        }
        .page-header {
            background: var(--primary);
            color: var(--white);
            padding: 20px 32px;
            border-radius: 12px;
            margin-bottom: 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .variant-row, .image-row {
            background: var(--white);
            border: 1px solid var(--light-gray);
            border-radius: 10px;
            padding: 16px;
        }
        .remove-btn {
            background: #fef2f2;
            color: #ef4444;
            border: 1px solid #fecaca;
            border-radius: 50%;
            width: 28px;
            height: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.15s;
            font-size: 14px;
        }
        .remove-btn:hover {
            background: #ef4444;
            color: white;
        }
        .section-title {
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            color: var(--primary);
            margin-bottom: 4px;
        }
        .section-sub {
            font-size: 9px;
            color: var(--olive);
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }
    </style>

    <div class="py-10 px-4" style="background: #f8f8f5; min-height: 100vh;">
        <div class="max-w-5xl mx-auto">

            {{-- Header --}}
            <div class="page-header">
                <div>
                    <h2 style="font-size: 16px; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; margin: 0;">Tambah Produk Baru</h2>
                    <p style="font-size: 9px; color: #b8c4a0; text-transform: uppercase; letter-spacing: 0.1em; margin: 4px 0 0;">Isi semua informasi produk & varian dengan lengkap</p>
                </div>
                <a href="{{ route('admin.products.index') }}"
                    style="font-size: 9px; background: rgba(255,255,255,0.12); color: #c8d4b0; padding: 6px 14px; border-radius: 20px; font-weight: 700; letter-spacing: 0.12em; text-transform: uppercase; text-decoration: none;">
                    ← Kembali
                </a>
            </div>

            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div style="display: flex; flex-direction: column; gap: 20px;">

                    {{-- 1. Informasi Dasar --}}
                    <div class="section-card">
                        <p class="section-title">① Informasi Dasar</p>
                        <p class="section-sub" style="margin-bottom: 16px;">Nama, kategori, harga & tag produk</p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="field-label">Nama Produk</label>
                                <input type="text" name="name" placeholder="Contoh: Abaya D1 - Midnight Black" class="form-input" required>
                            </div>
                            <div>
                                <label class="field-label">Kategori</label>
                                <select name="category_id" id="category_select" onchange="updateAllSizeSelectors()" class="form-input">
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" data-type="{{ $cat->type }}" data-slug="{{ Str::slug($cat->name) }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="field-label">Harga Utama (Rp)</label>
                                <div style="position: relative;">
                                    <span style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); font-size: 11px; color: var(--olive); font-weight: 600;">Rp</span>
                                    <input type="number" name="price" id="base_price" placeholder="185000"
                                        class="form-input" style="padding-left: 36px;" required>
                                </div>
                            </div>
                            <div>
                                <label class="field-label">Custom Tag <span style="font-weight: 400; opacity: 0.6;">(Opsional)</span></label>
                                <input type="text" name="custom_tag" placeholder="Misal: Best Seller / Trending" class="form-input">
                            </div>
                        </div>
                    </div>

                    {{-- 2. Status & Countdown --}}
                    <div class="section-card">
                        <p class="section-title">② Label & Rilis</p>
                        <p class="section-sub" style="margin-bottom: 16px;">Pre-order, limited edition & countdown tanggal rilis</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div style="display: flex; gap: 20px; align-items: center;">
                                <label style="display: flex; align-items: center; cursor: pointer; gap: 8px;">
                                    <input type="checkbox" name="is_preorder" value="1"
                                        style="width: 15px; height: 15px; accent-color: var(--primary);">
                                    <span style="font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: var(--primary);">Pre-Order</span>
                                </label>
                                <label style="display: flex; align-items: center; cursor: pointer; gap: 8px;">
                                    <input type="checkbox" name="is_limited" value="1"
                                        style="width: 15px; height: 15px; accent-color: var(--primary);">
                                    <span style="font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: var(--primary);">Limited Edition</span>
                                </label>
                            </div>
                            <div>
                                <label class="field-label">Tanggal Rilis (Countdown)</label>
                                <input type="datetime-local" name="release_date" class="form-input" style="font-size: 12px;">
                            </div>
                        </div>
                    </div>

                    {{-- 3. Size Guide --}}
                    <div class="section-accent">
                        <p class="section-title" style="color: #5A5A00;">③ Dynamic Size Guide</p>
                        <p class="section-sub" style="margin-bottom: 16px; color: #8a8a50;">Pilih template tabel ukuran untuk modal "Size Guide"</p>
                        <select name="size_guide_template_id" class="form-input">
                            <option value="">-- Tanpa Template (Kosong) --</option>
                            @foreach($templates as $template)
                                <option value="{{ $template->id }}">{{ $template->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- 4. Varian --}}
                    <div class="section-card">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px;">
                            <div>
                                <p class="section-title">④ Varian & Stok</p>
                                <p class="section-sub">Warna, ukuran, stok & harga tambahan per varian</p>
                            </div>
                            <button type="button" onclick="addVariantRow()"
                                style="font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; padding: 8px 18px; border-radius: 8px; border: none; cursor: pointer;"
                                class="btn-primary">
                                + Tambah Varian
                            </button>
                        </div>

                        <div id="variant-container" style="display: flex; flex-direction: column; gap: 10px;">
                            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 variant-row items-end">
                                <div class="md:col-span-1">
                                    <label class="field-label">Warna</label>
                                    <input type="text" name="variant_color[]" oninput="updateColorOptions()" placeholder="Midnight Black"
                                        class="variant-color-input form-input" required>
                                </div>
                                <div>
                                    <label class="field-label">Ukuran</label>
                                    <select name="variant_size[]" onchange="autoCalculatePrice(this)" class="size-selector form-input"></select>
                                </div>
                                <div>
                                    <label class="field-label">Stok</label>
                                    <input type="number" name="variant_stock[]" placeholder="0" class="form-input" required>
                                </div>
                                <div>
                                    <label class="field-label">Harga Tambahan</label>
                                    <div style="position: relative;">
                                        <span style="position: absolute; left: 10px; top: 50%; transform: translateY(-50%); font-size: 10px; color: var(--olive);">+Rp</span>
                                        <input type="number" name="additional_price[]" placeholder="0" value="0"
                                            class="additional-price-input form-input" style="padding-left: 36px;">
                                    </div>
                                </div>
                                <div style="display: flex; justify-content: center; padding-bottom: 2px;">
                                    <button type="button" onclick="removeVariantRow(this)" class="remove-btn">×</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 5. Deskripsi --}}
                    <div class="section-card">
                        <p class="section-title">⑤ Deskripsi Produk</p>
                        <p class="section-sub" style="margin-bottom: 16px;">Detail kain, jahitan, bahan, dll.</p>
                        <textarea name="description" rows="5" placeholder="Tuliskan detail kain, jahitan, ukuran, dll..."
                            class="form-input" style="resize: vertical;"></textarea>
                    </div>

                    {{-- 6. Foto Produk & Mapping Warna --}}
                    <div class="section-card" style="border: 2px dashed var(--light-gray);">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px;">
                            <div>
                                <p class="section-title">⑥ Foto Produk & Mapping Warna</p>
                                <p class="section-sub">Upload foto lalu pilih warna yang sesuai untuk setiap foto</p>
                            </div>
                            <button type="button" onclick="addImageRow()"
                                style="font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; padding: 8px 18px; border-radius: 8px; border: 1px solid var(--primary); background: transparent; color: var(--primary); cursor: pointer;">
                                + Tambah Slot Foto
                            </button>
                        </div>

                        <div id="image-container" style="display: flex; flex-direction: column; gap: 12px;">
                            <div class="flex flex-col md:flex-row gap-4 image-row items-center">
                                <div style="flex: 1;">
                                    <label class="field-label">File Foto</label>
                                    <input type="file" name="images[]" accept="image/*"
                                        style="width: 100%; font-size: 11px; color: var(--olive);"
                                        required />
                                </div>
                                <div style="width: 220px; min-width: 180px;">
                                    <label class="field-label">Mapping Warna Foto</label>
                                    {{-- PERBAIKAN: Pastikan name="image_colors[]" ada dan class color-selector benar --}}
                                    <select name="image_colors[]" class="color-selector form-input">
                                        <option value="">— Pilih Warna Foto —</option>
                                    </select>
                                </div>
                                <div style="padding-top: 18px;">
                                    <button type="button" onclick="removeImageRow(this)" class="remove-btn">×</button>
                                </div>
                            </div>
                        </div>

                        <p style="font-size: 9px; color: var(--olive); margin-top: 12px; font-style: italic; text-transform: uppercase; letter-spacing: 0.08em;">
                            * Isi nama warna di bagian Varian terlebih dahulu agar opsi mapping muncul di sini.
                        </p>
                    </div>

                    {{-- Submit --}}
                    <div style="padding-top: 8px;">
                        <button type="submit"
                            style="width: 100%; padding: 18px; border-radius: 10px; border: none; font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.35em; cursor: pointer; transition: background 0.2s; box-shadow: 0 8px 24px rgba(47,53,38,0.25);"
                            class="btn-primary">
                            Simpan & Publikasi Produk
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        // ─── KONFIGURASI UKURAN ───────────────────────────────────────────
        const sizes = {
            standard: ['S', 'M', 'L', 'XL', 'XXL', 'All Size'],
            kids: ['3 - 4 Years', '5 - 6 Years', '7 - 8 Years', '9 - 10 Years', '11 - 12 Years']
        };

        // ─── AUTO HARGA TAMBAHAN (KIDS) ───────────────────────────────────
        function autoCalculatePrice(selectElement) {
            const row = selectElement.closest('.variant-row');
            if (!row) return;
            const addPriceInput = row.querySelector('.additional-price-input');
            const categorySelect = document.getElementById('category_select');
            const selectedOption = categorySelect.options[categorySelect.selectedIndex];
            const type = selectedOption ? selectedOption.getAttribute('data-type') : 'standard';

            if (type === 'kids') {
                const bigSizes = ['7 - 8 Years', '9 - 10 Years', '11 - 12 Years'];
                addPriceInput.value = bigSizes.includes(selectElement.value) ? 20000 : 0;
            }
        }

        // ─── UPDATE SEMUA SIZE SELECTOR ──────────────────────────────────
        function updateAllSizeSelectors() {
            const categorySelect = document.getElementById('category_select');
            if (!categorySelect) return;
            const selectedOption = categorySelect.options[categorySelect.selectedIndex];
            const slug = selectedOption ? selectedOption.getAttribute('data-slug') : '';
            const type = selectedOption ? selectedOption.getAttribute('data-type') : 'standard';

            const isKids = type === 'kids' || (slug && (slug.includes('kids') || slug.includes('anak')));
            const currentSizes = isKids ? sizes.kids : sizes.standard;

            document.querySelectorAll('.size-selector').forEach(select => {
                const prevValue = select.value;
                select.innerHTML = '';
                currentSizes.forEach(size => {
                    const option = document.createElement('option');
                    option.value = size;
                    option.textContent = size;
                    if (size === prevValue) option.selected = true;
                    select.appendChild(option);
                });
                autoCalculatePrice(select);
            });
        }

        // ─── TAMBAH / HAPUS BARIS VARIAN ─────────────────────────────────
        function addVariantRow() {
            const container = document.getElementById('variant-container');
            const firstRow = container.querySelector('.variant-row');
            const newRow = firstRow.cloneNode(true);

            newRow.querySelectorAll('input').forEach(input => {
                if (input.name === 'additional_price[]') {
                    input.value = 0;
                } else {
                    input.value = '';
                }
            });

            const colorInput = newRow.querySelector('.variant-color-input');
            colorInput.setAttribute('oninput', 'updateColorOptions()');

            const sizeSelect = newRow.querySelector('.size-selector');
            sizeSelect.setAttribute('onchange', 'autoCalculatePrice(this)');

            container.appendChild(newRow);
            updateAllSizeSelectors();
            updateColorOptions();
        }

        function removeVariantRow(btn) {
            const rows = document.querySelectorAll('.variant-row');
            if (rows.length > 1) {
                btn.closest('.variant-row').remove();
                updateColorOptions();
            } else {
                alert('Minimal harus ada 1 varian.');
            }
        }

        // ─── UPDATE OPSI WARNA DI SEMUA COLOR SELECTOR ───────────────────
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
                select.innerHTML = '<option value="">— Pilih Warna Foto —</option>';
                colors.forEach(color => {
                    const option = document.createElement('option');
                    option.value = color;
                    option.textContent = color;
                    if (color === currentValue) option.selected = true;
                    select.appendChild(option);
                });
            });
        }

        // ─── TAMBAH / HAPUS BARIS FOTO ───────────────────────────────────
        function addImageRow() {
            const container = document.getElementById('image-container');
            const rows = container.querySelectorAll('.image-row');
            const firstRow = rows[0];
            const newRow = firstRow.cloneNode(true);

            // Reset input file
            const fileInput = newRow.querySelector('input[type="file"]');
            fileInput.value = '';
            fileInput.removeAttribute('required');

            // Reset select mapping warna dan pastikan name-nya benar
            const newSelect = newRow.querySelector('.color-selector');
            newSelect.name = 'image_colors[]'; // Re-confirm name
            
            // Salin opsi warna yang tersedia saat ini ke baris baru
            const refSelect = document.querySelector('.color-selector');
            newSelect.innerHTML = refSelect.innerHTML;
            newSelect.value = '';

            container.appendChild(newRow);
        }

        function removeImageRow(btn) {
            const rows = document.querySelectorAll('.image-row');
            if (rows.length > 1) {
                btn.closest('.image-row').remove();
            } else {
                alert('Minimal harus ada 1 slot foto.');
            }
        }

        // ─── INISIALISASI ─────────────────────────────────────────────────
        document.getElementById('variant-container').addEventListener('input', function(e) {
            if (e.target.classList.contains('variant-color-input')) {
                updateColorOptions();
            }
        });

        document.addEventListener('DOMContentLoaded', function () {
            updateAllSizeSelectors();
            updateColorOptions();
        });
    </script>
</x-app-layout>
