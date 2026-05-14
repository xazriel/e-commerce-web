<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Menunggu Pembayaran - Farhana</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --primary:    #2F3526;
            --white:      #FFFFFF;
            --black:      #000000;
            --olive-tint: #6B705C;
            --light-gray: #E9E9E9;
            --bg:         #F5F5F3;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background: var(--bg);
            color: var(--black);
            -webkit-font-smoothing: antialiased;
            min-height: 100vh;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes pulse-ring {
            0%   { transform: scale(1);   opacity: .6; }
            50%  { transform: scale(1.08); opacity: 1; }
            100% { transform: scale(1);   opacity: .6; }
        }
        @keyframes tick {
            0%, 100% { transform: scaleY(1); }
            50%       { transform: scaleY(.92); }
        }

        .fade-up   { animation: fadeUp .5s cubic-bezier(.22,.68,0,1.2) both; }
        .delay-1   { animation-delay: .08s; }
        .delay-2   { animation-delay: .16s; }
        .delay-3   { animation-delay: .24s; }
        .delay-4   { animation-delay: .32s; }

        /* ── Header ── */
        .site-header {
            background: var(--primary);
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .header-logo {
            font-size: 13px;
            font-weight: 700;
            letter-spacing: .45em;
            text-transform: uppercase;
            color: var(--white);
        }

        /* ── Layout ── */
        .page-wrap {
            max-width: 480px;
            margin: 0 auto;
            padding: 40px 20px 80px;
        }

        /* ── Step indicator ── */
        .step-tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 9px;
            letter-spacing: .18em;
            text-transform: uppercase;
            color: var(--olive-tint);
            margin-bottom: 28px;
        }
        .step-dot {
            width: 6px; height: 6px;
            border-radius: 50%;
            background: var(--olive-tint);
            animation: pulse-ring 2s ease-in-out infinite;
        }

        /* ── Card base ── */
        .card {
            background: var(--white);
            border: 1px solid var(--light-gray);
            border-radius: 20px;
            padding: 28px 26px;
            margin-bottom: 14px;
            transition: box-shadow .2s;
        }
        .card:hover { box-shadow: 0 4px 20px rgba(47,53,38,.06); }

        .card-label {
            font-size: 9px;
            letter-spacing: .2em;
            text-transform: uppercase;
            color: var(--olive-tint);
            font-weight: 600;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .card-label::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--light-gray);
        }

        /* ── Countdown card ── */
        .countdown-card {
            background: var(--primary);
            border: none;
            border-radius: 24px;
            padding: 36px 28px;
            text-align: center;
            margin-bottom: 14px;
        }
        .countdown-sub {
            font-size: 9px;
            letter-spacing: .2em;
            text-transform: uppercase;
            color: rgba(255,255,255,.45);
            margin-bottom: 16px;
        }
        .countdown-timer {
            font-size: 56px;
            font-weight: 800;
            color: var(--white);
            letter-spacing: -.03em;
            font-variant-numeric: tabular-nums;
            line-height: 1;
            animation: tick 1s ease-in-out infinite;
        }
        .countdown-timer.expired {
            color: rgba(255,255,255,.3);
            animation: none;
        }
        .countdown-order {
            margin-top: 14px;
            font-size: 10px;
            color: rgba(255,255,255,.35);
            letter-spacing: .08em;
        }
        .countdown-order strong {
            color: rgba(255,255,255,.75);
            font-weight: 700;
        }

        /* ── Order items ── */
        .order-row {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            font-size: 12px;
            margin-bottom: 10px;
        }
        .order-row-label { color: var(--olive-tint); }
        .order-row-val   { font-weight: 600; color: var(--black); }
        .order-divider {
            height: 1px;
            background: var(--light-gray);
            margin: 16px 0;
        }
        .order-total-row {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
        }
        .order-total-label {
            font-size: 9px;
            letter-spacing: .2em;
            text-transform: uppercase;
            color: var(--olive-tint);
        }
        .order-total-val {
            font-size: 20px;
            font-weight: 800;
            color: var(--black);
            letter-spacing: -.02em;
        }

        /* ── Shipping info ── */
        .ship-name  { font-size: 13px; font-weight: 700; color: var(--black); margin-bottom: 6px; }
        .ship-meta  { font-size: 11px; color: var(--olive-tint); line-height: 1.8; }

        /* ── CTA ── */
        .btn-pay {
            width: 100%;
            background: var(--primary);
            color: var(--white);
            border: none;
            padding: 18px;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: .25em;
            text-transform: uppercase;
            border-radius: 50px;
            cursor: pointer;
            transition: all .25s;
            margin-bottom: 12px;
            display: block;
        }
        .btn-pay:hover:not(:disabled) { background: var(--black); }
        .btn-pay:disabled {
            opacity: .35;
            cursor: not-allowed;
        }

        .btn-cancel {
            width: 100%;
            background: none;
            border: 1px solid var(--light-gray);
            color: var(--olive-tint);
            padding: 15px;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 9px;
            font-weight: 600;
            letter-spacing: .2em;
            text-transform: uppercase;
            border-radius: 50px;
            cursor: pointer;
            transition: all .25s;
            display: block;
            text-align: center;
        }
        .btn-cancel:hover {
            border-color: #fca5a5;
            color: #ef4444;
        }

        .pay-note {
            font-size: 9px;
            color: var(--olive-tint);
            text-align: center;
            margin-top: 14px;
            line-height: 1.7;
            letter-spacing: .03em;
        }
    </style>
