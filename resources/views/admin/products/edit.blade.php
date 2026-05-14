<x-app-layout>
<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 tracking-tight italic uppercase">Edit Produk: {{ $product->name }}</h2>
</x-slot>

<style>
    :root {
        --primary: #2F3526;
        --white: #FFFFFF;
        --black: #000000;
        --olive: #6B705C;
        --light-gray: #E9E9E9;
    }
    .btn-primary { background-color: var(--primary); color: var(--white); }
    .btn-primary:hover { background-color: #1e2218; }
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
        box-shadow: 0 0 0 2px rgba(47,53,38,0.1);
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
    .section-card {
        background: var(--white);
        border: 1px solid var(--light-gray);
        border-radius: 12px;
        padding: 24px;
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
        margin-bottom: 16px;
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
    .remove-btn:hover { background: #ef4444; color: white; }
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
    .img-card {
        border: 1px solid var(--light-gray);
        border-radius: 10px;
        padding: 12px;
        background: var(--white);
        transition: border-color 0.15s;
    }
    .img-card.primary {
        border-color: var(--primary);
        box-shadow: 0 0 0 1px var(--primary);
    }
</style>

<div class="py-10 px-4" style="background: #f8f8f5; min-height: 100vh;">
    <div class="max-w-5xl mx-auto">

        {{-- Header --}}
        <div class="page-header">
            <div>
                <h2 style="font-size: 16px; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; margin: 0;">Edit Produk</h2>
                <p style="font-size: 9px; color: #b8c4a0; text-transform: uppercase; letter-spacing: 0.1em; margin: 4px 0 0;">{{ $product->name }}</p>
            </div>
            <a href="{{ route('admin.products.index') }}"
                style="font-size: 9px; background: rgba(255,255,255,0.12); color: #c8d4b0; padding: 6px 14px; border-radius: 20px; font-weight: 700; letter-spacing: 0.12em; text-transform: uppercase; text-decoration: none;">
                ← Kembali
            </a>
        </div>

        <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" id="main-form">
            @csrf
            @method('PUT')

            <div style="display: flex; flex-direction: column; gap: 20px;">

                {{-- 1. Informasi Dasar --}}
                <div class="section-card">
                    <p class="section-title">① Informasi Dasar</p>
                    <p class="section-sub">Nama, kategori, size guide & tag</p>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        <div>
                            <label class="field-label">Nama Produk</label>
                            <input type="text" name="name" value="{{ old('name', $product->name) }}" class="form-input" required>
                        </div>
                        <div>
                            <label class="field-label">Kategori</label>
                            <select name="category_id" id="category_id" onchange="toggleSizeOptions()" class="form-input">
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}"
                                        data-slug="{{ Str::slug($cat->name) }}"
                                        {{ $product->category_id == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="field-label" style="color: #5A5A00;">Size Guide Template</label>
                            <select name="size_guide_template_id" class="form-input">
                                <option value="">-- No Size Guide --</option>
                                @foreach($sizeGuides as $guide)
                                    <option value="{{ $guide->id }}" {{ $product->size_guide_template_id == $guide->id ? 'selected' : '' }}>
                                        {{ $guide->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                {{-- 2. Harga & Tag --}}
                <div class="section-card">
                    <p class="section-title">② Harga & Tag</p>
                    <p class="section-sub">Harga dasar dan custom tag produk</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="field-label">Harga Dasar (Rp)</label>
                            <div style="position: relative;">
                                <span style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); font-size: 11px; color: var(--olive); font-weight: 600;">Rp</span>
                                <input type="number" name="price" value="{{ old('price', $product->price) }}"
                                    class="form-input" style="padding-left: 36px;" required>
                            </div>
                            <p style="font-size: 9px; color: var(--olive); margin-top: 4px; font-style: italic;">*Harga ini ditambah Additional Price varian jika ada.</p>
                        </div>
                        <div>
                            <label class="field-label">Custom Tag <span style="font-weight: 400; opacity: 0.6;">(Opsional)</span></label>
                            <input type="text" name="custom_tag" value="{{ old('custom_tag', $product->custom_tag) }}"
                                placeholder="Misal: Best Seller" class="form-input">
                        </div>
                    </div>
                </div>

                {{-- 3. Status & Countdown --}}
                <div class="section-card">
                    <p class="section-title">③ Label & Rilis</p>
                    <p class="section-sub">Pre-order, limited edition & countdown</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div style="display: flex; gap: 20px; align-items: center;">
                            <label style="display: flex; align-items: center; cursor: pointer; gap: 8px;">
                                <input type="checkbox" name="is_preorder" value="1"
                                    {{ $product->is_preorder ? 'checked' : '' }}
                                    style="width: 15px; height: 15px; accent-color: var(--primary);">
                                <span style="font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: var(--primary);">Pre-Order</span>
                            </label>
                            <label style="display: flex; align-items: center; cursor: pointer; gap: 8px;">
                                <input type="checkbox" name="is_limited" value="1"
                                    {{ $product->is_limited ? 'checked' : '' }}
                                    style="width: 15px; height: 15px; accent-color: var(--primary);">
                                <span style="font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: var(--primary);">Limited Edition</span>
                            </label>
                        </div>
                        <div>
                            <label class="field-label">Tanggal Rilis (Countdown)</label>
                            <input type="datetime-local" name="release_date"
                                value="{{ $product->release_date ? \Carbon\Carbon::parse($product->release_date)->format('Y-m-d\TH:i') : '' }}"
                                class="form-input" style="font-size: 12px;">
                        </div>
                    </div>
                </div>

                {{-- 4. Varian --}}
                <div class="section-card">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px;">
                        <div>
                            <p class="section-title">④ Varian & Stok</p>
                            <p class="section-sub">Warna, ukuran, stok & harga tambahan</p>
                        </div>
                        <button type="button" onclick="addVariantRow()"
                            style="font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; padding: 8px 18px; border-radius: 8px; border: none; cursor: pointer;"
                            class="btn-primary">
                            + Tambah Varian
                        </button>
                    </div>

                    <div id="variant-container" style="display: flex; flex-direction: column; gap: 10px;">
                        @forelse($product->variants as $variant)
                            <div class="grid grid-cols-1 md:grid-cols-12 gap-3 variant-row items-end">
                                <input type="hidden" name="variant_ids[]" value="{{ $variant->id }}">

                                <div class="md:col-span-3">
                                    <label class="field-label">Warna</label>
                                    <input type="text" name="variant_color[]" value="{{ $variant->color }}"
                                        oninput="updateColorOptions()"
                                        class="variant-color-input form-input" required>
                                </div>

                                <div class="md:col-span-3">
                                    <label class="field-label">Ukuran</label>
                                    <select name="variant_size[]"
                                        class="variant-size-select form-input"
                                        data-selected="{{ $variant->size }}">
                                    </select>
                                </div>

                                <div class="md:col-span-2">
                                    <label class="field-label">Stok</label>
                                    <input type="number" name="variant_stock[]" value="{{ $variant->stock }}"
                                        class="form-input" required>
                                </div>

                                <div class="md:col-span-3">
                                    <label class="field-label">+ Harga</label>
                                    <div style="position: relative;">
                                        <span style="position: absolute; left: 10px; top: 50%; transform: translateY(-50%); font-size: 10px; color: var(--olive);">Rp</span>
                                        <input type="number" name="additional_price[]" value="{{ $variant->additional_price ?? 0 }}"
                                            class="form-input" style="padding-left: 30px;">
                                    </div>
                                </div>

                                <div class="md:col-span-1" style="display: flex; justify-content: center; padding-bottom: 2px;">
                                    <button type="button" onclick="removeVariantRow(this)" class="remove-btn">×</button>
                                </div>
                            </div>
                        @empty
                            <p style="text-align: center; color: var(--olive); font-size: 12px; padding: 16px;">Klik tambah varian untuk memulai.</p>
                        @endforelse
                    </div>
                </div>

                {{-- 5. Deskripsi --}}
                <div class="section-card">
                    <p class="section-title">⑤ Deskripsi Produk</p>
                    <p class="section-sub">Detail bahan, jahitan, kualitas produk</p>
                    <textarea name="description" rows="5" class="form-input" style="resize: vertical;">{{ old('description', $product->description) }}</textarea>
                </div>

                {{-- 6. Galeri Foto --}}
                <div class="section-card">
                    <p class="section-title">⑥ Galeri Foto Produk</p>
                    <p class="section-sub" style="margin-bottom: 20px;">Kelola foto & mapping warna yang sudah ada</p>

                    {{-- Foto yang sudah ada --}}
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                        @foreach($product->images as $img)
                            <div class="img-card {{ $img->is_primary ? 'primary' : '' }}">
                                @if($img->is_primary)
                                    <div style="font-size: 8px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.1em; color: var(--primary); background: #e8ede0; padding: 3px 8px; border-radius: 4px; display: inline-block; margin-bottom: 8px;">
                                        ★ Utama
                                    </div>
                                @endif

                                <img src="{{ asset('storage/' . $img->image_path) }}"
                                    style="width: 100%; height: 140px; object-fit: cover; border-radius: 8px; margin-bottom: 10px;">

                                <div style="margin-bottom: 10px;">
                                    <label class="field-label">Mapping Warna:</label>
                                    <input type="hidden" name="existing_image_ids[]" value="{{ $img->id }}">
                                    <select name="existing_image_colors[]"
                                        class="color-selector form-input"
                                        style="font-size: 11px; padding: 6px 10px;"
                                        data-selected="{{ $img->color }}">
                                        <option value="">Global / No Color</option>
                                    </select>
                                </div>

                                <div style="display: flex; gap: 6px;">
                                    @if(!$img->is_primary)
                                        <button type="button"
                                            onclick="submitHiddenForm('set-primary-{{ $img->id }}')"
                                            style="flex: 1; font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; padding: 7px; border-radius: 6px; border: 1px solid var(--light-gray); background: var(--white); color: var(--primary); cursor: pointer; transition: all 0.15s;"
                                            onmouseover="this.style.background='var(--primary)';this.style.color='white'"
                                            onmouseout="this.style.background='var(--white)';this.style.color='var(--primary)'">
                                            Set Utama
                                        </button>
                                    @endif
                                    <button type="button"
                                        onclick="if(confirm('Hapus foto ini?')) submitHiddenForm('delete-img-{{ $img->id }}')"
                                        style="background: #fef2f2; color: #ef4444; border: 1px solid #fecaca; border-radius: 6px; padding: 7px 10px; cursor: pointer; transition: all 0.15s; font-size: 11px; line-height: 1;"
                                        onmouseover="this.style.background='#ef4444';this.style.color='white'"
                                        onmouseout="this.style.background='#fef2f2';this.style.color='#ef4444'">
                                        ×
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Upload Foto Baru --}}
                    <div style="background: #f8f8f5; border: 2px dashed var(--light-gray); border-radius: 12px; padding: 24px;">
                        <p class="section-title" style="margin-bottom: 4px;">Tambah Foto Baru</p>
                        <p class="section-sub" style="margin-bottom: 16px;">Upload foto tambahan & mapping ke warna varian</p>

                        <div id="new-image-container" style="display: flex; flex-direction: column; gap: 12px;">
                            <div class="flex flex-col md:flex-row gap-4 image-row items-center">
                                <div style="flex: 1;">
                                    <label class="field-label">File Foto</label>
                                    <input type="file" name="images[]" accept="image/*"
                                        style="width: 100%; font-size: 11px; color: var(--olive);" />
                                </div>
                                <div style="width: 220px; min-width: 180px;">
                                    <label class="field-label">Mapping Warna</label>
                                    <select name="image_colors_new[]" class="color-selector form-input" style="font-size: 12px;">
                                        <option value="">— Pilih Warna Foto —</option>
                                    </select>
                                </div>
                                <div style="padding-top: 18px;">
                                    <button type="button" onclick="this.closest('.image-row').remove()" class="remove-btn">×</button>
                                </div>
                            </div>
                        </div>

                        <button type="button" onclick="addNewImageRow()"
                            style="margin-top: 14px; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; color: var(--primary); background: none; border: none; cursor: pointer; text-decoration: underline;">
                            + Tambah Slot Foto Baru
                        </button>
                    </div>
                </div>

                {{-- Submit --}}
                <div style="display: flex; justify-content: flex-end; align-items: center; gap: 16px; padding-top: 8px;">
                    <a href="{{ route('admin.products.index') }}"
                        style="font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; color: var(--olive); text-decoration: none;">
                        Batal
                    </a>
                    <button type="submit"
                        style="padding: 14px 40px; border-radius: 10px; border: none; font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.3em; cursor: pointer; transition: background 0.2s; box-shadow: 0 8px 24px rgba(47,53,38,0.25);"
                        class="btn-primary">
                        Update & Simpan Perubahan
                    </button>
                </div>

            </div>
        </form>

        {{-- Hidden Forms --}}
        @foreach($product->images as $img)
            <form id="set-primary-{{ $img->id }}" action="{{ route('admin.products.images.setPrimary', $img->id) }}" method="POST" class="hidden">
                @csrf @method('PATCH')
            </form>
            <form id="delete-img-{{ $img->id }}" action="{{ route('admin.products.images.destroy', $img->id) }}" method="POST" class="hidden">
                @csrf @method('DELETE')
            </form>
        @endforeach
    </div>
</div>

<script>
    const ADULT_SIZES = ['S', 'M', 'L', 'XL', 'XXL', 'All Size'];
    const KIDS_SIZES = ['3 - 4 Years', '5 - 6 Years', '7 - 8 Years', '9 - 10 Years', '11 - 12 Years'];

    function submitHiddenForm(formId) {
        const form = document.getElementById(formId);
        if (form) form.submit();
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
                if (size === currentVal) option.selected = true;
                select.appendChild(option);
            });
            select.setAttribute('data-selected', select.value);
        });
    }

    function addVariantRow() {
        const container = document.getElementById('variant-container');
        const html = `
            <div class="grid grid-cols-1 md:grid-cols-12 gap-3 variant-row items-end">
                <input type="hidden" name="variant_ids[]" value="">
                <div class="md:col-span-3">
                    <label class="field-label">Warna</label>
                    <input type="text" name="variant_color[]" oninput="updateColorOptions()" placeholder="Warna"
                        class="variant-color-input form-input" required>
                </div>
                <div class="md:col-span-3">
                    <label class="field-label">Ukuran</label>
                    <select name="variant_size[]" class="variant-size-select form-input"></select>
                </div>
                <div class="md:col-span-2">
                    <label class="field-label">Stok</label>
                    <input type="number" name="variant_stock[]" placeholder="0" class="form-input" required>
                </div>
                <div class="md:col-span-3">
                    <label class="field-label">+ Harga</label>
                    <div style="position: relative;">
                        <span style="position: absolute; left: 10px; top: 50%; transform: translateY(-50%); font-size: 10px; color: var(--olive);">Rp</span>
                        <input type="number" name="additional_price[]" value="0" class="form-input" style="padding-left: 30px;">
                    </div>
                </div>
                <div class="md:col-span-1" style="display: flex; justify-content: center; padding-bottom: 2px;">
                    <button type="button" onclick="removeVariantRow(this)" class="remove-btn">×</button>
                </div>
            </div>`;
        container.insertAdjacentHTML('beforeend', html);
        toggleSizeOptions();
        updateColorOptions();
    }

    function removeVariantRow(btn) {
        const rows = document.querySelectorAll('.variant-row');
        if (rows.length > 1) {
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
        // Salin opsi warna yang sudah ada
        const refSelect = document.querySelector('#new-image-container .color-selector');
        select.innerHTML = refSelect.innerHTML;
        select.value = '';
        select.removeAttribute('data-selected');
        container.appendChild(newRow);
    }

    function updateColorOptions() {
        const colorInputs = document.querySelectorAll('.variant-color-input');
        let colors = [];

        colorInputs.forEach(input => {
            const val = input.value.trim();
            if (val && !colors.includes(val)) colors.push(val);
        });

        const selectors = document.querySelectorAll('.color-selector');
        selectors.forEach(select => {
            const userChoice = select.value;
            const initialVal = select.getAttribute('data-selected');
            const activeValue = userChoice || initialVal;

            const isNewImage = select.name === 'image_colors_new[]';
            select.innerHTML = `<option value="">${isNewImage ? '— Pilih Warna Foto —' : 'Global / No Color'}</option>`;

            colors.forEach(color => {
                const option = document.createElement('option');
                option.value = color;
                option.textContent = color;
                if (color === activeValue) option.selected = true;
                select.appendChild(option);
            });
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        toggleSizeOptions();
        updateColorOptions();

        // Simpan pilihan user di data-selected supaya tidak hilang saat updateColorOptions
        document.addEventListener('change', function (e) {
            if (e.target.classList.contains('color-selector')) {
                e.target.setAttribute('data-selected', e.target.value);
            }
        });
    });
</script>
</x-app-layout>
