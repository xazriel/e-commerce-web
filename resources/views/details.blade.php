<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <meta name="theme-color" content="#2F3526">
    <title>{{ $product->name }} — Farhana</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('farhana.svg') }}">
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
        .badge-cat { background:var(--p10); color:var(--primary); border:1px solid var(--p20); border-radius:0 }

        /* Title & Price */
        .product-name {
            font-size:clamp(1.55rem,3vw,2.3rem); font-weight:300;
            letter-spacing:.09em; color:var(--primary); line-height:1.25;
            margin-bottom:.75rem;
        }
        .product-price {
            font-size:1rem; font-weight:400; letter-spacing:.1em;
            color:var(--black); margin-bottom:1.5rem;
            transition: all .2s ease;
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
        .v-opt.off span {
            opacity:.32; cursor:not-allowed; text-decoration:line-through;
            background:var(--gray); pointer-events:none;
        }
        .sz-opt span { min-width:46px; height:46px; padding:0 8px; font-weight:500 }

        .form-sec.needs-attention {
            padding: .75rem;
            border: 1.5px solid var(--red);
            background: rgba(192,57,43,.04);
            border-radius: 2px;
            transition: border-color .3s ease, background .3s ease;
        }
        .form-sec.needs-attention .sec-label { color: var(--red); }

        @keyframes hintShake {
            0%,100% { transform:translateX(0) }
            15%      { transform:translateX(-5px) }
            35%      { transform:translateX(5px) }
            55%      { transform:translateX(-4px) }
            75%      { transform:translateX(4px) }
            90%      { transform:translateX(-2px) }
        }
        .hint-shake { animation:hintShake .45s ease }

        .inline-hint {
            font-size:9px; letter-spacing:.15em; text-transform:uppercase;
            color:var(--red); display:none; align-items:center; gap:4px;
            opacity: 0; transform: translateX(6px);
            transition: opacity .2s ease, transform .2s ease;
        }
        .inline-hint.show { display: inline-flex; opacity: 1; transform: translateX(0); }
        .inline-hint svg { flex-shrink:0 }

        @keyframes toastIn {
            from { opacity:0; transform:translateY(8px) }
            to   { opacity:1; transform:translateY(0) }
        }
        @keyframes toastOut {
            from { opacity:1; transform:translateY(0) }
            to   { opacity:0; transform:translateY(6px) }
        }
        .v-msg {
            display: flex; align-items: center; gap: .6rem;
            font-size: 10px; font-weight: 700; letter-spacing: .14em;
            text-transform: uppercase; padding: .8rem 1rem;
            border-left: 3px solid var(--red);
            background: rgba(192,57,43,.08); color: var(--red);
            margin-top: .75rem; animation: toastIn .2s ease forwards;
        }
        .v-msg.hiding { animation: toastOut .25s ease forwards; }
        .v-msg.hidden { display: none !important }
        .v-msg-icon { flex-shrink: 0; width:14px; height:14px }

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

        @keyframes btnShake {
            0%,100% { transform:translateX(0) }
            20%      { transform:translateX(-5px) }
            40%      { transform:translateX(5px) }
            60%      { transform:translateX(-4px) }
            80%      { transform:translateX(4px) }
        }
        .btn-shake { animation: btnShake .4s ease }

        /* Description */
        .desc-block { border-top:1px solid var(--gray); padding-top:1.75rem; margin-top:1.75rem }
        .desc-title { font-size:10px; font-weight:700; letter-spacing:.28em; text-transform:uppercase; color:var(--primary); margin-bottom:1rem }
        .desc-body { font-size:11px; letter-spacing:.05em; line-height:2; color:var(--o60); text-transform:uppercase }

        /* ── RELATED ── */
        .related-sec { border-top:1px solid var(--gray); padding-top:3.5rem; margin-top:4.5rem }
        .related-h { font-size:clamp(1rem,2.2vw,1.5rem); font-weight:300; letter-spacing:.4em; text-transform:uppercase; text-align:center; color:var(--primary); margin-bottom:2.5rem }
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
        footer { padding: 4rem 0 0; background: #2F3526; color: #fff; }
        .footer-inner { max-width: 1400px; margin: 0 auto; padding: 0 1.5rem 3.5rem; }
        .footer-grid { display: grid; grid-template-columns: 1fr; gap: 4rem; text-align: left; }
        @media(min-width:600px) { .footer-grid { grid-template-columns: 1fr 1fr } }
        @media(min-width:1024px) { .footer-grid { grid-template-columns: 1.5fr 1fr 1fr } }
        .f-title { font-size: 10px; font-weight: 700; letter-spacing: .3em; text-transform: uppercase; color: #f7f7f7; margin-bottom: 1.5rem; }
        .f-text { font-size: 11px; color: rgba(255,255,255,.8); line-height: 1.9; letter-spacing: .07em; text-transform: uppercase; }
        .f-links { display:flex; flex-direction:column; gap:.75rem }
        .f-link {
            font-size: 11px; letter-spacing: .11em; text-transform: uppercase;
            color: rgba(255,255,255,.8); transition: color var(--t), transform var(--t);
            background: none; border: none; cursor: pointer; padding: 0; text-align: left; font-family: inherit;
        }
        .f-link:hover { color: #fff; transform: translateX(4px) }
        .f-bottom { border-top: 1px solid rgba(255,255,255,.1); padding: .9rem 1.5rem; text-align: center; margin-top: 5rem; }
        .f-copy { font-size:9px; letter-spacing:.4em; text-transform:uppercase; color:rgba(255,255,255,.4) }

        /* ── MODAL ── */
        .modal {
            position:fixed; inset:0; z-index:300; background:rgba(0,0,0,.55);
            backdrop-filter:blur(5px); display:flex; align-items:center;
            justify-content:center; padding:1rem;
        }
        .modal-box { background:var(--white); max-width:460px; width:100%; max-height:92vh; overflow-y:auto; scrollbar-width:thin; }
        .modal-head {
            position:sticky; top:0; background:var(--white);
            border-bottom:1px solid var(--gray); padding:.9rem 1.25rem;
            display:flex; align-items:center; justify-content:space-between; z-index:1;
        }
        .modal-title { font-size:10px; font-weight:700; letter-spacing:.28em; text-transform:uppercase; color:var(--primary) }
        .modal-close { width:30px; height:30px; display:flex; align-items:center; justify-content:center; color:var(--primary); font-size:22px; font-weight:300; line-height:1; transition:opacity var(--t); }
        .modal-close:hover { opacity:.5 }
        .modal-body { padding:1.25rem }
        .modal-body img { width:100%; height:auto }

        .footer-modal-overlay {
            position:fixed; inset:0; z-index:999; display:flex; align-items:center; justify-content:center;
            padding:1.5rem; background:rgba(0,0,0,.4); backdrop-filter:blur(4px);
        }
        .footer-modal-box {
            background:#fff; width:100%; max-width:900px; max-height:90vh; overflow-y:auto;
            position:relative; border-radius:2.5rem; box-shadow:0 25px 60px rgba(0,0,0,.25); scrollbar-width:none;
        }
        .footer-modal-box::-webkit-scrollbar { display:none }
        .footer-modal-close {
            position:absolute; top:2rem; right:2rem; z-index:10;
            padding:.5rem; border-radius:50%; background:rgba(249,250,251,1);
            transition:background var(--t); color:#000; font-size:0;
        }
        .footer-modal-close:hover { background:#e5e7eb }

        .hidden { display:none !important }
    </style>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body x-data="{ openModal: null }">

    <template x-if="openModal">
        <div class="fixed inset-0 z-[400] flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm" @click.self="openModal = null">
            <div class="bg-white w-full max-w-[1000px] max-h-[85vh] overflow-y-auto rounded-[2rem] shadow-2xl relative text-gray-800" style="scrollbar-width: none;">
                <button @click="openModal = null" class="absolute top-8 right-8 text-gray-400 hover:text-black z-50 bg-white/80 backdrop-blur rounded-full p-2 transition-all">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
                <div class="prose prose-sm max-w-none">
                    <template x-if="openModal === 'contact'"><div>@include('includes.contact')</div></template>
                    <template x-if="openModal === 'shipping'"><div>@include('includes.shipping')</div></template>
                    <template x-if="openModal === 'howtobuy'"><div>@include('includes.how-to-buy')</div></template>
                    <template x-if="openModal === 'faqs'"><div>@include('includes.faqs')</div></template>
                </div>
            </div>
        </div>
    </template>

    {{-- WhatsApp FAB --}}
    <a href="https://wa.me/628123456789?text=Hello%20Farhana,%20I'm%20interested%20in%20product%20{{ urlencode($product->name) }}"
       class="wa-fab" target="_blank" rel="noopener" aria-label="Chat WhatsApp">
        <i class="fab fa-whatsapp"></i>
    </a>

    {{-- Navbar --}}
    <header class="navbar" role="banner">
        <div class="navbar-inner">
            <a href="{{ route('home') }}" class="back-btn" aria-label="Back to collection">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="15 18 9 12 15 6"/>
                </svg>
                <span>Collection</span>
            </a>
            <div class="shrink-0 flex items-center">
                <a href="{{ route('home') }}">
                    <img src="{{ Storage::url('LOGO-FARHANA-NEW-TRANSPARENT.png') }}"
                         alt="Farhana"
                         class="h-20 w-auto object-contain">
                </a>
            </div>
        </div>
    </header>

    @if(session('success'))
        <div class="success-toast" role="alert">{{ session('success') }}</div>
    @endif

    <main class="page" role="main">
        <div class="product-grid">

            {{-- ── GALLERY ── --}}
            <div aria-label="Product gallery">
                <div id="swipeArea" class="gallery-hero" role="img" aria-label="{{ $product->name }}">
                    <button type="button" class="g-arrow prev" onclick="navigate(-1)" aria-label="Previous photo">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
                    </button>
                    <img id="mainImage"
                         src="{{ asset('storage/' . ($product->images->where('is_primary', true)->first()->image_path ?? $product->images->first()->image_path)) }}"
                         alt="{{ $product->name }}" loading="eager">
                    <button type="button" class="g-arrow next" onclick="navigate(1)" aria-label="Next photo">
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
                                aria-label="Photo {{ $i + 1 }}">
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
                        @if($product->defect_type)
                            <span class="badge badge-defect" style="background: #ef4444; color: white;">Defect {{ $product->defect_type }}</span>
                        @endif
                        @if($product->custom_tag)
                            <span class="badge badge-tag">{{ $product->custom_tag }}</span>
                        @endif
                        <span class="badge badge-cat">{{ $product->category->name ?? 'Collection' }}</span>
                    </div>

                    <h1 class="product-name">{{ $product->name }}</h1>

                    {{-- ── PRICE BLOCK — sama persis dengan welcome.blade ── --}}
                    <div class="flex items-center gap-3 mb-6 flex-wrap" style="display: flex; align-items: center; gap: 12px; margin-bottom: 1.5rem;">
                        @if($product->original_price && $product->original_price > $product->price)
                            <span id="originalPrice" style="text-decoration: line-through; color: #9ca3af; font-size: 0.875rem;">
                                IDR {{ number_format($product->original_price, 0, ',', '.') }}
                            </span>
                        @endif
                        <p class="product-price" id="displayPrice" style="margin-bottom: 0;">IDR {{ number_format($product->price, 0, ',', '.') }}</p>
                    </div>

                    <hr class="div">

                    {{-- Countdown --}}
                    @if($product->is_preorder && $product->release_date)
                        <div id="cdWrap" data-expire="{{ $product->release_date }}" class="countdown-wrap hidden" role="timer">
                            <p class="cd-label">Pre-Order Ends In</p>
                            <div class="cd-row">
                                <div class="cd-unit"><span id="cdD" class="cd-num">00</span><span class="cd-lbl">Days</span></div>
                                <div class="cd-unit"><span id="cdH" class="cd-num">00</span><span class="cd-lbl">Hours</span></div>
                                <div class="cd-unit"><span id="cdM" class="cd-num">00</span><span class="cd-lbl">Minutes</span></div>
                                <div class="cd-unit"><span id="cdS" class="cd-num">00</span><span class="cd-lbl">Seconds</span></div>
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

                        {{-- COLOR SECTION --}}
                        @if($hasColor)
                        <div class="form-sec" id="colorSection">
                            <div class="form-sec-head">
                                <span class="sec-label" id="colorLabel">Select Color</span>
                                <span id="colorHint" class="inline-hint" aria-live="polite">
                                    <svg class="v-msg-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                                    </svg>
                                    <span class="hint-text">Select color first</span>
                                </span>
                            </div>
                            <div class="v-opts" role="group" aria-label="Color options" id="colorOpts">
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

                        {{-- SIZE SECTION --}}
                        <div class="form-sec" id="sizeSection">
                            <div class="form-sec-head">
                                <span class="sec-label" id="sizeLabel">Select Size</span>
                                <div style="display:flex;align-items:center;gap:.75rem">
                                    <span id="sizeHint" class="inline-hint" aria-live="polite">
                                        <svg class="v-msg-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                                        </svg>
                                        <span class="hint-text">Select size first</span>
                                    </span>
                                    @if($product->sizeGuide)
                                        <button type="button" class="guide-btn" onclick="openModal('guideModal')">Size Guide</button>
                                    @endif
                                </div>
                            </div>
                            <div class="v-opts" id="sizeContainer" role="group" aria-label="Size options">
                                @foreach($uniqueSizes as $size)
                                    <label class="v-opt sz-opt" data-size="{{ $size }}" onclick="onSizeLabelClick(event, this)">
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
                                <span class="sec-label">Quantity</span>
                                <span id="stockBadge" class="stock-badge" aria-live="polite">
                                    <span class="stock-dot"></span>
                                    <span id="stockText">{{ $hasColor ? 'Select color & size' : 'Select size' }}</span>
                                </span>
                            </div>
                            <div class="qty-wrap" role="group" aria-label="Quantity">
                                <button type="button" class="qty-btn" id="qtyMinus" onclick="adjustQty(-1)" aria-label="Decrease">−</button>
                                <input type="number" id="qtyInput" name="quantity" value="1" min="1" class="qty-input" readonly aria-label="Quantity">
                                <button type="button" class="qty-btn" id="qtyPlus" onclick="adjustQty(1)" aria-label="Increase">+</button>
                            </div>
                        </div>

                        {{-- Validation message --}}
                        <div id="vMsg" class="v-msg hidden" role="alert" aria-live="assertive">
                            <svg class="v-msg-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"/>
                                <line x1="12" y1="8" x2="12" y2="12"/>
                                <line x1="12" y1="16" x2="12.01" y2="16"/>
                            </svg>
                            <span id="vMsgText"></span>
                        </div>

                        <div class="btn-row" id="btnRow">
                            <button type="button" class="btn btn-outline" id="btnCart" onclick="handleOrder('add')">
                                {{ $product->is_preorder ? 'Pre-Order Now' : 'Add to Cart' }}
                            </button>
                            <button type="button" class="btn btn-primary" id="btnBuy" onclick="handleOrder('buy')">
                                Buy Now
                            </button>
                        </div>
                    </form>

                    {{-- Description --}}
                    <div class="desc-block">
                        <p class="desc-title">Description</p>
                        <div class="desc-body">{!! nl2br(e($product->description)) !!}</div>
                    </div>

                </div>
            </div>
        </div>

        {{-- RELATED --}}
        @if(isset($relatedProducts) && $relatedProducts->count() > 0)
            <section class="related-sec" aria-label="Related products">
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

    {{-- FOOTER --}}
    <footer id="about" role="contentinfo" x-data="{ footerModal: null }">

        <div x-show="footerModal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="footer-modal-overlay"
             style="display:none;">
            <div @click.away="footerModal = null" class="footer-modal-box">
                <button @click="footerModal = null" class="footer-modal-close" aria-label="Close">
                    <svg style="width:20px;height:20px" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
                <div class="prose prose-sm max-w-none">
                    <template x-if="footerModal === 'contact'"><div>@include('includes.contact')</div></template>
                    <template x-if="footerModal === 'shipping'"><div>@include('includes.shipping')</div></template>
                    <template x-if="footerModal === 'howtobuy'"><div>@include('includes.how-to-buy')</div></template>
                    <template x-if="footerModal === 'faqs'"><div>@include('includes.faqs')</div></template>
                </div>
            </div>
        </div>

        <div class="footer-inner">
            <div class="footer-grid">
                <div>
                    <h4 class="text-[10px] font-bold tracking-[0.3em] uppercase mb-6">About Farhana</h4>
                    <p class="text-[11px] text-white/80 leading-loose tracking-widest uppercase">
                        Farhana is present through a simplicity that feels calm, elegant, and meaningful.
                        We believe that modesty is not just a way of dressing, but also a way of carrying oneself with faith, serenity, and beauty that is not excessive.
                        Each collection is designed with attention to detail, comfort, and elegant silhouettes to accompany Muslim women in every step.
                        <br><br>
                        Luxury in Modesty.<br>
                        Elegance with Iman.
                    </p>
                </div>
                <div>
                    <h4 class="f-title">Customer Care</h4>
                    <div class="f-links">
                        <button @click="footerModal = 'contact'"  class="f-link">Contact Us</button>
                        <button @click="footerModal = 'shipping'" class="f-link">Shipping &amp; Returns</button>
                        <button @click="footerModal = 'howtobuy'" class="f-link">How To Buy</button>
                        <button @click="footerModal = 'faqs'"     class="f-link">FAQs</button>
                    </div>
                </div>
                <div>
                    <h4 class="text-[10px] font-bold tracking-[0.3em] uppercase mb-6">Follow Us</h4>
                    <div class="flex flex-col items-start gap-4">
                        <div class="flex items-center gap-3">
                            <a href="https://www.instagram.com/farhanas.id"
                               class="w-9 h-9 rounded-full bg-white flex items-center justify-center text-[#2F3526] hover:bg-[#E9E9E9] transition flex-shrink-0"
                               target="_blank" rel="noopener" aria-label="Instagram Farhana">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                </svg>
                            </a>
                            <span class="text-[10px] tracking-widest uppercase text-white/80">@farhanas.id</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <a href="https://www.tiktok.com/@farhanas.id"
                               class="w-9 h-9 rounded-full bg-white flex items-center justify-center text-[#2F3526] hover:bg-[#E9E9E9] transition flex-shrink-0"
                               target="_blank" rel="noopener" aria-label="TikTok Farhana">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.17-2.86-.6-4.12-1.31a8.52 8.52 0 0 1-1.87-1.43v6.24c-.03 2.34-.79 4.7-2.6 6.13-1.81 1.43-4.39 1.83-6.57 1.15-2.18-.68-4.05-2.48-4.66-4.67-.61-2.19-.19-4.78 1.25-6.58 1.44-1.8 3.84-2.74 6.11-2.43v4.18c-1.13-.19-2.36.03-3.23.82-.87.79-1.2 2.06-.85 3.19.35 1.13 1.43 1.99 2.61 2.08 1.18.09 2.4-.42 3.01-1.43.25-.42.36-.91.36-1.4V0z"/>
                                </svg>
                            </a>
                            <span class="text-[10px] tracking-widest uppercase text-white/80">@farhanas.id</span>
                        </div>
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
                    <p class="modal-title" id="guideTitle">Size Guide</p>
                    <button type="button" class="modal-close" onclick="closeModal('guideModal')" aria-label="Close">&times;</button>
                </div>
                <div class="modal-body">
                    <img src="{{ asset('storage/' . $product->sizeGuide->image) }}"
                         alt="Size Guide {{ $product->name }}" loading="lazy">
                </div>
            </div>
        </div>
    @endif

    <script>
    /* ══════════════════════════════════════════════
       DATA
    ══════════════════════════════════════════════ */
    const variants   = @json($product->variants);
    const imgUrls    = @json($product->images->values()->map(fn($i) => asset('storage/'.$i->image_path)));
    const hasColor   = {{ $hasColor ? 'true' : 'false' }};
    const isLoggedIn = {{ auth()->check() ? 'true' : 'false' }};
    const basePrice  = {{ $product->price }};
    let   curIdx     = 0;
    let   maxStock   = 0;

    /* ══════════════════════════════════════════════
       GALLERY
    ══════════════════════════════════════════════ */
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

    /* ══════════════════════════════════════════════
       VALIDATION UX HELPERS
    ══════════════════════════════════════════════ */
    function _highlightSection(sectionId) {
        const sec = document.getElementById(sectionId);
        if (!sec) return;
        sec.classList.add('needs-attention');
        sec.classList.remove('hint-shake');
        void sec.offsetWidth;
        sec.classList.add('hint-shake');
        sec.addEventListener('animationend', () => sec.classList.remove('hint-shake'), { once:true });
        clearTimeout(sec._highlightTid);
        sec._highlightTid = setTimeout(() => sec.classList.remove('needs-attention'), 3500);
    }

    function _clearSection(sectionId) {
        const sec = document.getElementById(sectionId);
        if (sec) {
            sec.classList.remove('needs-attention', 'hint-shake');
            clearTimeout(sec._highlightTid);
        }
    }

    function showColorHint(msg) {
        const el = document.getElementById('colorHint');
        if (!el) return;
        if (msg) { const t = el.querySelector('.hint-text'); if (t) t.textContent = msg; }
        el.classList.add('show');
        clearTimeout(el._tid);
        el._tid = setTimeout(() => el.classList.remove('show'), 3500);
    }
    function hideColorHint() {
        const el = document.getElementById('colorHint');
        if (el) { el.classList.remove('show'); clearTimeout(el._tid); }
    }

    function showSizeHint(msg) {
        const el = document.getElementById('sizeHint');
        if (!el) return;
        if (msg) { const t = el.querySelector('.hint-text'); if (t) t.textContent = msg; }
        el.classList.add('show');
        clearTimeout(el._tid);
        el._tid = setTimeout(() => el.classList.remove('show'), 3500);
    }
    function hideSizeHint() {
        const el = document.getElementById('sizeHint');
        if (el) { el.classList.remove('show'); clearTimeout(el._tid); }
    }

    let _bannerTimer = null;
    function showBanner(msg) {
        const el  = document.getElementById('vMsg');
        const txt = document.getElementById('vMsgText');
        if (!el || !txt) return;
        el.classList.remove('hidden', 'hiding');
        txt.textContent = msg;
        clearTimeout(_bannerTimer);
        _bannerTimer = setTimeout(() => {
            el.classList.add('hiding');
            setTimeout(() => el.classList.add('hidden'), 260);
        }, 4000);
    }
    function hideBanner() {
        clearTimeout(_bannerTimer);
        const el = document.getElementById('vMsg');
        if (el) el.classList.add('hidden');
    }

    function shakeBtn(id) {
        const btn = document.getElementById(id);
        if (!btn) return;
        btn.classList.remove('btn-shake');
        void btn.offsetWidth;
        btn.classList.add('btn-shake');
        btn.addEventListener('animationend', () => btn.classList.remove('btn-shake'), { once:true });
    }

    function scrollToSection(sectionId) {
        const el = document.getElementById(sectionId);
        if (!el) return;
        const top = el.getBoundingClientRect().top + window.scrollY - 100;
        window.scrollTo({ top, behavior:'smooth' });
    }

    /* ══════════════════════════════════════════════
       UPDATE HARGA DINAMIS
    ══════════════════════════════════════════════ */
    function updateDisplayPrice(additionalPrice) {
        const finalPrice = basePrice + (parseFloat(additionalPrice) || 0);
        const priceEl    = document.getElementById('displayPrice');
        if (priceEl) {
            priceEl.textContent = 'IDR ' + finalPrice.toLocaleString('id-ID');
        }
    }

    function resetDisplayPrice() {
        const priceEl = document.getElementById('displayPrice');
        if (priceEl) {
            priceEl.textContent = 'IDR ' + basePrice.toLocaleString('id-ID');
        }
    }

    /* ══════════════════════════════════════════════
       SIZE LABEL CLICK GUARD
    ══════════════════════════════════════════════ */
    function onSizeLabelClick(event, labelEl) {
        if (labelEl.classList.contains('off')) {
            event.preventDefault();
            return;
        }
        const inp = labelEl.querySelector('input');
        if (inp && inp.disabled) {
            event.preventDefault();
            _highlightSection('colorSection');
            showColorHint('Select color first');
            scrollToSection('colorSection');
            return;
        }
    }

    /* ══════════════════════════════════════════════
       COLOR CHANGE
    ══════════════════════════════════════════════ */
    function onColorChange(color) {
        hideBanner();
        hideColorHint();
        hideSizeHint();
        _clearSection('colorSection');
        _clearSection('sizeSection');

        const thumb = document.querySelector(`.thumb[data-color="${color.toLowerCase().trim()}"]`);
        if (thumb) goToImage(parseInt(thumb.dataset.index));

        const isPreorder = {{ $product->is_preorder ? 'true' : 'false' }};

        document.querySelectorAll('.sz-opt').forEach(opt => {
            const v     = variants.find(v => v.color === color && v.size === opt.dataset.size);
            const inp   = opt.querySelector('input');
            const avail = isPreorder ? !!v : (v && parseInt(v.stock) > 0);

            opt.classList.toggle('off', !avail);
            inp.disabled = !avail;
            if (!avail && inp.checked) inp.checked = false;
        });

        document.getElementById('variantId').value = '';
        resetStockBadge();
        resetDisplayPrice();
        resetQty();
        onSizeChange();
    }

    /* ══════════════════════════════════════════════
       SIZE CHANGE
    ══════════════════════════════════════════════ */
    function onSizeChange() {
        const color = document.querySelector('input[name="color"]:checked')?.value;
        const size  = document.querySelector('input[name="size"]:checked')?.value;

        if (!size) { resetStockBadge(); return; }
        if (hasColor && !color) { resetStockBadge(); return; }

        const v = variants.find(v => (!hasColor || v.color === color) && v.size === size);
        if (!v) { resetStockBadge(); return; }

        hideBanner();
        hideColorHint();
        hideSizeHint();
        _clearSection('colorSection');
        _clearSection('sizeSection');

        document.getElementById('variantId').value = v.id;
        const stock = parseInt(v.stock);
        maxStock = stock;
        setStockBadge(stock);
        resetQty();

        updateDisplayPrice(v.additional_price ?? 0);
    }

    /* ══════════════════════════════════════════════
       STOCK BADGE
    ══════════════════════════════════════════════ */
    function setStockBadge(stock) {
        const badge = document.getElementById('stockBadge');
        const txt   = document.getElementById('stockText');
        badge.className = 'stock-badge';
        if (stock <= 0) {
            badge.classList.add('stock-out');
            txt.textContent = 'Out of Stock';
        } else if (stock <= 3) {
            badge.classList.add('stock-low');
            txt.textContent = `Only ${stock} left`;
        } else {
            badge.classList.add('stock-ok');
            txt.textContent = `${stock} Available`;
        }
        const outOfStock = stock <= 0;
        document.getElementById('qtyMinus').disabled = outOfStock;
        document.getElementById('qtyPlus').disabled  = outOfStock;
        document.getElementById('btnCart').disabled  = outOfStock;
        document.getElementById('btnBuy').disabled   = outOfStock;
    }

    function resetStockBadge() {
        maxStock = 0;
        const badge = document.getElementById('stockBadge');
        const txt   = document.getElementById('stockText');
        badge.className = 'stock-badge';
        txt.textContent = hasColor ? 'Select color & size' : 'Select size';
        document.getElementById('qtyPlus').disabled  = false;
        document.getElementById('qtyMinus').disabled = true;
        document.getElementById('btnCart').disabled  = false;
        document.getElementById('btnBuy').disabled   = false;
    }

    /* ══════════════════════════════════════════════
       QUANTITY
    ══════════════════════════════════════════════ */
    function adjustQty(delta) {
        const inp = document.getElementById('qtyInput');
        let val   = parseInt(inp.value) + delta;
        if (val < 1) val = 1;
        if (maxStock > 0 && val > maxStock) {
            val = maxStock;
            showBanner(`Only ${maxStock} items available.`);
        }
        inp.value = val;
        document.getElementById('qtyMinus').disabled = val <= 1;
        document.getElementById('qtyPlus').disabled  = maxStock > 0 && val >= maxStock;
    }

    function resetQty() {
        document.getElementById('qtyInput').value = 1;
        document.getElementById('qtyMinus').disabled = true;
        document.getElementById('qtyPlus').disabled  = false;
    }

    /* ══════════════════════════════════════════════
       HANDLE ORDER
    ══════════════════════════════════════════════ */
    function handleOrder(type) {
        hideBanner();
        hideColorHint();
        hideSizeHint();
        _clearSection('colorSection');
        _clearSection('sizeSection');

        const colorEl = document.querySelector('input[name="color"]:checked');
        const sizeEl  = document.querySelector('input[name="size"]:checked');

        if (hasColor && !colorEl && !sizeEl) {
            _highlightSection('colorSection');
            _highlightSection('sizeSection');
            showColorHint('Select color first');
            showSizeHint('Select size first');
            showBanner('Please select color & size first.');
            shakeBtn('btnCart');
            shakeBtn('btnBuy');
            scrollToSection('colorSection');
            return;
        }

        if (!hasColor && !sizeEl) {
            _highlightSection('sizeSection');
            showSizeHint('Select size first');
            showBanner('Please select size first.');
            shakeBtn('btnCart');
            shakeBtn('btnBuy');
            scrollToSection('sizeSection');
            return;
        }

        if (!sizeEl) {
            _highlightSection('sizeSection');
            showSizeHint('Select size first');
            showBanner('Please select size first.');
            shakeBtn('btnCart');
            shakeBtn('btnBuy');
            scrollToSection('sizeSection');
            return;
        }

        if (hasColor && !colorEl) {
            _highlightSection('colorSection');
            showColorHint('Select color first');
            showBanner('Please select color first.');
            shakeBtn('btnCart');
            shakeBtn('btnBuy');
            scrollToSection('colorSection');
            return;
        }

        const isPreorder = {{ $product->is_preorder ? 'true' : 'false' }};
        const v = variants.find(v =>
            (!hasColor || v.color === colorEl.value) && v.size === sizeEl.value
        );

        if (!v || (!isPreorder && parseInt(v.stock) <= 0)) {
            showBanner('This option is out of stock.');
            shakeBtn('btnCart');
            shakeBtn('btnBuy');
            return;
        }

        const form = document.getElementById('cartForm');
        const existing = form.querySelector('input[name="buy_now"]');
        if (existing) existing.remove();

        if (type === 'buy') {
            if (!isLoggedIn) {
                window.location.href = '{{ route('login') }}?redirect={{ urlencode(request()->fullUrl()) }}';
                return;
            }
            const inp = document.createElement('input');
            inp.type = 'hidden'; inp.name = 'buy_now'; inp.value = '1';
            form.appendChild(inp);
        }

        HTMLFormElement.prototype.submit.call(form);
    }

    /* ══════════════════════════════════════════════
       MODAL
    ══════════════════════════════════════════════ */
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

    document.getElementById('cartForm').addEventListener('keydown', e => {
        if (e.key === 'Enter') e.preventDefault();
    });

    /* ══════════════════════════════════════════════
       COUNTDOWN
    ══════════════════════════════════════════════ */
    document.addEventListener('DOMContentLoaded', () => {
        const el = document.getElementById('cdWrap');
        if (el) {
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
        }

        document.getElementById('qtyMinus').disabled = true;

        if (!hasColor) {
            document.querySelectorAll('.sz-opt input').forEach(i => i.disabled = false);
        }
    });
    </script>
</body>
</html>