</head>
<body>

{{-- Header --}}
<header class="site-header fade-up">
<div class="header-logo">
    <a href="{{ route('home') }}">
        <img src="{{ Storage::url('LOGO-FARHANA-NEW-TRANSPARENT_WHITE.png') }}" 
             alt="Farhana" 
             class="h-14 md:h-20 w-auto object-contain">
    </a>
</div></header>

<div class="page-wrap">

    {{-- Step tag --}}
    <div class="step-tag fade-up delay-1">
        <span class="step-dot"></span>
        Menunggu Pembayaran
    </div>

    {{-- Countdown --}}
    <div class="countdown-card fade-up delay-1">
        <p class="countdown-sub">Selesaikan pembayaran dalam</p>
        <div id="countdown" class="countdown-timer">--:--</div>
        <p class="countdown-order">
            Order <strong>{{ $order->order_number }}</strong>
        </p>
    </div>

    {{-- Order Summary --}}
    <div class="card fade-up delay-2">
        <div class="card-label">Ringkasan Pesanan</div>

        @foreach($order->items as $item)
        <div class="order-row">
            <span class="order-row-label">
                {{ $item->product->name ?? 'Produk' }}
                <span style="opacity:.6;">× {{ $item->quantity }}</span>
            </span>
            <span class="order-row-val">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</span>
        </div>
        @endforeach

        <div class="order-divider"></div>

        <div class="order-row">
            <span class="order-row-label">Subtotal</span>
            <span class="order-row-val" style="font-weight:400;">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
        </div>
        <div class="order-row">
            <span class="order-row-label">Ongkos Kirim</span>
            <span class="order-row-val" style="font-weight:400;">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
        </div>
        <div class="order-row" style="margin-bottom:0;">
            <span class="order-row-label">Kurir</span>
            <span class="order-row-val" style="font-weight:500;">{{ $order->courier_name }}</span>
        </div>

        <div class="order-divider"></div>

        <div class="order-total-row">
            <span class="order-total-label">Total</span>
            <span class="order-total-val">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</span>
        </div>
    </div>

    {{-- Shipping Info --}}
    <div class="card fade-up delay-3">
        <div class="card-label">Dikirim Ke</div>
        <div class="ship-name">{{ $order->receiver_name }}</div>
        <div class="ship-meta">
            {{ $order->receiver_phone }}<br>
            {{ $order->receiver_address }}<br>
            @if($order->receiver_city)
            {{ $order->receiver_city }}{{ $order->receiver_zip ? ' ' . $order->receiver_zip : '' }}
            @endif
        </div>
    </div>

    {{-- CTA --}}
    <div class="fade-up delay-4">
        <button id="pay-button" class="btn-pay">Bayar Sekarang</button>

        <form action="{{ route('checkout.cancel', $order->order_number) }}" method="POST"
            onsubmit="return confirm('Batalkan pesanan ini?')">
            @csrf
            @method('PATCH')
            <button type="submit" class="btn-cancel">Batalkan Pesanan</button>
        </form>

        <p class="pay-note">Klik "Bayar Sekarang" untuk membuka halaman pembayaran Midtrans yang aman.</p>
    </div>

</div>

<script src="https://app.sandbox.midtrans.com/snap/snap.js"
    data-client-key="{{ $clientKey }}"></script>

<script>
    const deadline = new Date("{{ $order->payment_deadline }}").getTime();

    function updateCountdown() {
        const diff = deadline - new Date().getTime();
        const el = document.getElementById('countdown');
        if (diff <= 0) {
            el.textContent = '00:00';
            el.classList.add('expired');
            const btn = document.getElementById('pay-button');
            btn.disabled = true;
            return;
        }
        const m = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
        const s = Math.floor((diff % (1000 * 60)) / 1000);
        el.textContent = String(m).padStart(2, '0') + ':' + String(s).padStart(2, '0');
    }
    updateCountdown();
    setInterval(updateCountdown, 1000);

    document.getElementById('pay-button').onclick = function () {
        snap.pay('{{ $order->payment_token }}', {
            onSuccess: () => window.location.href = '/checkout/success/{{ $order->order_number }}',
            onPending: () => window.location.href = '/checkout/waiting/{{ $order->order_number }}',
            onError:   () => alert('Pembayaran gagal. Silakan coba lagi.'),
            onClose:   () => {}
        });
    };
</script>
</body>
</html>