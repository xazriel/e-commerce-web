<section id="faqs-section" class="py-24 md:py-32 scroll-mt-20 relative overflow-hidden" style="background:#FFFFFF;">
<style>
    /* ── FAQ ── */
    .faq-item {
        border: 1px solid #E9E9E9;
        border-radius: 20px;
        overflow: hidden;
        margin-bottom: 12px;
        transition: border-color .35s ease;
    }
    .faq-item:hover { border-color: #6B705C; }

    .faq-summary {
        padding: 1.25rem 1.5rem;
        cursor: pointer;
        list-style: none;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1.25rem;
        transition: background .25s ease;
    }
    .faq-summary::-webkit-details-marker { display: none; }
    .faq-item[open] .faq-summary { background: rgba(47,53,38,.025); }

    .faq-q {
        font-size: 10px; font-weight: 700;
        letter-spacing: .2em; text-transform: uppercase;
        color: #2F3526; margin: 0; line-height: 1.55;
    }
    @media (min-width: 768px) { .faq-q { font-size: 11px; } }

    .faq-toggle {
        width: 30px; height: 30px; flex-shrink: 0;
        border-radius: 50%; border: 1px solid #E9E9E9;
        display: flex; align-items: center; justify-content: center;
        transition: background .3s ease, border-color .3s ease, transform .4s ease;
    }
    .faq-toggle svg { transition: stroke .3s ease; }
    .faq-item[open] .faq-toggle {
        background: #2F3526; border-color: #2F3526;
        transform: rotate(180deg);
    }
    .faq-item[open] .faq-toggle svg { stroke: #FFFFFF; }

    .faq-body {
        padding: 0 1.5rem 1.25rem;
        font-size: 13px; color: #6B705C;
        line-height: 1.9;
    }
</style>

<div class="max-w-[900px] mx-auto px-6 md:px-12">

    {{-- Header --}}
    <div style="text-align:center; margin-bottom: 3rem;">
        <span style="display:block; font-size:9px; font-weight:700; letter-spacing:.3em; text-transform:uppercase; color:#6B705C; margin-bottom:.4rem;">Information</span>
        <h2 style="font-size:clamp(1.7rem,4vw,2.6rem); font-weight:300; letter-spacing:.08em; text-transform:uppercase; color:#2F3526; margin:0;">
            F.A.<strong style="font-weight:900; color:#000;">Q</strong>
        </h2>
        <div style="width:36px; height:1.5px; background:#2F3526; margin:1.1rem auto 0;"></div>
    </div>

    @php
        $faqs = [
            [
                'Are products sold as a set?',
                'Some products are sold separately according to their respective categories.'
            ],
            [
                'How can I view my order status?',
                'Log in to your account and check order details via the profile/orders page.'
            ],
            [
                'When is the shipping done?',
                'Shipping is conducted on operational days according to the applicable schedule.'
            ],
            [
                'Are shipments processed on Saturdays and Sundays?',
                'Orders placed outside operational hours will be processed on the next business day.'
            ],
        ];
    @endphp

    @foreach($faqs as $faq)
    <details class="faq-item">
        <summary class="faq-summary">
            <p class="faq-q">{{ $faq[0] }}</p>
            <div class="faq-toggle">
                <svg width="14" height="14" fill="none" stroke="#6B705C" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 9l-7 7-7-7"/>
                </svg>
            </div>
        </summary>
        <div class="faq-body">{{ $faq[1] }}</div>
    </details>
    @endforeach

</div>
</section>