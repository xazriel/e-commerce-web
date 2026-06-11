<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <meta name="theme-color" content="#2F3526">
    <title>Cart — Farhana</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('farhana.svg') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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
            --w12:      rgba(255,255,255,.12);
            --red:      #C0392B;
            --t:        .25s ease;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0 }
        html { scroll-behavior: smooth; -webkit-text-size-adjust: 100% }
        body {
            font-family: Helvetica, Arial, sans-serif;
            background: var(--white);
            color: var(--black);
            -webkit-font-smoothing: antialiased;
            overflow-x: hidden;
            font-size: 14px;
        }
        img { display: block; max-width: 100% }
        button { font-family: inherit; border: none; background: none; cursor: pointer }
        a { text-decoration: none; color: inherit }

        /* ── NAVBAR ── */
        .navbar {
            position: sticky; top: 0; z-index: 100;
            background: var(--white); border-bottom: 1px solid var(--gray);
        }
        .navbar-inner {
            max-width: 1400px; margin: 0 auto; padding: 0 1.25rem;
            height: 68px; display: flex; align-items: center; justify-content: space-between;
            position: relative;
        }
        .back-btn {
            display: flex; align-items: center; gap: .4rem;
            font-size: 10px; letter-spacing: .22em; text-transform: uppercase;
            color: var(--o60); transition: color var(--t);
        }
        .back-btn svg { transition: transform var(--t); flex-shrink: 0 }
        .back-btn:hover { color: var(--primary) }
        .back-btn:hover svg { transform: translateX(-3px) }

        /* ── PAGE ── */
        .page { max-width: 1100px; margin: 0 auto; padding: 3rem 1.25rem 6rem }

        /* ── PAGE HEADER ── */
        .page-head {
            display: flex; align-items: center; gap: 1.5rem; margin-bottom: 3rem;
            padding-bottom: 1.5rem;
        }
        .page-title {
            font-size: clamp(0.9rem, 2vw, 1.2rem); font-weight: 300;
            letter-spacing: .4em; text-transform: uppercase; color: var(--primary);
            white-space: nowrap;
        }
        .page-head-line { flex: 1; height: 1px; background: var(--gray) }
        .item-count {
            font-size: 9px; letter-spacing: .25em; text-transform: uppercase;
            color: var(--o60); white-space: nowrap;
        }

        /* ── CART TABLE ── */
        .cart-table { width: 100% }
        .cart-head {
            display: grid;
            grid-template-columns: 100px 1fr 140px 120px 80px;
            gap: 1rem;
            padding: 0 0 .75rem;
            border-bottom: 1px solid var(--gray);
        }
        @media (max-width: 768px) { .cart-head { display: none } }
        .cart-head span {
            font-size: 9px; font-weight: 700; letter-spacing: .22em;
            text-transform: uppercase; color: var(--o60);
        }
        .cart-head .col-qty  { text-align: center }
        .cart-head .col-sub  { text-align: right }
        .cart-head .col-act  { text-align: right }

        /* ── CART ROW — DESKTOP ── */
        .cart-row {
            display: grid;
            grid-template-columns: 100px 1fr 140px 120px 80px;
            gap: 1rem;
            padding: 1.5rem 0;
            border-bottom: 1px solid var(--gray);
            align-items: center;
            transition: opacity var(--t);
        }
        .cart-row.updating { opacity: .4; pointer-events: none }

        /* ── CART ROW — MOBILE ── */
        @media (max-width: 768px) {
            .cart-row {
                display: grid;
                grid-template-columns: 90px 1fr auto;
                grid-template-rows: auto auto;
                column-gap: .85rem;
                row-gap: 0;
                padding: 1.25rem 0;
                align-items: start;
            }

            /* Gambar: span 2 baris */
            .col-img {
                grid-column: 1;
                grid-row: 1 / 3;
                align-self: start;
            }

            /* Info: nama, meta, harga satuan — baris 1, kolom 2 */
            .col-info {
                grid-column: 2;
                grid-row: 1;
                padding-top: 2px;
            }

            /* Hapus — baris 1, kolom 3 (pojok kanan atas) */
            .col-act {
                grid-column: 3;
                grid-row: 1;
                display: flex;
                justify-content: flex-end;
                align-items: flex-start;
                padding-top: 2px;
            }

            /* Baris 2: qty kiri, subtotal kanan — sebaris */
            .col-qty {
                grid-column: 2;
                grid-row: 2;
                justify-content: flex-start;
                margin-top: .7rem;
                align-self: center;
            }

            .col-sub {
                grid-column: 3;
                grid-row: 2;
                text-align: right;
                margin-top: .7rem;
                font-size: 11px;
                font-weight: 500;
                letter-spacing: .04em;
                color: var(--primary);
                align-self: center;
            }
        }

        /* Product image */
        .prod-img {
            width: 100px; height: 130px; overflow: hidden;
            background: var(--gray); flex-shrink: 0;
        }
        @media (max-width: 768px) {
            .prod-img { width: 90px; height: 118px }
        }
        .prod-img img { width: 100%; height: 100%; object-fit: cover }

        /* Info */
        .prod-name {
            font-size: 11px; font-weight: 700; letter-spacing: .15em;
            text-transform: uppercase; color: var(--primary); margin-bottom: .3rem;
        }
        .prod-meta {
            font-size: 10px; letter-spacing: .12em; text-transform: uppercase;
            color: var(--o60); margin-bottom: .3rem;
        }
        .prod-price {
            font-size: 10px; letter-spacing: .08em; color: var(--olive);
        }
        .preorder-tag {
            display: inline-block; margin-top: .4rem;
            font-size: 8px; font-weight: 700; letter-spacing: .18em;
            text-transform: uppercase; padding: 2px 8px;
            background: var(--primary); color: var(--white);
        }

        /* Qty control */
        .col-qty { display: flex; justify-content: center }
        @media (max-width: 768px) { .col-qty { justify-content: flex-start } }

        .qty-wrap {
            display: inline-flex; align-items: center;
            border: 1px solid var(--gray); height: 34px;
        }
        @media (min-width: 769px) { .qty-wrap { height: 38px } }
        .qty-btn {
            width: 34px; height: 100%; display: flex; align-items: center; justify-content: center;
            font-size: 16px; font-weight: 300; color: var(--olive);
            transition: color var(--t), background var(--t); flex-shrink: 0;
            user-select: none;
        }
        @media (min-width: 769px) { .qty-btn { width: 38px; font-size: 18px } }
        .qty-btn:hover:not(:disabled) { color: var(--primary); background: var(--p10) }
        .qty-btn:disabled { opacity: .3; cursor: not-allowed }
        .qty-val {
            width: 38px; text-align: center;
            font-size: 12px; color: var(--black);
            border-left: 1px solid var(--gray);
            border-right: 1px solid var(--gray);
            line-height: 34px; user-select: none;
        }
        @media (min-width: 769px) { .qty-val { width: 42px; line-height: 38px } }

        /* Subtotal — desktop */
        .col-sub {
            font-size: 12px; font-weight: 400; letter-spacing: .06em;
            color: var(--black); text-align: right;
        }

        /* Action */
        .col-act { display: flex; justify-content: flex-end; align-items: center }

        /* Remove button */
        .remove-btn {
            font-size: 9px; letter-spacing: .2em; text-transform: uppercase;
            color: var(--gray); border-bottom: 1px solid transparent;
            transition: color var(--t), border-color var(--t);
            padding-bottom: 1px; line-height: 1;
        }
        .remove-btn:hover { color: var(--red); border-color: var(--red) }

        /* ── SUMMARY ── */
        .summary-wrap {
            display: flex; justify-content: flex-end; margin-top: 2.5rem;
        }
        .summary-box { width: 100%; max-width: 360px }

        .summary-row {
            display: flex; justify-content: space-between; align-items: baseline;
            padding: .65rem 0; border-bottom: 1px solid var(--gray);
        }
        .summary-label {
            font-size: 9px; letter-spacing: .28em; text-transform: uppercase; color: var(--o60);
        }
        .summary-val {
            font-size: 11px; letter-spacing: .06em; color: var(--black);
        }
        .summary-total-row {
            display: flex; justify-content: space-between; align-items: baseline;
            padding: 1rem 0 1.5rem;
        }
        .summary-total-label {
            font-size: 10px; font-weight: 700; letter-spacing: .28em;
            text-transform: uppercase; color: var(--primary);
        }
        .summary-total-val {
            font-size: 1.2rem; font-weight: 300; letter-spacing: .05em; color: var(--primary);
        }

        .btn-checkout {
            display: block; width: 100%; height: 52px;
            background: var(--primary); color: var(--white);
            font-family: inherit; font-size: 10px; font-weight: 700;
            letter-spacing: .35em; text-transform: uppercase;
            border: 1px solid var(--primary); text-align: center;
            line-height: 52px;
            transition: background var(--t), color var(--t), border-color var(--t);
        }
        .btn-checkout:hover { background: var(--white); color: var(--primary) }

        .btn-continue {
            display: block; width: 100%; height: 44px;
            background: var(--white); color: var(--primary);
            font-family: inherit; font-size: 10px; font-weight: 700;
            letter-spacing: .28em; text-transform: uppercase;
            border: 1px solid var(--gray); text-align: center;
            line-height: 44px; margin-top: .6rem;
            transition: border-color var(--t), color var(--t);
        }
        .btn-continue:hover { border-color: var(--primary) }

        .summary-note {
            font-size: 9px; letter-spacing: .15em; text-transform: uppercase;
            color: var(--o60); text-align: center; margin-top: .9rem;
            font-style: italic; opacity: .7;
        }

        /* ── EMPTY STATE ── */
        .empty-state { text-align: center; padding: 6rem 1rem }
        .empty-icon { width: 48px; height: 48px; margin: 0 auto 2rem; opacity: .15 }
        .empty-text {
            font-size: 11px; letter-spacing: .35em; text-transform: uppercase;
            color: var(--o60); margin-bottom: 2.5rem; font-style: italic;
        }
        .btn-shop {
            display: inline-block; padding: 14px 40px;
            font-size: 10px; font-weight: 700; letter-spacing: .35em; text-transform: uppercase;
            background: var(--primary); color: var(--white);
            border: 1px solid var(--primary);
            transition: background var(--t), color var(--t);
        }
        .btn-shop:hover { background: var(--white); color: var(--primary) }

        /* ── FOOTER ── */
        footer { background: var(--primary); color: var(--white); padding: 1.25rem }
        .f-copy {
            text-align: center; font-size: 9px; letter-spacing: .3em;
            text-transform: uppercase; color: rgba(255,255,255,.4);
        }

        /* ── TOAST ── */
        .toast {
            position: fixed; bottom: 1.5rem; left: 50%; transform: translateX(-50%);
            background: var(--primary); color: var(--white);
            font-size: 10px; letter-spacing: .2em; text-transform: uppercase;
            padding: .75rem 1.5rem; z-index: 999;
            opacity: 0; transition: opacity .3s ease; pointer-events: none;
            white-space: nowrap;
        }
        .toast.show { opacity: 1 }
    </style>
