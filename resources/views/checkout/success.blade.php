<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pembayaran Berhasil - Farhana</title>
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
            background: var(--primary);
            color: var(--black);
            -webkit-font-smoothing: antialiased;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ── Animations ── */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes checkDraw {
            from { stroke-dashoffset: 60; opacity: 0; }
            to   { stroke-dashoffset: 0;  opacity: 1; }
        }
        @keyframes ringPop {
            0%   { transform: scale(.7);  opacity: 0; }
            70%  { transform: scale(1.08); opacity: 1; }
            100% { transform: scale(1);   opacity: 1; }
        }
        @keyframes shimmerIn {
            from { opacity: 0; transform: translateY(8px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .fade-up  { animation: fadeUp .55s cubic-bezier(.22,.68,0,1.2) both; }
        .delay-1  { animation-delay: .1s; }
        .delay-2  { animation-delay: .2s; }
        .delay-3  { animation-delay: .3s; }
        .delay-4  { animation-delay: .45s; }
        .delay-5  { animation-delay: .58s; }

        /* ── Header strip ── */
        .site-header {
            background: var(--primary);
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .header-logo {
            font-size: 13px;
            font-weight: 700;
            letter-spacing: .45em;
            text-transform: uppercase;
            color: var(--white);
        }

        /* ── Main bg split ── */
        .page-body {
            flex: 1;
            background: linear-gradient(to bottom, var(--primary) 180px, var(--bg) 180px);
            display: flex;
            align-items: flex-start;
            justify-content: center;
            padding: 40px 20px 80px;
        }

        /* ── Card ── */
        .success-card {
            background: var(--white);
            border-radius: 28px;
            width: 100%;
            max-width: 440px;
            overflow: hidden;
            box-shadow: 0 24px 60px rgba(0,0,0,.18);
        }

        /* ── Card top (hero) ── */
        .card-hero {
            background: var(--bg);
            padding: 44px 32px 32px;
            text-align: center;
            border-bottom: 1px solid var(--light-gray);
        }

        /* Check icon */
        .check-ring {
            width: 72px; height: 72px;
            border-radius: 50%;
            background: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            animation: ringPop .55s cubic-bezier(.22,.68,0,1.2) both;
            animation-delay: .15s;
        }
        .check-ring svg {
            width: 32px; height: 32px;
        }
        .check-ring svg path {
            stroke: var(--white);
            stroke-width: 2.5;
            stroke-linecap: round;
            stroke-linejoin: round;
            fill: none;
            stroke-dasharray: 60;
            stroke-dashoffset: 60;
            animation: checkDraw .45s ease forwards;
            animation-delay: .5s;
        }

        .hero-title {
            font-size: 18px;
            font-weight: 800;
            color: var(--black);
            letter-spacing: -.01em;
            margin-bottom: 6px;
            animation: shimmerIn .4s ease both;
            animation-delay: .35s;
        }
        .hero-sub {
            font-size: 10px;
            color: var(--olive-tint);
            letter-spacing: .08em;
            animation: shimmerIn .4s ease both;
            animation-delay: .42s;
        }

        /* ── Card body ── */
        .card-body { padding: 28px 28px 32px; }

        /* Section label */
        .section-label {
            font-size: 9px;
            letter-spacing: .2em;
            text-transform: uppercase;
            color: var(--olive-tint);
            font-weight: 600;
            margin-bottom: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .section-label::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--light-gray);
        }

        /* Order rows */
        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            margin-bottom: 12px;
            font-size: 12px;
        }
        .info-row-label { color: var(--olive-tint); }
        .info-row-val   { font-weight: 600; color: var(--black); text-align: right; }

        /* Status pill */
        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: rgba(47,53,38,.08);
            color: var(--primary);
            font-size: 9px;
            font-weight: 700;
            letter-spacing: .1em;
            text-transform: uppercase;
            padding: 4px 10px;
            border-radius: 20px;
        }
        .status-dot {
            width: 5px; height: 5px;
            border-radius: 50%;
            background: var(--primary);
        }

        /* Resi link */
        .resi-link {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            color: var(--primary);
            font-weight: 700;
            text-decoration: none;
            font-size: 12px;
            transition: opacity .2s;
        }
        .resi-link:hover { opacity: .65; }

        /* Resi pending */
        .resi-pending {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 10px;
            color: var(--olive-tint);
            font-style: italic;
        }

        /* Divider */
        .divider {
            height: 1px;
            background: var(--light-gray);
            margin: 16px 0;
        }

        /* Total row */
        .total-row {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
        }
        .total-label {
            font-size: 9px;
            letter-spacing: .2em;
            text-transform: uppercase;
            color: var(--olive-tint);
        }
        .total-val {
            font-size: 22px;
            font-weight: 800;
            color: var(--black);
            letter-spacing: -.02em;
        }

        /* Notice */
        .notice {
            background: rgba(251,191,36,.1);
            border: 1px solid rgba(251,191,36,.3);
            border-radius: 14px;
            padding: 14px 16px;
            margin-top: 16px;
        }
        .notice p {
            font-size: 10px;
            color: #92400e;
            line-height: 1.7;
            letter-spacing: .02em;
        }

        /* ── Buttons ── */
        .btn-primary {
            display: block;
            width: 100%;
            background: var(--primary);
            color: var(--white);
            border: none;
            padding: 17px;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: .25em;
            text-transform: uppercase;
            border-radius: 50px;
            text-align: center;
            text-decoration: none;
            cursor: pointer;
            transition: background .25s;
            margin-bottom: 10px;
        }
        .btn-primary:hover { background: var(--black); }

        .btn-secondary {
            display: block;
            width: 100%;
            background: none;
            border: 1px solid var(--light-gray);
            color: var(--olive-tint);
            padding: 15px;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 10px;
            font-weight: 600;
            letter-spacing: .2em;
            text-transform: uppercase;
            border-radius: 50px;
            text-align: center;
            text-decoration: none;
            cursor: pointer;
            transition: all .25s;
        }
        .btn-secondary:hover { border-color: var(--primary); color: var(--primary); }

        .btn-wrap { margin-top: 24px; }
    </style>
</head>
<body>

<header class="site-header fade-up">
<div class="header-logo">
    <a href="{{ route('home') }}">
        <img src="{{ Storage::url('LOGO-FARHANA-NEW-TRANSPARENT_WHITE.png') }}" 
             alt="Farhana" 
             class="h-14 md:h-20 w-auto object-contain">
    </a>
</div></header>

<div class="page-body">
    <div class="success-card fade-up delay-1">

        {{-- Hero --}}
        <div class="card-hero">
            <div class="check-ring">
                <svg viewBox="0 0 24 24">
                    <path d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <h1 class="hero-title">Pembayaran Berhasil</h1>
            <p class="hero-sub">Terima kasih telah berbelanja di Farhana</p>
        </div>

        {{-- Body --}}
        <div class="card-body">

            {{-- Order detail --}}
            <div class="fade-up delay-2">
                <div class="section-label">Detail Pesanan</div>

                <div class="info-row">
                    <span class="info-row-label">No. Order</span>
                    <span class="info-row-val">{{ $order->order_number }}</span>
                </div>

                <div class="info-row">
                    <span class="info-row-label">Status</span>
                    <span class="info-row-val">
                        <span class="status-pill">
                            <span class="status-dot"></span>
                            {{ ucfirst($order->status) }}
                        </span>
                    </span>
                </div>

                <div class="info-row">
                    <span class="info-row-label">Kurir</span>
                    <span class="info-row-val">{{ $order->courier_name }}</span>
                </div>

                <div class="info-row" style="margin-bottom:0;">
                    <span class="info-row-label">No. Resi JNE</span>
                    <span class="info-row-val">
                        @if($order->tracking_number)
                        <a href="{{ route('tracking.show', $order->tracking_number) }}" class="resi-link">
                            {{ $order->tracking_number }}
                            <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                        @else
                        <span class="resi-pending">Sedang diproses…</span>
                        @endif
                    </span>
                </div>
            </div>

            <div class="divider"></div>

            {{-- Total --}}
            <div class="total-row fade-up delay-3">
                <span class="total-label">Total Pembayaran</span>
                <span class="total-val">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</span>
            </div>

            {{-- Notice resi --}}
            @if(!$order->tracking_number)
            <div class="notice fade-up delay-3">
                <p>Nomor resi JNE sedang diproses oleh tim kami. Cek halaman pesananmu untuk update terbaru.</p>
            </div>
            @endif

            {{-- Buttons --}}
            <div class="btn-wrap fade-up delay-4">
                <a href="{{ route('profile.orders') }}" class="btn-primary">Lihat Pesanan Saya</a>
                <a href="{{ route('home') }}" class="btn-secondary">Lanjut Belanja</a>
            </div>

        </div>
    </div>
</div>

</body>
</html>