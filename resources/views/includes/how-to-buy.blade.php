<section id="how-to-buy-section" class="py-24 md:py-32 scroll-mt-20 relative overflow-hidden" style="background:#FFFFFF;">
<style>
    /* ── HOW TO BUY ── */
    .htb-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    @media (min-width: 640px)  { .htb-grid { grid-template-columns: 1fr 1fr; } }
    @media (min-width: 1024px) { .htb-grid { grid-template-columns: repeat(4, 1fr); } }

    .htb-card {
        border: 1px solid #E9E9E9;
        border-radius: 20px;
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        min-height: 190px;
        cursor: default;
        position: relative;
        overflow: hidden;
        transition: border-color .35s ease, transform .4s cubic-bezier(.22,1,.36,1);
    }
    .htb-card:hover {
        border-color: #6B705C;
        transform: translateY(-5px);
    }

    .htb-card-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1.5rem;
    }
    .htb-dot {
        width: 9px; height: 9px; border-radius: 50%;
        background: rgba(47,53,38,.12);
        margin-top: 5px; flex-shrink: 0;
        transition: background .35s ease, transform .35s ease;
    }
    .htb-card:hover .htb-dot { background: #2F3526; transform: scale(1.25); }

    .htb-num {
        font-size: 52px; font-weight: 200; line-height: 1;
        color: rgba(47,53,38,.06);
        transition: color .4s ease;
    }
    .htb-card:hover .htb-num { color: rgba(47,53,38,.16); }

    .htb-step-title {
        font-size: 10px; font-weight: 700;
        letter-spacing: .22em; text-transform: uppercase;
        color: #2F3526; margin: 0 0 5px;
    }
    .htb-step-desc {
        font-size: 12px; color: #6B705C;
        line-height: 1.7; margin: 0;
    }

    .htb-line {
        position: absolute;
        bottom: 1.5rem; left: 1.5rem;
        height: 1px; width: 0;
        background: #2F3526;
        transition: width .45s cubic-bezier(.22,1,.36,1);
    }
    .htb-card:hover .htb-line { width: 32px; }

    /* Payment Info Styling */
    .payment-info-wrapper {
        margin-top: 4rem;
        border-top: 1px solid #F1F1F1;
        padding-top: 3rem;
    }
    .payment-info-title {
        font-size: 11px;
        font-weight: 800;
        letter-spacing: .3em;
        text-transform: uppercase;
        color: #2F3526;
        margin-bottom: 1.5rem;
    }
    .payment-text {
        font-size: 12px;
        color: #6B705C;
        line-height: 1.8;
    }
    .payment-list-simple {
        list-style: none;
        padding: 0;
        margin: 10px 0 20px 0;
    }
    .payment-list-simple li {
        position: relative;
        padding-left: 15px;
        margin-bottom: 5px;
    }
    .payment-list-simple li::before {
        content: "•";
        position: absolute;
        left: 0;
        color: #2F3526;
    }
</style>

<div class="max-w-[1400px] mx-auto px-6 md:px-12 lg:px-20">

    {{-- Header --}}
    <div style="text-align:center; margin-bottom: 3rem;">
        <span style="display:block; font-size:9px; font-weight:700; letter-spacing:.3em; text-transform:uppercase; color:#6B705C; margin-bottom:.4rem;">Shopping Guide</span>
        <h2 style="font-size:clamp(1.7rem,4vw,2.6rem); font-weight:300; letter-spacing:.08em; text-transform:uppercase; color:#2F3526; margin:0;">
            How To <strong style="font-weight:900; color:#000;">Buy</strong>
        </h2>
        <div style="width:36px; height:1.5px; background:#2F3526; margin:1.1rem auto 0;"></div>
    </div>

    @php
        $steps = [
            ['01', 'Browse',   'Temukan koleksi Farhana yang sesuai dengan kebutuhan dan kenyamanan Anda.'],
            ['02', 'Secure',   'Lengkapi detail pengiriman dengan benar sebelum melanjutkan pesanan.'],
            ['03', 'Payment',  'Selesaikan pembayaran melalui metode pembayaran yang tersedia.'],
            ['04', 'Receive',  'Pesanan akan segera kami proses dan kirimkan ke alamat tujuan Anda.'],
        ];
    @endphp

    <div class="htb-grid">
        @foreach($steps as $step)
        <div class="htb-card">
            <div class="htb-card-top">
                <span class="htb-dot"></span>
                <span class="htb-num">{{ $step[0] }}</span>
            </div>
            <div>
                <p class="htb-step-title">{{ $step[1] }}</p>
                <p class="htb-step-desc">{{ $step[2] }}</p>
            </div>
            <div class="htb-line"></div>
        </div>
        @endforeach
    </div>

    {{-- Payment Information --}}
    <div class="payment-info-wrapper">
        <h3 class="payment-info-title">PAYMENT INFORMATION</h3>
        <div class="payment-text">
            <p>Farhana menerima pembayaran melalui:</p>
            <ul class="payment-list-simple">
                <li>Transfer Bank</li>
                <li>Virtual Account</li>
                <li>E-Wallet</li>
            </ul>
            <p>Pesanan akan diproses setelah pembayaran berhasil dikonfirmasi.</p>
            <p>Pembayaran yang tidak diselesaikan dalam batas waktu tertentu akan otomatis dibatalkan oleh sistem.</p>
        </div>
    </div>

</div>
</section>