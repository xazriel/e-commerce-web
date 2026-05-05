<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <meta name="theme-color" content="#2F3526">
    <title>{{ $product->name }} — Farhana</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root {
            --primary:  #2F3526;
            --olive:    #6B705C;
            --gray:     #E9E9E9;
            --white:    #FFFFFF;
            --black:    #000000;
            --p10:      rgba(47,53,38,.10);
            --p20:      rgba(47,53,38,.20);
            --o60:      rgba(107,112,92,.60);
            --w80:      rgba(255,255,255,.80);
            --w50:      rgba(255,255,255,.50);
            --w12:      rgba(255,255,255,.12);
            --red:      #C0392B;
            --t:        .25s ease;
        }

        *,*::before,*::after { box-sizing:border-box; margin:0; padding:0 }
        html { scroll-behavior:smooth; -webkit-text-size-adjust:100% }
        body {
            font-family: Helvetica, Arial, sans-serif;
            background:var(--white); color:var(--black);
            -webkit-font-smoothing:antialiased; overflow-x:hidden;
            font-size:14px;
        }
        img { display:block; max-width:100% }
        button { font-family:inherit; border:none; background:none; cursor:pointer }
        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button { -webkit-appearance:none }
        a { text-decoration:none; color:inherit }

        /* ── NAVBAR ── */
        .navbar {
            position:sticky; top:0; z-index:100;
            background:var(--white); border-bottom:1px solid var(--gray);
        }
        .navbar-inner {
            max-width:1400px; margin:0 auto; padding:0 1.25rem;
            height:68px; display:flex; align-items:center; justify-content:space-between;
            position:relative;
        }
        .back-btn {
            display:flex; align-items:center; gap:.4rem;
            font-size:10px; letter-spacing:.22em; text-transform:uppercase;
            color:var(--o60); transition:color var(--t);
        }
        .back-btn svg { transition:transform var(--t); flex-shrink:0 }
        .back-btn:hover { color:var(--primary) }
        .back-btn:hover svg { transform:translateX(-3px) }
        .brand {
            font-size:1.25rem; font-weight:400; letter-spacing:.45em;
            text-transform:uppercase; color:var(--primary);
            position:absolute; left:50%; transform:translateX(-50%);
            white-space:nowrap;
        }
        .success-toast {
            background:var(--primary); color:var(--white);
            font-size:10px; letter-spacing:.2em; text-transform:uppercase;
            text-align:center; padding:.6rem;
        }

        /* ── FAB ── */
        .wa-fab {
            position:fixed; bottom:24px; right:20px;
            width:50px; height:50px; background:var(--primary); color:var(--white);
            border-radius:50%; display:flex; align-items:center; justify-content:center;
            font-size:21px; box-shadow:0 6px 20px rgba(47,53,38,.4);
            z-index:200; transition:transform var(--t),background var(--t);
        }
        .wa-fab:hover { transform:scale(1.1); background:var(--olive) }

        /* ── PAGE ── */
        .page { max-width:1400px; margin:0 auto; padding:1.5rem 1.25rem 5rem }

        /* ── PRODUCT GRID ── */
        .product-grid { display:grid; grid-template-columns:1fr; gap:2.5rem }
        @media(min-width:900px) {
            .product-grid { grid-template-columns:1fr 1fr; gap:3.5rem; align-items:start }
        }

        /* ── GALLERY ── */
        .gallery-hero {
            position:relative; overflow:hidden; background:var(--gray);
            aspect-ratio:3/4; user-select:none; touch-action:pan-y;
        }
        .gallery-hero img {
            width:100%; height:100%; object-fit:cover;
            transition:opacity .3s ease; pointer-events:none;
        }
        .g-arrow {
            position:absolute; top:50%; transform:translateY(-50%);
            width:40px; height:40px; background:rgba(255,255,255,.72);
            border:1px solid var(--p20); border-radius:50%;
            display:none; align-items:center; justify-content:center;
            color:var(--primary); font-size:16px; z-index:10;
            transition:background var(--t),color var(--t); backdrop-filter:blur(3px);
        }
        .g-arrow:hover { background:var(--primary); color:var(--white) }
        .g-arrow.prev { left:12px }
        .g-arrow.next { right:12px }
        @media(min-width:640px) { .g-arrow { display:flex } }

        .g-dots {
            display:flex; justify-content:center; gap:5px; margin-top:.9rem;
        }
        @media(min-width:640px) { .g-dots { display:none } }
        .g-dot {
            height:5px; border-radius:99px; background:var(--gray);
            transition:width var(--t),background var(--t); width:5px;
        }
        .g-dot.active { width:16px; background:var(--primary) }

        .g-thumbs {
            display:none; grid-template-columns:repeat(5,1fr);
            gap:6px; margin-top:.9rem;
        }
        @media(min-width:640px) { .g-thumbs { display:grid } }
        .thumb {
            cursor:pointer; position:relative; overflow:hidden;
            background:var(--gray); aspect-ratio:1/1;
            border:2px solid transparent; transition:border-color var(--t);
        }
        .thumb.active,.thumb:hover { border-color:var(--primary) }
        .thumb img { width:100%; height:100%; object-fit:cover; transition:transform .5s ease }
        .thumb:hover img { transform:scale(1.05) }

        /* ── INFO ── */
        .info-sticky { position:sticky; top:82px }

        /* Badges */
        .badge-row { display:flex; flex-wrap:wrap; align-items:center; gap:.4rem; margin-bottom:1rem }
        .badge {
            display:inline-flex; align-items:center;
            font-size:9px; font-weight:700; letter-spacing:.2em;
            text-transform:uppercase; padding:3px 10px;
        }
        .badge-po  { background:var(--primary); color:var(--white) }
        .badge-tag { background:var(--gray); color:var(--primary); border:1px solid var(--p20) }
        .badge-cat { background:var(--p10); color:var(--primary); border:1px solid var(--p20); border-radius:99px }

        /* Title & Price */
        .product-name {
            font-size:clamp(1.55rem,3vw,2.3rem); font-weight:300;
            letter-spacing:.09em; color:var(--primary); line-height:1.25;
            margin-bottom:.75rem;
        }
        .product-price {
            font-size:1rem; font-weight:400; letter-spacing:.1em;
            color:var(--black); margin-bottom:1.5rem;
        }
        hr.div { border:none; border-top:1px solid var(--gray); margin:1.25rem 0 }

        /* Countdown */
        .countdown-wrap {
            background:var(--p10); border:1px solid var(--p20);
            padding:1rem 1.25rem; margin-bottom:1.5rem;
        }
        .cd-label {
            font-size:9px; letter-spacing:.22em; text-transform:uppercase;
            color:var(--olive); margin-bottom:.75rem;
        }
        .cd-row { display:flex; gap:1.25rem }
        .cd-unit { text-align:center }
        .cd-num { font-size:1.75rem; font-weight:300; color:var(--primary); display:block; line-height:1 }
        .cd-lbl { display:block; font-size:8px; letter-spacing:.18em; text-transform:uppercase; color:var(--o60); margin-top:3px }

        /* Form sections */
        .sec-label { font-size:10px; font-weight:700; letter-spacing:.22em; text-transform:uppercase; color:var(--primary) }
        .form-sec { margin-bottom:1.25rem }
        .form-sec-head { display:flex; justify-content:space-between; align-items:center; margin-bottom:.6rem }

        /* Variant options — shared */
        .v-opts { display:flex; flex-wrap:wrap; gap:.5rem }
        .v-opt { cursor:pointer }
        .v-opt input { position:absolute; opacity:0; pointer-events:none }
        .v-opt span {
            display:flex; align-items:center; justify-content:center;
            padding:7px 16px; border:1px solid var(--gray);
            font-size:10px; letter-spacing:.12em; text-transform:uppercase;
            color:var(--black); transition:border-color var(--t),background var(--t),color var(--t);
            white-space:nowrap;
        }
        .v-opt:hover span { border-color:var(--primary) }
        .v-opt input:checked + span {
            border-color:var(--primary); background:var(--primary); color:var(--white);
        }
        /* disabled */
        .v-opt.off span {
            opacity:.32; cursor:not-allowed; text-decoration:line-through;
            background:var(--gray); pointer-events:none;
        }
        /* size — square */
        .sz-opt span { min-width:46px; height:46px; padding:0 8px; font-weight:500 }

        .guide-btn {
            font-size:9px; letter-spacing:.15em; text-transform:uppercase;
            color:var(--olive); border-bottom:1px solid var(--gray); padding-bottom:1px;
            transition:color var(--t),border-color var(--t);
        }
        .guide-btn:hover { color:var(--primary); border-color:var(--primary) }

        /* Stock badge */
        .stock-badge {
            display:inline-flex; align-items:center; gap:5px;
            font-size:9px; letter-spacing:.15em; text-transform:uppercase;
        }
        .stock-dot { width:6px; height:6px; border-radius:50%; flex-shrink:0 }
        .stock-ok   .stock-dot { background:#4CAF50 }
        .stock-low  .stock-dot { background:#FF9800 }
        .stock-out  .stock-dot { background:var(--red) }
        .stock-ok   { color:var(--olive) }
        .stock-low  { color:#B56A00 }
        .stock-out  { color:var(--red) }

        /* Quantity */
        .qty-wrap {
            display:inline-flex; align-items:center;
            border:1px solid var(--gray); height:46px;
        }
        .qty-btn {
            width:44px; height:100%; display:flex; align-items:center; justify-content:center;
            font-size:19px; font-weight:300; color:var(--olive);
            transition:color var(--t),background var(--t); flex-shrink:0; user-select:none;
        }
        .qty-btn:hover:not(:disabled) { color:var(--primary); background:var(--p10) }
        .qty-btn:disabled { opacity:.3; cursor:not-allowed }
        .qty-input {
            width:48px; text-align:center; border:none;
            border-left:1px solid var(--gray); border-right:1px solid var(--gray);
            height:100%; font-family:inherit; font-size:13px;
            color:var(--black); background:var(--white); outline:none;
        }

        /* Validation */
        .v-msg {
            font-size:10px; font-weight:700; letter-spacing:.15em;
            text-transform:uppercase; color:var(--red);
            padding:.5rem 0;
        }
        @keyframes fadeDown { from{opacity:0;transform:translateY(-5px)} to{opacity:1;transform:translateY(0)} }
        .anim { animation:fadeDown .25s ease forwards }

        /* Buttons */
        .btn-row { display:flex; flex-direction:column; gap:.6rem; margin-top:1.25rem }
        .btn {
            width:100%; height:50px; display:flex; align-items:center; justify-content:center;
            font-family:inherit; font-size:10px; font-weight:700; letter-spacing:.28em;
            text-transform:uppercase; transition:background var(--t),color var(--t),border-color var(--t),opacity var(--t);
            cursor:pointer;
        }
        .btn:disabled { opacity:.45; cursor:not-allowed }
        .btn-primary { background:var(--primary); color:var(--white); border:1px solid var(--primary) }
        .btn-primary:hover:not(:disabled) { background:#3b4430; border-color:#3b4430 }
        .btn-outline { background:var(--white); color:var(--primary); border:1px solid var(--primary) }
        .btn-outline:hover:not(:disabled) { background:var(--p10) }

        /* Description */
        .desc-block { border-top:1px solid var(--gray); padding-top:1.75rem; margin-top:1.75rem }
        .desc-title { font-size:10px; font-weight:700; letter-spacing:.28em; text-transform:uppercase; color:var(--primary); margin-bottom:1rem }
        .desc-body { font-size:11px; letter-spacing:.05em; line-height:2; color:var(--o60); text-transform:uppercase }

        /* ── RELATED ── */
        .related-sec { border-top:1px solid var(--gray); padding-top:3.5rem; margin-top:4.5rem }
        .related-h { font-size:clamp(1.2rem,2.5vw,1.8rem); font-weight:300; letter-spacing:.4em; text-transform:uppercase; text-align:center; color:var(--primary); margin-bottom:2.5rem }
        .related-scroll { overflow-x:auto; scrollbar-width:none; -ms-overflow-style:none }
        .related-scroll::-webkit-scrollbar { display:none }
        .related-track { display:flex; gap:1.25rem; min-width:max-content; padding-bottom:.5rem }
        @media(min-width:1024px) { .related-track { display:grid; grid-template-columns:repeat(5,1fr); min-width:0 } }
        .r-card { width:170px; flex-shrink:0; display:block }
        @media(min-width:1024px) { .r-card { width:auto } }
        .r-card-img { aspect-ratio:2/3; overflow:hidden; background:var(--gray); margin-bottom:.6rem }
        .r-card-img img { width:100%; height:100%; object-fit:cover; transition:transform .6s ease }
        .r-card:hover .r-card-img img { transform:scale(1.04) }
        .r-card-name { font-size:10px; letter-spacing:.16em; text-transform:uppercase; color:var(--primary); margin-bottom:3px }
        .r-card-price { font-size:10px; letter-spacing:.09em; color:var(--olive) }

        /* ── FOOTER ── */
        footer { background:var(--primary); color:var(--white); padding-top:3.5rem }
        .footer-inner { max-width:1400px; margin:0 auto; padding:0 1.25rem 3.5rem }
        .footer-grid { display:grid; grid-template-columns:1fr; gap:2.5rem }
        @media(min-width:600px)  { .footer-grid { grid-template-columns:1fr 1fr } }
        @media(min-width:1024px) { .footer-grid { grid-template-columns:1.5fr 1fr 1fr } }
        .f-title { font-size:10px; font-weight:700; letter-spacing:.28em; text-transform:uppercase; color:var(--white); margin-bottom:1rem }
        .f-text  { font-size:11px; letter-spacing:.07em; line-height:1.9; color:var(--w80) }
        .f-link  { display:block; font-size:11px; letter-spacing:.11em; text-transform:uppercase; color:var(--w80); margin-bottom:.65rem; transition:color var(--t) }
        .f-link:hover { color:var(--white) }
        .f-social { display:flex; align-items:center; gap:.65rem }
        .f-icon {
            width:36px; height:36px; border-radius:50%; background:var(--white);
            color:var(--primary); display:flex; align-items:center; justify-content:center;
            transition:background var(--t);
        }
        .f-icon:hover { background:var(--gray) }
        .f-handle { font-size:10px; letter-spacing:.11em; color:var(--w80) }
        .f-bottom { border-top:1px solid var(--w12); padding:.9rem 1.25rem; text-align:center }
        .f-copy { font-size:9px; letter-spacing:.3em; text-transform:uppercase; color:var(--w50) }

        /* ── MODAL ── */
        .modal {
            position:fixed; inset:0; z-index:300; background:rgba(0,0,0,.55);
            backdrop-filter:blur(5px); display:flex; align-items:center;
            justify-content:center; padding:1rem;
        }
        .modal-box {
            background:var(--white); max-width:460px; width:100%;
            max-height:92vh; overflow-y:auto; scrollbar-width:thin;
        }
        .modal-head {
            position:sticky; top:0; background:var(--white);
            border-bottom:1px solid var(--gray); padding:.9rem 1.25rem;
            display:flex; align-items:center; justify-content:space-between; z-index:1;
        }
        .modal-title { font-size:10px; font-weight:700; letter-spacing:.28em; text-transform:uppercase; color:var(--primary) }
        .modal-close {
            width:30px; height:30px; display:flex; align-items:center; justify-content:center;
            color:var(--primary); font-size:22px; font-weight:300; line-height:1; transition:opacity var(--t);
        }
        .modal-close:hover { opacity:.5 }
        .modal-body { padding:1.25rem }
        .modal-body img { width:100%; height:auto }

        .hidden { display:none !important }
    </style>
</head>
<body>

    {{-- WhatsApp FAB --}}
    <a href="https://wa.me/628123456789?text=Halo%20Farhana,%20saya%20tertarik%20dengan%20produk%20{{ urlencode($product->name) }}"
       class="wa-fab" target="_blank" rel="noopener" aria-label="Chat WhatsApp">
        <i class="fab fa-whatsapp"></i>
    </a>

    {{-- Navbar --}}
    <header class="navbar" role="banner">
        <div class="navbar-inner">
            <a href="{{ route('home') }}" class="back-btn" aria-label="Kembali ke koleksi">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="15 18 9 12 15 6"/>
                </svg>
                <span>Collection</span>
            </a>
            <a href="{{ route('home') }}" class="brand" aria-label="Farhana Home">Farhana</a>
            <div style="width:80px" aria-hidden="true"></div>
        </div>
        @if(session('success'))
            <div class="success-toast" role="alert">{{ session('success') }}</div>
        @endif
    </header>

    {{-- Main --}}
    <main class="page" role="main">
        <div class="product-grid">

            {{-- ── GALLERY ── --}}
            <div aria-label="Galeri produk">
                <div id="swipeArea" class="gallery-hero" role="img" aria-label="{{ $product->name }}">
                    <button type="button" class="g-arrow prev" onclick="navigate(-1)" aria-label="Foto sebelumnya">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
                    </button>
                    <img id="mainImage"
                         src="{{ asset('storage/' . ($product->images->where('is_primary', true)->first()->image_path ?? $product->images->first()->image_path)) }}"
                         alt="{{ $product->name }}" loading="eager">
                    <button type="button" class="g-arrow next" onclick="navigate(1)" aria-label="Foto berikutnya">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                    </button>
                </div>

                <div class="g-dots" id="gDots" aria-hidden="true">
                    @foreach($product->images as $i => $img)
                        <div class="g-dot {{ $loop->first ? 'active' : '' }}" data-index="{{ $i }}"></div>
                    @endforeach
                </div>

                <div class="g-thumbs" id="thumbGrid">
                    @foreach($product->images as $i => $img)
                        <button type="button"
                                class="thumb {{ $loop->first ? 'active' : '' }}"
                                data-index="{{ $i }}"
                                data-color="{{ strtolower(trim($img->color ?? '')) }}"
                                onclick="goToImage({{ $i }})"
                                aria-label="Foto {{ $i + 1 }}">
                            <img src="{{ asset('storage/' . $img->image_path) }}" alt="Thumbnail {{ $i + 1 }}" loading="lazy">
                        </button>
                    @endforeach
                </div>
            </div>

            {{-- ── INFO ── --}}
            <div>
                <div class="info-sticky">

                    {{-- Badges --}}
                    <div class="badge-row">
                        @if($product->is_preorder)
                            <span class="badge badge-po">Pre-Order</span>
                        @endif
                        @if($product->custom_tag)
                            <span class="badge badge-tag">{{ $product->custom_tag }}</span>
                        @endif
                        <span class="badge badge-cat">{{ $product->category->name ?? 'Collection' }}</span>
                    </div>

                    <h1 class="product-name">{{ $product->name }}</h1>
                    <p class="product-price">IDR {{ number_format($product->price, 0, ',', '.') }}</p>
                    <hr class="div">

                    {{-- Countdown --}}
                    @if($product->is_preorder && $product->release_date)
                        <div id="cdWrap" data-expire="{{ $product->release_date }}" class="countdown-wrap hidden" role="timer">
                            <p class="cd-label">Pre-Order Berakhir Dalam</p>
                            <div class="cd-row">
                                <div class="cd-unit"><span id="cdD" class="cd-num">00</span><span class="cd-lbl">Hari</span></div>
                                <div class="cd-unit"><span id="cdH" class="cd-num">00</span><span class="cd-lbl">Jam</span></div>
                                <div class="cd-unit"><span id="cdM" class="cd-num">00</span><span class="cd-lbl">Menit</span></div>
                                <div class="cd-unit"><span id="cdS" class="cd-num">00</span><span class="cd-lbl">Detik</span></div>
                            </div>
                        </div>
                    @endif

                    {{-- Form --}}
                    <form action="{{ route('cart.add', $product->id) }}" method="POST" id="cartForm" novalidate>
                        @csrf
                        <input type="hidden" name="variant_id" id="variantId">

                        @php
                            $uniqueColors = $product->variants->pluck('color')->unique();
                            $sizeOrder    = ['XS','S','M','L','XL','XXL','ALL SIZE'];
                            $uniqueSizes  = $product->variants->pluck('size')->unique()->sortBy(
                                fn($s) => (($p = array_search(strtoupper($s), $sizeOrder)) !== false) ? $p : 99
                            );
                            $hasColor = $uniqueColors->count() > 0;
                        @endphp

                        {{-- Color --}}
                        @if($hasColor)
                        <div class="form-sec">
                            <div class="form-sec-head">
                                <span class="sec-label">Pilih Warna</span>
                                <span id="colorHint" class="sec-label" style="font-size:9px;color:var(--red);letter-spacing:.15em;display:none">← Pilih dulu</span>
                            </div>
                            <div class="v-opts" role="group" aria-label="Pilihan warna">
                                @foreach($uniqueColors as $color)
                                    <label class="v-opt">
                                        <input type="radio" name="color" value="{{ $color }}"
                                               onchange="onColorChange('{{ $color }}')">
                                        <span>{{ $color }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        {{-- Size --}}
                        <div class="form-sec">
                            <div class="form-sec-head">
                                <span class="sec-label">Pilih Ukuran</span>
                                <div style="display:flex;align-items:center;gap:.75rem">
                                    <span id="sizeHint" class="sec-label" style="font-size:9px;color:var(--red);letter-spacing:.15em;display:none">← Pilih dulu</span>
                                    @if($product->sizeGuide)
                                        <button type="button" class="guide-btn" onclick="openModal('guideModal')">Panduan Ukuran</button>
                                    @endif
                                </div>
                            </div>
                            <div class="v-opts" id="sizeContainer" role="group" aria-label="Pilihan ukuran">
                                @foreach($uniqueSizes as $size)
                                    <label class="v-opt sz-opt" data-size="{{ $size }}">
                                        <input type="radio" name="size" value="{{ $size }}"
                                               onchange="onSizeChange()"
                                               {{ !$hasColor ? '' : 'disabled' }}>
                                        <span>{{ $size }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- Quantity --}}
                        <div class="form-sec">
                            <div class="form-sec-head">
                                <span class="sec-label">Jumlah</span>
                                <span id="stockBadge" class="stock-badge" aria-live="polite">
                                    <span class="stock-dot"></span>
                                    <span id="stockText">{{ $hasColor ? 'Pilih warna & ukuran' : 'Pilih ukuran' }}</span>
                                </span>
                            </div>
                            <div class="qty-wrap" role="group" aria-label="Jumlah">
                                <button type="button" class="qty-btn" id="qtyMinus" onclick="adjustQty(-1)" aria-label="Kurangi">−</button>
                                <input type="number" id="qtyInput" name="quantity" value="1" min="1" class="qty-input" readonly aria-label="Jumlah">
                                <button type="button" class="qty-btn" id="qtyPlus" onclick="adjustQty(1)" aria-label="Tambah">+</button>
                            </div>
                        </div>

                        <div id="vMsg" class="v-msg hidden anim" role="alert" aria-live="assertive"></div>

                        <div class="btn-row">
                            <button type="button" class="btn btn-outline" id="btnCart" onclick="submit('add')">
                                {{ $product->is_preorder ? 'Pre-Order Sekarang' : 'Tambah ke Keranjang' }}
                            </button>
                            <button type="button" class="btn btn-primary" id="btnBuy" onclick="submit('buy')">
                                Beli Sekarang
                            </button>
                        </div>
                    </form>

                    {{-- Description --}}
                    <div class="desc-block">
                        <p class="desc-title">Deskripsi</p>
                        <div class="desc-body">{!! nl2br(e($product->description)) !!}</div>
                    </div>

                </div>
            </div>
        </div>

        {{-- ── RELATED ── --}}
        @if(isset($relatedProducts) && $relatedProducts->count() > 0)
            <section class="related-sec" aria-label="Produk serupa">
                <h2 class="related-h">You May Also Like</h2>
                <div class="related-scroll">
                    <div class="related-track">
                        @foreach($relatedProducts as $rel)
                            <a href="{{ route('product.details', $rel->slug) }}" class="r-card">
                                <div class="r-card-img">
                                    <img src="{{ asset('storage/' . ($rel->images->where('is_primary', true)->first()->image_path ?? $rel->images->first()->image_path)) }}"
                                         alt="{{ $rel->name }}" loading="lazy">
                                </div>
                                <p class="r-card-name">{{ $rel->name }}</p>
                                <p class="r-card-price">IDR {{ number_format($rel->price, 0, ',', '.') }}</p>
                            </a>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif
    </main>

    {{-- ── FOOTER ── --}}
    <footer id="about" role="contentinfo">
        <div class="footer-inner">
            <div class="footer-grid">
                <div>
                    <p class="f-title">Tentang Farhana</p>
                    <p class="f-text">Eksklusivitas dalam balutan kesantunan. Kami menghadirkan kualitas terbaik untuk gaya Muslim modern yang elegan dan berkelas.</p>
                </div>
                <div>
                    <p class="f-title">Layanan Pelanggan</p>
                    <a href="#" class="f-link">Hubungi Kami</a>
                    <a href="#" class="f-link">Pengiriman &amp; Pengembalian</a>
                    <a href="#" class="f-link">Cara Berbelanja</a>
                    <a href="#" class="f-link">FAQ</a>
                </div>
                <div>
                    <p class="f-title">Ikuti Kami</p>
                    <div class="f-social">
                        <a href="#" class="f-icon" aria-label="Instagram Farhana" rel="noopener" target="_blank">
                            <svg width="17" height="17" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                            </svg>
                        </a>
                        <span class="f-handle">@farhana.official</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="f-bottom">
            <p class="f-copy">&copy; 2026 Farhana Official. All Rights Reserved.</p>
        </div>
    </footer>

    {{-- Size Guide Modal --}}
    @if($product->sizeGuide)
        <div id="guideModal" class="modal hidden" role="dialog" aria-modal="true" aria-labelledby="guideTitle"
             onclick="if(event.target===this)closeModal('guideModal')">
            <div class="modal-box">
                <div class="modal-head">
                    <p class="modal-title" id="guideTitle">Panduan Ukuran</p>
                    <button type="button" class="modal-close" onclick="closeModal('guideModal')" aria-label="Tutup">&times;</button>
                </div>
                <div class="modal-body">
                    <img src="{{ asset('storage/' . $product->sizeGuide->image) }}"
                         alt="Panduan Ukuran {{ $product->name }}" loading="lazy">
                </div>
            </div>
        </div>
    @endif

    <script>
    /* ─── DATA ─── */
    const variants     = @json($product->variants);
    const imgUrls      = @json($product->images->values()->map(fn($i) => asset('storage/'.$i->image_path)));
    const hasColor     = {{ $hasColor ? 'true' : 'false' }};
    let   curIdx       = 0;
    let   maxStock     = 0;   // stok variant yang dipilih

    /* ─── GALLERY ─── */
    function goToImage(idx) {
        if (idx < 0 || idx >= imgUrls.length) return;
        curIdx = idx;
        const main = document.getElementById('mainImage');
        main.style.opacity = '0';
        setTimeout(() => { main.src = imgUrls[idx]; main.style.opacity = '1'; }, 260);
        document.querySelectorAll('.thumb').forEach(t =>
            t.classList.toggle('active', parseInt(t.dataset.index) === idx));
        document.querySelectorAll('#gDots .g-dot').forEach((d, i) =>
            d.classList.toggle('active', i === idx));
    }
    function navigate(dir) {
        goToImage((curIdx + dir + imgUrls.length) % imgUrls.length);
    }

    /* Touch swipe */
    let tx = 0;
    const sa = document.getElementById('swipeArea');
    sa.addEventListener('touchstart', e => tx = e.touches[0].clientX, { passive:true });
    sa.addEventListener('touchend',   e => {
        const d = tx - e.changedTouches[0].clientX;
        if (d > 45) navigate(1); else if (d < -45) navigate(-1);
    }, { passive:true });

    /* ─── COLOR CHANGE ─── */
    function onColorChange(color) {
        // Sync gallery to matching color image
        const thumb = document.querySelector(`.thumb[data-color="${color.toLowerCase().trim()}"]`);
        if (thumb) goToImage(parseInt(thumb.dataset.index));

        // Enable/disable sizes based on selected color
        document.querySelectorAll('.sz-opt').forEach(opt => {
            const v     = variants.find(v => v.color === color && v.size === opt.dataset.size);
            const inp   = opt.querySelector('input');
            const avail = v && parseInt(v.stock) > 0;
            opt.classList.toggle('off', !avail);
            inp.disabled = !avail;
            if (!avail && inp.checked) { inp.checked = false; }
            inp.disabled = false; // unlock all (we show disabled visually via .off)
        });

        // Re-lock truly unavailable
        document.querySelectorAll('.sz-opt').forEach(opt => {
            const v   = variants.find(v => v.color === color && v.size === opt.dataset.size);
            const avail = v && parseInt(v.stock) > 0;
            opt.classList.toggle('off', !avail);
            const inp = opt.querySelector('input');
            inp.disabled = !avail;
            if (!avail && inp.checked) inp.checked = false;
        });

        document.getElementById('variantId').value = '';
        hideMsg();
        resetStockBadge();
        resetQty();
        onSizeChange(); // re-evaluate if size is already selected
    }

    /* ─── SIZE CHANGE ─── */
    function onSizeChange() {
        const color = document.querySelector('input[name="color"]:checked')?.value;
        const size  = document.querySelector('input[name="size"]:checked')?.value;
        if (!size) { resetStockBadge(); return; }

        const v = variants.find(v => (!hasColor || v.color === color) && v.size === size);
        if (!v) { resetStockBadge(); return; }

        document.getElementById('variantId').value = v.id;
        const stock = parseInt(v.stock);
        maxStock = stock;
        setStockBadge(stock);
        resetQty();
        hideMsg();
    }

    /* ─── STOCK BADGE ─── */
    function setStockBadge(stock) {
        const badge = document.getElementById('stockBadge');
        const txt   = document.getElementById('stockText');
        badge.className = 'stock-badge';
        if (stock <= 0) {
            badge.classList.add('stock-out');
            txt.textContent = 'Habis Terjual';
        } else if (stock <= 3) {
            badge.classList.add('stock-low');
            txt.textContent = `Sisa ${stock} pcs`;
        } else {
            badge.classList.add('stock-ok');
            txt.textContent = `${stock} Tersedia`;
        }
        // lock/unlock qty buttons
        document.getElementById('qtyMinus').disabled = stock <= 0;
        document.getElementById('qtyPlus').disabled  = stock <= 0;
        document.getElementById('btnCart').disabled  = stock <= 0;
        document.getElementById('btnBuy').disabled   = stock <= 0;
    }

    function resetStockBadge() {
        maxStock = 0;
        document.getElementById('stockBadge').className = 'stock-badge';
        document.getElementById('stockText').textContent = hasColor ? 'Pilih warna & ukuran' : 'Pilih ukuran';
        document.getElementById('qtyPlus').disabled  = false;
        document.getElementById('qtyMinus').disabled = false;
        document.getElementById('btnCart').disabled  = false;
        document.getElementById('btnBuy').disabled   = false;
    }

    /* ─── QUANTITY ─── */
    function adjustQty(delta) {
        const inp = document.getElementById('qtyInput');
        let val   = parseInt(inp.value) + delta;
        if (val < 1) val = 1;
        if (maxStock > 0 && val > maxStock) {
            val = maxStock;
            showMsg(`Stok hanya tersedia ${maxStock} pcs.`);
            setTimeout(hideMsg, 2500);
        }
        inp.value = val;
        document.getElementById('qtyMinus').disabled = val <= 1;
        document.getElementById('qtyPlus').disabled  = maxStock > 0 && val >= maxStock;
    }

    function resetQty() {
        const inp = document.getElementById('qtyInput');
        inp.value = 1;
        document.getElementById('qtyMinus').disabled = true; // always disable minus at 1
        document.getElementById('qtyPlus').disabled  = false;
    }

    /* ─── SUBMIT ─── */
  function submit(type) {
    const color = document.querySelector('input[name="color"]:checked');
    const size  = document.querySelector('input[name="size"]:checked');

    hideMsg();

    // Aman: cek dulu element-nya ada
    const colorHint = document.getElementById('colorHint');
    const sizeHint  = document.getElementById('sizeHint');
    if (colorHint) colorHint.style.display = 'none';
    if (sizeHint)  sizeHint.style.display  = 'none';

    if (hasColor && !color) {
        if (colorHint) colorHint.style.display = 'inline';
        showMsg('Silakan pilih warna terlebih dahulu.');
        return;
    }
    if (!size) {
        if (sizeHint) sizeHint.style.display = 'inline';
        showMsg('Silakan pilih ukuran Anda.');
        return;
    }

    const v = variants.find(v => (!hasColor || v.color === color.value) && v.size === size.value);
    if (!v || parseInt(v.stock) <= 0) {
        showMsg('Ukuran ini sudah habis terjual.');
        return;
    }

    const form = document.getElementById('cartForm');

    // Hapus buy_now lama dulu sebelum append (hindari duplikat)
    const existing = form.querySelector('input[name="buy_now"]');
    if (existing) existing.remove();

    if (type === 'buy') {
        const inp = document.createElement('input');
        inp.type = 'hidden'; inp.name = 'buy_now'; inp.value = '1';
        form.appendChild(inp);
    }

    form.submit();
}
    /* ─── MSG ─── */
    function showMsg(msg) {
        const el = document.getElementById('vMsg');
        el.textContent = msg;
        el.classList.remove('hidden');
    }
    function hideMsg() {
        document.getElementById('vMsg').classList.add('hidden');
    }

    /* ─── MODAL ─── */
    function openModal(id) {
        const m = document.getElementById(id);
        if (!m) return;
        m.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    function closeModal(id) {
        const m = document.getElementById(id);
        if (!m) return;
        m.classList.add('hidden');
        document.body.style.overflow = '';
    }
    document.addEventListener('keydown', e => {
        if (e.key !== 'Escape') return;
        document.querySelectorAll('.modal:not(.hidden)').forEach(m => {
            m.classList.add('hidden');
        });
        document.body.style.overflow = '';
    });

    /* ─── COUNTDOWN ─── */
    document.addEventListener('DOMContentLoaded', () => {
        const el = document.getElementById('cdWrap');
        if (!el) return;
        const target = new Date(el.dataset.expire).getTime();
        function tick() {
            const dist = target - Date.now();
            if (dist <= 0) return;
            el.classList.remove('hidden');
            let r = dist;
            const parts = { cdD:86400000, cdH:3600000, cdM:60000, cdS:1000 };
            for (const [id, ms] of Object.entries(parts)) {
                document.getElementById(id).textContent = String(Math.floor(r / ms)).padStart(2,'0');
                r %= ms;
            }
        }
        tick(); setInterval(tick, 1000);

        // Init: disable minus at qty=1
        document.getElementById('qtyMinus').disabled = true;

        // If no color required, unlock size options immediately
        if (!hasColor) {
            document.querySelectorAll('.sz-opt input').forEach(i => i.disabled = false);
        }
    });
    </script>
</body>
</html>