</head>
<body>

    {{-- Navbar --}}
    <header class="navbar" role="banner">
        <div class="navbar-inner">
            <a href="{{ route('home') }}" class="back-btn" aria-label="Continue shopping">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="15 18 9 12 15 6"/>
                </svg>
                <span>Collection</span>
            </a>
            <a href="{{ route('home') }}">
                <img src="{{ Storage::url('LOGO-FARHANA-NEW-TRANSPARENT.png') }}"
                    alt="Farhana"
                    class="h-20 w-auto object-contain">
            </a>
        </div>
    </header>

    <main class="page" role="main">

        {{-- Page Header --}}
        <div class="page-head">
            <h1 class="page-title">Cart</h1>
            <div class="page-head-line"></div>
            <span class="item-count">
                <span id="cart-count-head">{{ count($cart ?? []) }}</span> items
            </span>
        </div>

        @if($cart && count($cart) > 0)

            {{-- Table Header (desktop only) --}}
            <div class="cart-head">
                <span></span>
                <span>Product</span>
                <span class="col-qty">Quantity</span>
                <span class="col-sub">Subtotal</span>
                <span class="col-act">Remove</span>
            </div>

            {{-- Cart Items --}}
            <div class="cart-table" id="cart-container">
                @php 
                    $total = 0;
                    $hasSoldOutItems = false;
                @endphp
                @foreach($cart as $id => $details)
                    @php 
                        $total += $details['price'] * $details['quantity']; 
                        $variant = \App\Models\ProductVariant::find($id);
                        $isSoldOut = false;
                        if ($variant) {
                            $isSoldOut = !$variant->product->is_preorder && $variant->stock <= 0;
                        }
                        if ($isSoldOut) {
                            $hasSoldOutItems = true;
                        }
                    @endphp

                    <div class="cart-row" id="item-row-{{ $id }}">

                        {{-- Image --}}
                        <div class="col-img {{ $isSoldOut ? 'opacity-60 grayscale' : '' }}" style="{{ $isSoldOut ? 'filter: grayscale(100%);' : '' }}">
                            <div class="prod-img">
                                @if(!empty($details['image']))
                                    <img src="{{ asset('storage/' . $details['image']) }}"
                                         alt="{{ $details['name'] }}" loading="lazy">
                                @endif
                            </div>
                        </div>

                        {{-- Info --}}
                        <div class="col-info {{ $isSoldOut ? 'opacity-60' : '' }}">
                            <p class="prod-name">{{ $details['name'] }}</p>
                            <p class="prod-meta">{{ $details['color'] }} / {{ $details['size'] }}</p>
                            <p class="prod-price">Rp {{ number_format($details['price'], 0, ',', '.') }}</p>
                            @if(!empty($details['is_preorder']) && $details['is_preorder'])
                                <span class="preorder-tag">Pre-Order</span>
                            @endif
                            @if($isSoldOut)
                                <span class="sold-out-tag" style="display: inline-block; margin-top: .4rem; font-size: 8px; font-weight: 700; letter-spacing: .18em; text-transform: uppercase; padding: 2px 8px; background: #C0392B; color: #FFFFFF;">Sold Out</span>
                            @endif
                        </div>

                        {{-- Quantity --}}
                        <div class="col-qty">
                            <div class="qty-wrap {{ $isSoldOut ? 'opacity-40' : '' }}" data-id="{{ $id }}" style="{{ $isSoldOut ? 'pointer-events: none;' : '' }}">
                                <button type="button" class="qty-btn btn-update"
                                        data-action="decrease"
                                        aria-label="Decrease"
                                        {{ $isSoldOut || $details['quantity'] <= 1 ? 'disabled' : '' }}>−</button>
                                <span class="qty-val" id="qty-val-{{ $id }}">{{ $details['quantity'] }}</span>
                                <button type="button" class="qty-btn btn-update"
                                        data-action="increase"
                                        aria-label="Increase"
                                        {{ $isSoldOut ? 'disabled' : '' }}>+</button>
                            </div>
                        </div>

                        {{-- Subtotal --}}
                        <div class="col-sub">
                            <span id="item-subtotal-{{ $id }}">
                                Rp {{ number_format($details['price'] * $details['quantity'], 0, ',', '.') }}
                            </span>
                        </div>

                        {{-- Remove --}}
                        <div class="col-act">
                            <form action="{{ route('cart.remove', $id) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="submit" class="remove-btn" aria-label="Remove product">Remove</button>
                            </form>
                        </div>

                    </div>
                @endforeach
            </div>

            {{-- Summary --}}
            <div class="summary-wrap">
                <div class="summary-box">
                    <div class="summary-row">
                        <span class="summary-label">Subtotal</span>
                        <span class="summary-val" id="cart-total">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-label">Shipping</span>
                        <span class="summary-val" style="color:var(--o60);font-style:italic">Calculated at checkout</span>
                    </div>
                    <div class="summary-total-row">
                        <span class="summary-total-label">Total</span>
                        <span class="summary-total-val" id="cart-total-big">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>

                    @if($hasSoldOutItems)
                        <div class="sold-out-warning" style="background: #FDEDEC; border: 1px solid #FADBD8; color: #C0392B; padding: 12px; font-size: 10px; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; text-align: center; margin-bottom: 1.2rem;">
                            Remove sold out items before checkout
                        </div>
                        <button class="btn-checkout" style="opacity: 0.4; cursor: not-allowed;" disabled onclick="showToast('Please remove sold out items from your cart.')">Checkout</button>
                    @else
                        <a href="{{ route('checkout.index') }}" class="btn-checkout">Checkout</a>
                    @endif
                    <a href="{{ route('home') }}" class="btn-continue">Continue Shopping</a>
                    <p class="summary-note">Taxes & shipping calculated at checkout</p>
                </div>
            </div>

        @else

            {{-- Empty State --}}
            <div class="empty-state">
                <svg class="empty-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/>
                    <line x1="3" y1="6" x2="21" y2="6"/>
                    <path d="M16 10a4 4 0 01-8 0"/>
                </svg>
                <p class="empty-text">Your shopping cart is empty.</p>
                <a href="{{ route('home') }}" class="btn-shop">Start Shopping</a>
            </div>

        @endif
    </main>

    <footer role="contentinfo">
        <p class="f-copy">&copy; 2026 Farhana Official. All Rights Reserved.</p>
    </footer>

    {{-- Toast --}}
    <div class="toast" id="toast" role="alert" aria-live="polite"></div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
    function showToast(msg, duration = 2500) {
        const t = document.getElementById('toast');
        t.textContent = msg;
        t.classList.add('show');
        setTimeout(() => t.classList.remove('show'), duration);
    }

    function syncTotals(cartTotal) {
        document.getElementById('cart-total').textContent     = cartTotal;
        document.getElementById('cart-total-big').textContent = cartTotal;
    }

    document.querySelectorAll('.btn-update').forEach(button => {
        button.addEventListener('click', function () {
            const wrapper = this.closest('.qty-wrap');
            const id      = wrapper.getAttribute('data-id');
            const action  = this.getAttribute('data-action');
            const row     = document.getElementById(`item-row-${id}`);

            row.classList.add('updating');

            axios.patch(`/cart/update/${id}`, { action })
                .then(res => {
                    if (res.data.success) {
                        document.getElementById(`qty-val-${id}`).textContent       = res.data.newQty;
                        document.getElementById(`item-subtotal-${id}`).textContent = res.data.itemSubtotal;
                        syncTotals(res.data.cartTotal);

                        const btnMinus = wrapper.querySelector('[data-action="decrease"]');
                        btnMinus.disabled = res.data.newQty <= 1;
                    }
                })
                .catch(err => {
                    const msg = err.response?.data?.message || 'Failed to update cart.';
                    showToast(msg);
                })
                .finally(() => row.classList.remove('updating'));
        });
    });
    </script>
</body>
</html>