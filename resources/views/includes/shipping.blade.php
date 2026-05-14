<section id="shipping-section" class="py-24 md:py-32 scroll-mt-20 relative overflow-hidden" style="background:#FFFFFF;">
<style>
    /* ── SHIPPING ── */
    .sh-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1.25rem;
    }
    @media (min-width: 768px) { .sh-grid { grid-template-columns: 1fr 1fr; gap: 1.5rem; } }

    .sh-card {
        border: 1px solid #E9E9E9;
        border-radius: 20px;
        padding: 2rem;
        cursor: default;
        transition: border-color .35s ease, transform .4s cubic-bezier(.22,1,.36,1);
    }
    .sh-card:hover {
        border-color: #6B705C;
        transform: translateY(-4px);
    }

    .sh-icon-box {
        width: 46px; height: 46px;
        border-radius: 14px;
        background: #2F3526;
        display: flex; align-items: center; justify-content: center;
        margin-bottom: 1.25rem;
        transition: transform .4s cubic-bezier(.22,1,.36,1);
    }
    .sh-card:hover .sh-icon-box {
        transform: rotate(-6deg) scale(1.06);
    }

    .sh-card-title {
        font-size: 13px; font-weight: 700;
        letter-spacing: .14em; text-transform: uppercase;
        color: #2F3526; margin: 0 0 .6rem;
        line-height: 1.3;
    }
    .sh-card-desc {
        font-size: 13px; color: #6B705C;
        line-height: 1.85; margin: 0 0 1.25rem;
    }

    .sh-list {
        list-style: none; padding: 0; margin: 0;
        display: flex; flex-direction: column; gap: 8px;
        border-top: 1px solid #E9E9E9; padding-top: 1.1rem;
    }
    .sh-list li {
        display: flex; align-items: center; gap: 10px;
        font-size: 9px; font-weight: 700;
        letter-spacing: .22em; text-transform: uppercase; color: #000;
    }
    .sh-bullet {
        width: 5px; height: 5px; border-radius: 50%;
        background: #2F3526; flex-shrink: 0;
    }
</style>

<div class="max-w-[1000px] mx-auto px-6 md:px-12 lg:px-20">

    {{-- Header --}}
    <div style="text-align:center; margin-bottom: 3rem;">
        <span style="display:block; font-size:9px; font-weight:700; letter-spacing:.3em; text-transform:uppercase; color:#6B705C; margin-bottom:.4rem;">Logistics & Care</span>
        <h2 style="font-size:clamp(1.7rem,4vw,2.6rem); font-weight:300; letter-spacing:.08em; text-transform:uppercase; color:#2F3526; margin:0;">
            Shipping & <strong style="font-weight:900; color:#000;">Returns</strong>
        </h2>
        <div style="width:36px; height:1.5px; background:#2F3526; margin:1.1rem auto 0;"></div>
    </div>

    <div class="sh-grid">

        {{-- Card: Pengiriman --}}
        <div class="sh-card">
            <div class="sh-icon-box">
                <svg width="20" height="20" fill="none" stroke="#FFFFFF" viewBox="0 0 24 24" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 012 2v2a2 2 0 01-2 2H4a2 2 0 01-2-2v-2a2 2 0 012-2m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                </svg>
            </div>
            <h3 class="sh-card-title">Layanan Pengiriman</h3>
            <p class="sh-card-desc">
                Setiap pesanan dipersiapkan dengan penuh perhatian untuk menjaga kualitas produk tetap sampai dengan baik ke tangan Anda.
            </p>
            <ul class="sh-list">
                <li><span class="sh-bullet"></span>Pesanan diproses setelah pembayaran terkonfirmasi</li>
                <li><span class="sh-bullet"></span>Resi akan diinformasikan maksimal H+1 setelah pengiriman</li>
                <li><span class="sh-bullet"></span>Pengiriman dilakukan melalui jasa ekspedisi terpercaya</li>
                <li><span class="sh-bullet"></span>Untuk pengiriman internasional, silakan hubungi Customer Service Farhana</li>
            </ul>
        </div>

      {{-- Card: Retur --}}
<div class="sh-card">
    <div class="sh-icon-box">
        <svg width="20" height="20" fill="none" stroke="#FFFFFF" viewBox="0 0 24 24" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
        </svg>
    </div>
    <h3 class="sh-card-title">Garansi & Retur</h3>
    <p class="sh-card-desc">
        Penukaran hanya berlaku untuk kesalahan pengiriman atau produk rusak dengan ketentuan berikut:
    </p>
    <ul class="sh-list">
        <li><span class="sh-bullet"></span>Wajib menyertakan video unboxing tanpa cut/edit</li>
        <li><span class="sh-bullet"></span>Tag produk masih terpasang</li>
        <li><span class="sh-bullet"></span>Maksimal pengajuan H+3 setelah produk diterima</li>
        <li><span class="sh-bullet"></span>Ongkir pengembalian ditanggung customer</li>
        <li><span class="sh-bullet"></span>Penukaran mengikuti ketersediaan stok</li>
        <li><span class="sh-bullet"></span>Produk akan diproses setelah barang retur diterima dan diperiksa</li>
    </ul>
    
    <p class="sh-card-desc" style="margin-top: 15px;">
        Penukaran size atau warna karena ketidaksesuaian pilihan pribadi tidak dapat dilakukan.
    </p>
    <p class="sh-card-desc" style="font-style: margin-top: 10px;">
        Semoga Allah Ta’ala meridhai muamalah kita.
    </p>
</div>

    </div>
</div>
</section>