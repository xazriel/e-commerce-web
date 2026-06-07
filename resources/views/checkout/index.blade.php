<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Checkout - Farhana</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('farhana.svg') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        :root {
            --primary:     #2F3526;
            --white:       #FFFFFF;
            --black:       #000000;
            --olive-tint:  #6B705C;
            --light-gray:  #E9E9E9;
            --bg:          #F5F5F3;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background: var(--bg);
            color: var(--black);
            -webkit-font-smoothing: antialiased;
        }

        input:focus, textarea:focus, select:focus, button:focus {
            outline: none !important;
            box-shadow: none !important;
        }
        input:-webkit-autofill {
            -webkit-text-fill-color: var(--black);
            -webkit-box-shadow: 0 0 0px 1000px #fff inset;
            transition: background-color 5000s ease-in-out 0s;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(18px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .fade-up { animation: fadeUp 0.55s cubic-bezier(.22,.68,0,1.2) both; }
        .delay-1 { animation-delay: .08s; }
        .delay-2 { animation-delay: .16s; }
        .delay-3 { animation-delay: .24s; }
        .delay-4 { animation-delay: .32s; }

        /* ── Header ── */
        .site-header {
            background: var(--primary);
            border-bottom: none;
            position: sticky;
            top: 0;
            z-index: 50;
            padding: 0 48px;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .header-back {
            display: flex;
            align-items: center;
            gap: 8px;
            color: rgba(255,255,255,.55);
            font-size: 10px;
            letter-spacing: .15em;
            text-transform: uppercase;
            text-decoration: none;
            transition: color .2s;
        }
        .header-back:hover { color: #fff; }
        .header-logo {
            font-size: 13px;
            font-weight: 700;
            letter-spacing: .45em;
            text-transform: uppercase;
            color: var(--white);
        }
        .header-spacer { width: 80px; }

        /* ── Progress bar ── */
        .progress-bar {
            background: var(--primary);
            padding: 0 48px 16px;
        }
        .progress-steps {
            display: flex;
            align-items: center;
            gap: 0;
            max-width: 480px;
            margin: 0 auto;
        }
        .progress-step {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 9px;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: rgba(255,255,255,.35);
        }
        .progress-step.active { color: rgba(255,255,255,.9); }
        .progress-step .dot {
            width: 6px; height: 6px;
            border-radius: 50%;
            background: rgba(255,255,255,.25);
            flex-shrink: 0;
        }
        .progress-step.active .dot { background: var(--white); }
        .progress-line {
            flex: 1;
            height: 1px;
            background: rgba(255,255,255,.12);
            margin: 0 12px;
        }

        /* ── Main layout ── */
        .checkout-main {
            max-width: 1100px;
            margin: 0 auto;
            padding: 48px 24px 80px;
            display: grid;
            grid-template-columns: 1fr 380px;
            gap: 40px;
            align-items: start;
        }
        @media (max-width: 900px) {
            .checkout-main { grid-template-columns: 1fr; }
            .site-header, .progress-bar { padding-left: 20px; padding-right: 20px; }
        }

        /* ── Section ── */
        .section { margin-bottom: 36px; }
        .section-label {
            font-size: 9px;
            letter-spacing: .2em;
            text-transform: uppercase;
            color: var(--olive-tint);
            font-weight: 600;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .section-label::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--light-gray);
        }

        /* ── Address display card ── */
        .addr-display {
            background: var(--white);
            border: 1px solid var(--light-gray);
            border-radius: 16px;
            padding: 22px 26px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 16px;
            transition: border-color .25s, box-shadow .25s;
        }
        .addr-display:hover { border-color: var(--olive-tint); box-shadow: 0 4px 20px rgba(47,53,38,.06); }
        .addr-name { font-size: 13px; font-weight: 700; color: var(--black); margin-bottom: 4px; }
        .addr-meta { font-size: 11px; color: var(--olive-tint); line-height: 1.8; }
        .btn-change {
            font-size: 9px;
            letter-spacing: .15em;
            text-transform: uppercase;
            color: var(--primary);
            background: none;
            border: 1px solid rgba(47,53,38,.3);
            padding: 7px 16px;
            border-radius: 20px;
            cursor: pointer;
            white-space: nowrap;
            transition: all .2s;
            font-family: inherit;
            flex-shrink: 0;
        }
        .btn-change:hover { background: var(--primary); color: var(--white); border-color: var(--primary); }

        /* ── Courier & Payment cards ── */
        .option-card {
            background: var(--white);
            border: 1px solid var(--light-gray);
            border-radius: 14px;
            padding: 16px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            cursor: pointer;
            transition: border-color .2s, box-shadow .2s, background .2s;
            margin-bottom: 10px;
        }
        .option-card:hover { border-color: var(--olive-tint); box-shadow: 0 3px 14px rgba(47,53,38,.07); }
        .option-card.selected {
            border-color: var(--primary);
            background: rgba(47,53,38,.03);
            box-shadow: 0 4px 18px rgba(47,53,38,.1);
        }
        .option-card-left { display: flex; align-items: center; gap: 16px; }
        .option-badge {
            width: 42px; height: 42px;
            background: var(--primary);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 8px;
            font-weight: 800;
            letter-spacing: .06em;
            color: var(--white);
            flex-shrink: 0;
        }
        .option-badge.light { background: var(--light-gray); color: var(--primary); }
        .option-title {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .07em;
            color: var(--black);
            margin-bottom: 2px;
        }
        .option-desc { font-size: 10px; color: var(--olive-tint); }
        .option-price { font-size: 13px; font-weight: 700; color: var(--black); margin-right: 12px; }

        .radio-ring {
            width: 18px; height: 18px;
            border-radius: 50%;
            border: 1.5px solid var(--light-gray);
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            transition: border-color .2s;
        }
        .option-card.selected .radio-ring { border-color: var(--primary); }
        .radio-fill {
            width: 8px; height: 8px;
            border-radius: 50%;
            background: var(--primary);
            opacity: 0;
            transition: opacity .15s;
        }
        .option-card.selected .radio-fill { opacity: 1; }

        .courier-empty {
            border: 1.5px dashed var(--light-gray);
            border-radius: 16px;
            padding: 32px;
            text-align: center;
            font-size: 10px;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: var(--olive-tint);
        }

        /* ── Sidebar ── */
        .sidebar { position: sticky; top: 88px; }
        .sidebar-card {
            background: var(--primary);
            border-radius: 24px;
            padding: 32px 28px;
            color: var(--white);
        }
        .sidebar-title {
            font-size: 9px;
            letter-spacing: .25em;
            text-transform: uppercase;
            color: rgba(255,255,255,.45);
            margin-bottom: 24px;
            font-weight: 600;
        }
        .cart-item { display: flex; gap: 14px; margin-bottom: 20px; }
        .cart-img {
            width: 52px; height: 64px;
            border-radius: 8px;
            object-fit: cover;
            flex-shrink: 0;
            opacity: .9;
        }
        .cart-info { flex: 1; }
        .cart-name {
            font-size: 10px;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: var(--white);
            line-height: 1.4;
            margin-bottom: 4px;
        }
        .cart-variant { font-size: 10px; color: rgba(255,255,255,.45); }
        .cart-price { font-size: 11px; font-weight: 700; color: var(--white); margin-top: 6px; }

        .totals-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
            font-size: 11px;
        }
        .totals-row span:first-child { color: rgba(255,255,255,.5); }
        .totals-row span:last-child { color: rgba(255,255,255,.85); font-weight: 500; }
        .totals-divider { height: 1px; background: rgba(255,255,255,.1); margin: 16px 0; }
        .totals-grand { display: flex; justify-content: space-between; align-items: baseline; }
        .totals-grand-label {
            font-size: 9px;
            letter-spacing: .2em;
            text-transform: uppercase;
            color: rgba(255,255,255,.5);
        }
        .totals-grand-val {
            font-size: 22px;
            font-weight: 700;
            color: var(--white);
            letter-spacing: -.02em;
        }

        .btn-submit {
            width: 100%;
            margin-top: 28px;
            background: var(--white);
            color: var(--primary);
            border: none;
            padding: 16px;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: .25em;
            text-transform: uppercase;
            border-radius: 10px;
            cursor: pointer;
            transition: all .25s;
        }
        .btn-submit:hover:not(:disabled) { background: var(--black); color: var(--white); }
        .btn-submit:disabled { opacity: .3; cursor: not-allowed; }
        .btn-notice {
            font-size: 9px;
            color: rgba(255,255,255,.3);
            text-align: center;
            margin-top: 12px;
            line-height: 1.6;
            letter-spacing: .03em;
        }

        .custom-scrollbar::-webkit-scrollbar { width: 2px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,.15); }

        @keyframes shimmer {
            0%   { background-position: -600px 0; }
            100% { background-position: 600px 0; }
        }
        .skeleton {
            background: #e8e8e6;
            background-image: linear-gradient(to right, #e8e8e6 0%, #d8d8d5 20%, #e8e8e6 40%, #e8e8e6 100%);
            background-repeat: no-repeat;
            background-size: 800px 104px;
            animation: shimmer 1.4s infinite linear;
            border-radius: 4px;
        }

        /* ══ MODAL ══ */
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 200;
            background: rgba(0,0,0,.6);
            align-items: center;
            justify-content: center;
            padding: 16px;
        }
        .modal-overlay.active { display: flex; }
        .modal-wrap {
            background: var(--white);
            width: 100%;
            max-width: 520px;
            border-radius: 24px;
            overflow: hidden;
            max-height: 90vh;
            display: flex;
            flex-direction: column;
            box-shadow: 0 32px 80px rgba(0,0,0,.18);
        }
        .modal-header {
            background: var(--primary);
            padding: 20px 28px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-shrink: 0;
            gap: 12px;
        }
        .modal-header-left {
            display: flex;
            align-items: center;
            gap: 12px;
            flex: 1;
            min-width: 0;
        }
        .modal-back-btn {
            display: none;
            align-items: center;
            justify-content: center;
            background: rgba(255,255,255,.12);
            border: none;
            border-radius: 8px;
            width: 32px;
            height: 32px;
            cursor: pointer;
            color: rgba(255,255,255,.8);
            flex-shrink: 0;
            transition: background .2s, color .2s;
        }
        .modal-back-btn:hover { background: rgba(255,255,255,.2); color: #fff; }
        .modal-header-title {
            font-size: 10px;
            font-weight: 700;
            letter-spacing: .2em;
            text-transform: uppercase;
            color: var(--white);
        }
        .modal-close {
            color: rgba(255,255,255,.5);
            background: none;
            border: none;
            cursor: pointer;
            transition: color .2s;
            padding: 0;
            flex-shrink: 0;
        }
        .modal-close:hover { color: var(--white); }

        /* Address list items */
        .addr-item {
            border: 1px solid var(--light-gray);
            border-radius: 14px;
            padding: 18px 20px;
            cursor: pointer;
            transition: border-color .2s, background .2s, box-shadow .2s;
            margin-bottom: 10px;
        }
        .addr-item:hover { border-color: var(--olive-tint); box-shadow: 0 3px 12px rgba(47,53,38,.07); }
        .addr-item.addr-item-selected {
            border-color: var(--primary) !important;
            background: rgba(47,53,38,.04);
        }
        .addr-item.addr-item-selected .addr-ring { border-color: var(--primary) !important; }
        .addr-item.addr-item-selected .addr-dot  { opacity: 1 !important; }

        .addr-ring {
            width: 18px; height: 18px;
            border-radius: 50%;
            border: 1.5px solid var(--light-gray);
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            transition: border-color .2s;
        }
        .addr-dot {
            width: 8px; height: 8px;
            border-radius: 50%;
            background: var(--primary);
            opacity: 0;
            transition: opacity .15s;
        }
        .addr-badge {
            font-size: 8px;
            background: var(--primary);
            color: var(--white);
            padding: 2px 10px;
            border-radius: 20px;
            letter-spacing: .08em;
            text-transform: uppercase;
            font-weight: 700;
        }
        .addr-rec-name  { font-size: 12px; font-weight: 700; color: var(--black); }
        .addr-rec-meta  { font-size: 10px; color: var(--olive-tint); margin-top: 3px; line-height: 1.6; }

        /* Action buttons in addr-item */
        .addr-action-btns {
            display: flex;
            gap: 6px;
            margin-top: 12px;
            padding-top: 12px;
            border-top: 1px solid var(--light-gray);
        }
        .addr-action-btn {
            font-size: 9px;
            letter-spacing: .1em;
            text-transform: uppercase;
            font-weight: 700;
            font-family: inherit;
            padding: 6px 14px;
            border-radius: 20px;
            cursor: pointer;
            border: 1px solid;
            transition: all .2s;
        }
        .addr-action-btn.edit {
            color: var(--primary);
            border-color: rgba(47,53,38,.3);
            background: none;
        }
        .addr-action-btn.edit:hover { background: var(--primary); color: var(--white); border-color: var(--primary); }
        .addr-action-btn.delete {
            color: #dc2626;
            border-color: rgba(220,38,38,.25);
            background: none;
        }
        .addr-action-btn.delete:hover { background: #dc2626; color: #fff; border-color: #dc2626; }

        .modal-footer {
            padding: 16px 24px;
            border-top: 1px solid var(--light-gray);
            flex-shrink: 0;
        }
        .btn-add-addr {
            width: 100%;
            border: 1.5px dashed var(--light-gray);
            background: none;
            color: var(--olive-tint);
            font-family: inherit;
            font-size: 9px;
            letter-spacing: .18em;
            text-transform: uppercase;
            padding: 14px;
            border-radius: 14px;
            cursor: pointer;
            transition: all .2s;
        }
        .btn-add-addr:hover { border-color: var(--primary); color: var(--primary); }

        /* Form (add & edit) */
        .form-group {
            background: var(--bg);
            border: 1px solid var(--light-gray);
            border-radius: 12px;
            padding: 12px 16px;
            transition: border-color .25s;
        }
        .form-group:focus-within { border-color: var(--primary); background: var(--white); }
        .form-lbl {
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: .12em;
            color: var(--olive-tint);
            margin-bottom: 4px;
            display: block;
            font-weight: 600;
        }
        .form-fld {
            width: 100%;
            background: transparent;
            border: none;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 12px;
            color: var(--black);
            outline: none;
        }
        .form-fld::placeholder { color: #bbb; }

        /* Select2 inside modal */
        .select2-container { width: 100% !important; }
        .select2-container--default .select2-selection--single {
            background: var(--bg);
            border: 1px solid var(--light-gray);
            border-radius: 12px;
            height: 52px;
            display: flex;
            align-items: center;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 12px;
            transition: border-color .25s;
        }
        .select2-container--default.select2-container--focus .select2-selection--single {
            border-color: var(--primary) !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 52px;
            padding-left: 16px;
            color: var(--black);
            font-size: 12px;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow { height: 52px; }

        .modal-body {
            flex: 1;
            overflow-y: auto;
            padding: 24px;
        }
        .modal-body::-webkit-scrollbar { width: 2px; }
        .modal-body::-webkit-scrollbar-thumb { background: var(--light-gray); }

        /* Error box in form */
        .form-error-box {
            display: none;
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 10px;
            padding: 10px 16px;
            font-size: 11px;
            color: #dc2626;
        }

        /* Loading spinner for delete / save */
        .addr-action-btn:disabled { opacity: .5; cursor: not-allowed; }

        /* Toast */
        .toast {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 9999;
            background: #fee2e2;
            border: 1px solid #fca5a5;
            color: #991b1b;
            font-size: 11px;
            padding: 12px 24px;
            border-radius: 50px;
            letter-spacing: .03em;
        }
    </style>
</head>
<body>

@if(session('error'))
<div class="toast">{{ session('error') }}</div>
@endif

{{-- ══════════════════════════════════════════════════════
     MODAL: ADDRESS CRUD
══════════════════════════════════════════════════════ --}}
<div id="address-modal" class="modal-overlay">
    <div class="modal-wrap">

        {{-- Header --}}
        <div class="modal-header">
            <div class="modal-header-left">
                <button id="modal-back-btn" type="button" class="modal-back-btn" onclick="showListPanel()">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>
                <span id="modal-title" class="modal-header-title">Select Delivery Address</span>
            </div>
            <button type="button" onclick="closeAddressModal()" class="modal-close">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- ── PANEL 1: LIST ── --}}
        <div id="panel-list" style="display:flex; flex-direction:column; flex:1; overflow:hidden;">
            <div class="modal-body">
                @forelse($addresses as $addr)
                <div class="addr-item {{ $addr->is_default ? 'addr-item-selected' : '' }}"
                    id="addr-item-{{ $addr->id }}"
                    data-id="{{ $addr->id }}"
                    data-name="{{ $addr->recipient_name }}"
                    data-phone="{{ $addr->phone }}"
                    data-address="{{ $addr->address }}"
                    data-destination="{{ $addr->destination_id }}"
                    data-city="{{ $addr->city_name }}"
                    data-zip="{{ $addr->zip_code ?? $addr->postal_code }}"
                    data-label="{{ $addr->address_label ?? ($addr->city_name . ($addr->province_name ? ', '.$addr->province_name : '')) }}"
                    onclick="selectAddress({{ $addr->id }}, event)">

                    <div style="display:flex; align-items:flex-start; justify-content:space-between; gap:12px;">
                        <div style="flex:1; min-width:0;">
                            <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap; margin-bottom:4px;">
                                <span class="addr-rec-name">{{ $addr->recipient_name }}</span>
                                @if($addr->is_default)
                                <span class="addr-badge">Default</span>
                                @endif
                                @if($addr->address_label)
                                <span style="font-size:8px; background:var(--bg); color:var(--olive-tint); padding:2px 8px; border-radius:20px; letter-spacing:.06em; text-transform:uppercase; font-weight:600;">{{ $addr->address_label }}</span>
                                @endif
                            </div>
                            <div class="addr-rec-meta">
                                {{ $addr->phone }}<br>
                                {{ $addr->address }}<br>
                                {{ $addr->city_name }}{{ $addr->province_name ? ', '.$addr->province_name : '' }}
                            </div>
                            @if(!$addr->destination_id)
                            <p style="font-size:10px; color:#d97706; margin-top:6px;">⚠ Lokasi belum lengkap — perlu dipilih ulang.</p>
                            @endif
                        </div>
                        <div class="addr-ring">
                            <div class="addr-dot" style="{{ $addr->is_default ? 'opacity:1' : 'opacity:0' }}"></div>
                        </div>
                    </div>

                    {{-- Action buttons — stop propagation so it doesn't trigger select --}}
                    <div class="addr-action-btns" onclick="event.stopPropagation()">
                        <button type="button"
                            class="addr-action-btn edit"
                            onclick="openEditPanel({{ $addr->id }})">
                            ✏ Edit
                        </button>
                        <button type="button"
                            class="addr-action-btn delete"
                            id="del-btn-{{ $addr->id }}"
                            onclick="deleteAddress({{ $addr->id }}, this)">
                            🗑 Hapus
                        </button>
                    </div>
                </div>
                @empty
                <div style="padding:40px; text-align:center; font-size:11px; color:var(--olive-tint);">Belum ada alamat tersimpan.</div>
                @endforelse
            </div>
            <div class="modal-footer">
                <button type="button" onclick="openNewPanel()" class="btn-add-addr">+ Add New Address</button>
            </div>
        </div>

        {{-- ── PANEL 2: ADD / EDIT FORM ── --}}
        <div id="panel-form" style="display:none; flex-direction:column; flex:1; overflow:hidden;">
            <div class="modal-body" style="display:flex; flex-direction:column; gap:14px;">

                {{-- Hidden: tracks whether we're editing --}}
                <input type="hidden" id="form_address_id" value="">

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
                    <div class="form-group">
                        <label class="form-lbl">Nama Penerima *</label>
                        <input type="text" id="form_name" class="form-fld" placeholder="Nama lengkap">
                    </div>
                    <div class="form-group">
                        <label class="form-lbl">No. HP *</label>
                        <input type="text" id="form_phone" class="form-fld" placeholder="08xxxxxxxxxx">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-lbl">Label Alamat</label>
                    <input type="text" id="form_label" class="form-fld" placeholder="Rumah, Kantor, dll.">
                </div>

                <div>
                    <label class="form-lbl" style="margin-bottom:8px; display:block;">Kota / Kecamatan *</label>
                    <select id="form_destination" style="width:100%;">
                        <option value="">Cari kota atau kecamatan...</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-lbl">Alamat Lengkap *</label>
                    <textarea id="form_address" rows="3" class="form-fld" style="resize:none;" placeholder="Jalan, nomor rumah, RT/RW, dll."></textarea>
                </div>

                <div class="form-group">
                    <label class="form-lbl">Kode Pos</label>
                    <input type="text" id="form_zip" class="form-fld" placeholder="12345">
                </div>

                <label style="display:flex; align-items:center; gap:10px; cursor:pointer;">
                    <input type="checkbox" id="form_is_default" style="accent-color:var(--primary); width:15px; height:15px;">
                    <span style="font-size:11px; color:var(--olive-tint);">Jadikan alamat utama</span>
                </label>

                <div id="form-error-box" class="form-error-box"></div>
            </div>

            <div class="modal-footer" style="display:flex; gap:10px;">
                <button type="button" onclick="showListPanel()"
                    style="flex:1; padding:14px; border:1px solid var(--light-gray); border-radius:50px; font-family:inherit; font-size:9px; letter-spacing:.15em; text-transform:uppercase; cursor:pointer; background:none; color:var(--olive-tint); transition:all .2s;">
                    ← Kembali
                </button>
                <button type="button" id="btn-save-addr"
                    style="flex:2; padding:14px; background:var(--primary); color:var(--white); border:none; border-radius:50px; font-family:inherit; font-size:9px; letter-spacing:.18em; text-transform:uppercase; font-weight:800; cursor:pointer; transition:background .2s;">
                    Simpan Alamat
                </button>
            </div>
        </div>

    </div>{{-- /.modal-wrap --}}
</div>

{{-- ══════════════════════════════════════════════════════
     HEADER
══════════════════════════════════════════════════════ --}}
<header class="site-header fade-up">
    <a href="{{ route('cart.index') }}" class="header-back">
        <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Cart
    </a>
    <div class="header-logo">
        <a href="{{ route('home') }}">
            <img src="{{ Storage::url('LOGO-FARHANA-NEW-TRANSPARENT_WHITE.png') }}"
                 alt="Farhana"
                 class="h-14 md:h-20 w-auto object-contain">
        </a>
    </div>
    <div class="header-spacer"></div>
</header>

{{-- Progress --}}
<div class="progress-bar fade-up delay-1">
    <div class="progress-steps">
        <div class="progress-step">
            <div class="dot"></div>Cart
        </div>
        <div class="progress-line"></div>
        <div class="progress-step active">
            <div class="dot"></div>Checkout
        </div>
        <div class="progress-line"></div>
        <div class="progress-step">
            <div class="dot"></div>Confirm
        </div>
    </div>
</div>

<main>
    <form action="{{ route('checkout.store') }}" method="POST" id="checkout-form">
        @csrf

        @if(isset($isBuyNow) && $isBuyNow)
            <input type="hidden" name="buy_now_mode" value="1">
        @endif

        {{-- Hidden fields --}}
        <input type="hidden" name="email"            value="{{ $user->email }}">
        <input type="hidden" name="receiver_name"    id="hidden_receiver_name"    value="{{ $defaultAddr?->recipient_name ?? $user->name }}">
        <input type="hidden" name="receiver_phone"   id="hidden_receiver_phone"   value="{{ $defaultAddr?->phone ?? $user->phone }}">
        <input type="hidden" name="receiver_address" id="hidden_receiver_address" value="{{ $defaultAddr?->address ?? $user->address }}">
        <input type="hidden" name="destination_id"   id="hidden_destination_id"   value="{{ $defaultAddr?->destination_id ?? $user->destination_id }}">
        <input type="hidden" name="receiver_city"    id="hidden_receiver_city"    value="{{ $defaultAddr?->city_name }}">
        <input type="hidden" name="receiver_zip"     id="hidden_receiver_zip"     value="{{ $defaultAddr?->zip_code ?? $defaultAddr?->postal_code }}">
        <input type="hidden" name="shipping_cost"    id="hidden_shipping_cost">
        <input type="hidden" name="courier_name"     id="hidden_courier_name">
        <input type="hidden" name="service_code"     id="hidden_service_code">

        <div class="checkout-main">

            {{-- LEFT COLUMN --}}
            <div>

                {{-- ── 01 SHIPPING ADDRESS ── --}}
                <div class="section fade-up delay-2">
                    <div class="section-label">Shipping Address</div>

                    @if($defaultAddr)
                    <div class="addr-display">
                        <div>
                            <div class="addr-name" id="display-name">{{ $defaultAddr->recipient_name }}</div>
                            <div class="addr-meta">
                                <span id="display-phone">{{ $defaultAddr->phone }}</span><br>
                                <span id="display-address">{{ $defaultAddr->address }}</span><br>
                                <span id="display-location">{{ $defaultAddr->address_label ?? ($defaultAddr->city_name . ($defaultAddr->province_name ? ', '.$defaultAddr->province_name : '')) }}</span>
                            </div>
                        </div>
                        <button type="button" onclick="openAddressModal()" class="btn-change">Change</button>
                    </div>
                    @else
                    <div class="addr-display" style="border-color:#fcd34d; background:#fffbeb;">
                        <div>
                            <div class="addr-name" id="display-name">{{ $user->name }}</div>
                            <div class="addr-meta">
                                <span id="display-phone">{{ $user->phone ?? '—' }}</span><br>
                                <span id="display-address" style="color:#d97706;">Belum ada alamat — klik Change untuk menambah.</span><br>
                                <span id="display-location"></span>
                            </div>
                        </div>
                        <button type="button" onclick="openAddressModal()" class="btn-change">Change</button>
                    </div>
                    @endif
                </div>

                {{-- ── 02 SHIPPING METHOD ── --}}
                <div class="section fade-up delay-3">
                    <div class="section-label">Shipping Method</div>
                    <div id="courier_list">
                        <div class="courier-empty">Select location to calculate shipping</div>
                    </div>
                </div>

                {{-- ── 03 PAYMENT METHOD ── --}}
                <div class="section fade-up delay-4">
                    <div class="section-label">Payment Method</div>
                    <div style="background: var(--white); border: 1px solid var(--light-gray); border-radius: 16px; padding: 24px; display: flex; flex-direction: column; gap: 16px;">
                        <div style="display: flex; align-items: center; gap: 14px;">
                            <div style="width: 42px; height: 42px; background: rgba(47, 53, 38, 0.08); border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; color: var(--primary);">
                                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                            </div>
                            <div>
                                <h4 style="font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; color: var(--black); margin-bottom: 2px;">Midtrans Secure Payment Gateway</h4>
                                <p style="font-size: 10px; color: var(--olive-tint); line-height: 1.5;">Anda dapat memilih berbagai metode pembayaran setelah menekan tombol pesanan.</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>{{-- /LEFT --}}

            {{-- ── SIDEBAR ── --}}
            <aside class="sidebar fade-up delay-3">
                <div class="sidebar-card">
                    <div class="sidebar-title">Your Order</div>

                    <div class="custom-scrollbar" style="max-height:260px; overflow-y:auto; margin-bottom:24px; padding-right:4px;">
                        @foreach($cart as $id => $item)
                        <div class="cart-item">
                            <img src="{{ asset('storage/' . $item['image']) }}" class="cart-img" alt="{{ $item['name'] }}">
                            <div class="cart-info">
                                <div class="cart-name">{{ $item['name'] }}</div>
                                <div class="cart-variant">{{ $item['size'] }} / {{ $item['color'] }} · Qty {{ $item['quantity'] }}</div>
                                <div class="cart-price">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="totals-divider"></div>

                    <div class="totals-row" style="margin-top:16px;">
                        <span>Subtotal</span>
                        <span>Rp {{ number_format($totalAmount, 0, ',', '.') }}</span>
                    </div>
                    <div class="totals-row">
                        <span>Shipping</span>
                        <span id="shipping_cost_display" style="font-style:italic; color:rgba(255,255,255,.35); font-size:10px;">Select method</span>
                    </div>

                    <div class="totals-divider"></div>

                    <div class="totals-grand">
                        <span class="totals-grand-label">Total</span>
                        <span class="totals-grand-val" id="grand_total_display">Rp {{ number_format($totalAmount, 0, ',', '.') }}</span>
                    </div>

                    <button type="submit" id="btn-place-order" disabled class="btn-submit">
                        Complete Purchase
                    </button>
                    <p class="btn-notice">By proceeding, you agree to our Terms of Service and Privacy Policy.</p>
                </div>
            </aside>

        </div>
    </form>
</main>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function () {

    const subtotal     = {{ $totalAmount }};
    const CSRF         = '{{ csrf_token() }}';
    const isProduction = {{ app()->isProduction() ? 'true' : 'false' }};
    const jneLogoUrl   = '{{ Storage::url("jne logo.png") }}';
    const excludedServices = ['JTR250', 'JTR<150', 'JTR<130', 'PELIKAN', 'POPBOX', 'CTCSPS', 'SPS'];
    const serviceLabels = {
        'REG':    { label: 'Reguler',          desc: 'Estimasi 2–3 hari kerja' },
        'YES':    { label: 'YES (Esok Sampai)', desc: 'Estimasi 1 hari kerja' },
        'OKE':    { label: 'OKE (Ekonomis)',    desc: 'Estimasi 3–5 hari kerja' },
        'CTC':    { label: 'City Courier',      desc: 'Pengiriman dalam kota' },
        'CTCYES': { label: 'City Courier YES',  desc: 'Estimasi 1 hari kerja dalam kota' },
        'CTCOKE': { label: 'City Courier OKE',  desc: 'Ekonomis dalam kota' },
        'JTR':    { label: 'JNE Trucking',      desc: 'Estimasi 3–4 hari kerja' },
    };

    // ══════════════════════════════════════════════════════
    // SELECT2 — form destination
    // ══════════════════════════════════════════════════════
    function initSelect2() {
        $('#form_destination').select2({
            dropdownParent: $('#address-modal'),
            ajax: {
                url: "{{ route('api.locations') }}",
                dataType: 'json',
                delay: 250,
                data: p => ({ q: p.term }),
                processResults: data => ({
                    results: (data.results ?? []).map(i => ({
                        id:       i.id,
                        text:     i.text,
                        city:     i.city,
                        zip_code: i.zip_code
                    }))
                }),
                cache: true
            },
            placeholder: 'Cari kota atau kecamatan...',
            minimumInputLength: 3
        });
    }
    initSelect2();

    // ══════════════════════════════════════════════════════
    // MODAL — open / close
    // ══════════════════════════════════════════════════════
    window.openAddressModal = function () {
        showListPanel();
        $('#address-modal').addClass('active');
        document.body.style.overflow = 'hidden';
    };
    window.closeAddressModal = function () {
        $('#address-modal').removeClass('active');
        document.body.style.overflow = '';
    };
    $('#address-modal').on('click', function (e) {
        if ($(e.target).is('#address-modal')) closeAddressModal();
    });

    // ══════════════════════════════════════════════════════
    // PANEL SWITCHING
    // ══════════════════════════════════════════════════════
    window.showListPanel = function () {
        $('#panel-list').css('display', 'flex');
        $('#panel-form').css('display', 'none');
        $('#modal-title').text('Select Delivery Address');
        $('#modal-back-btn').css('display', 'none');
    };

    window.openNewPanel = function () {
        // Reset form fields
        $('#form_address_id').val('');
        $('#form_name, #form_phone, #form_label, #form_address, #form_zip').val('');
        $('#form_is_default').prop('checked', false);
        // Reset select2
        $('#form_destination').val(null).trigger('change');
        hideFormError();

        $('#modal-title').text('Add New Address');
        $('#modal-back-btn').css('display', 'flex');
        $('#panel-list').css('display', 'none');
        $('#panel-form').css('display', 'flex');
        $('#btn-save-addr').text('Simpan Alamat');
    };

    window.openEditPanel = async function (id) {
        // Show panel first (optimistic) with loading state
        $('#form_address_id').val(id);
        $('#form_name, #form_phone, #form_label, #form_address, #form_zip').val('');
        $('#form_is_default').prop('checked', false);
        $('#form_destination').val(null).trigger('change');
        hideFormError();

        $('#modal-title').text('Edit Alamat');
        $('#modal-back-btn').css('display', 'flex');
        $('#panel-list').css('display', 'none');
        $('#panel-form').css('display', 'flex');
        $('#btn-save-addr').text('Simpan Perubahan');

        try {
            const res  = await fetch(`/profile/addresses/${id}/edit`, {
                headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
            });
            if (!res.ok) throw new Error('Gagal memuat data.');
            const addr = await res.json();

            $('#form_name').val(addr.recipient_name ?? '');
            $('#form_phone').val(addr.phone ?? '');
            $('#form_label').val(addr.address_label ?? '');
            $('#form_address').val(addr.address ?? '');
            $('#form_zip').val(addr.zip_code ?? addr.postal_code ?? '');
            $('#form_is_default').prop('checked', !!addr.is_default);

            // Pre-fill select2 with existing city
            if (addr.destination_id && addr.city_name) {
                const opt = new Option(addr.city_name, addr.destination_id, true, true);
                $('#form_destination').append(opt).trigger('change');
            }
        } catch (e) {
            showFormError('Gagal memuat data alamat. Coba lagi.');
        }
    };

    // ══════════════════════════════════════════════════════
    // SAVE — handles both add & edit
    // ══════════════════════════════════════════════════════
    $('#btn-save-addr').on('click', async function () {
        const id       = $('#form_address_id').val();
        const isEdit   = id !== '';
        const name     = $('#form_name').val().trim();
        const phone    = $('#form_phone').val().trim();
        const address  = $('#form_address').val().trim();
        const destData = $('#form_destination').select2('data')[0];
        const label    = $('#form_label').val().trim();
        const zip      = $('#form_zip').val().trim();
        const isDef    = $('#form_is_default').is(':checked');

        // Client-side validation
        if (!name || !phone || !address || !destData || !destData.id) {
            showFormError('Nama penerima, HP, kota/kecamatan, dan alamat wajib diisi.');
            return;
        }

        const cityName = destData.city || destData.text.split(',').pop()?.trim() || destData.text;
        const btn      = $('#btn-save-addr').text('Menyimpan...').prop('disabled', true);
        hideFormError();

        const url    = isEdit ? `/profile/addresses/${id}` : '{{ route("address.store") }}';
        const method = isEdit ? 'PUT' : 'POST';

        const body = new URLSearchParams({
            _token:          CSRF,
            recipient_name:  name,
            phone:           phone,
            address:         address,
            destination_id:  destData.id,
            city_name:       cityName,
            address_label:   label,
            zip_code:        zip,
            postal_code:     zip,
            is_default:      isDef ? '1' : '0',
        });
        if (!isEdit) body.append('from_checkout', '1');

        try {
            const res  = await fetch(url, {
                method,
                body,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept':           'application/json'
                }
            });
            const data = await res.json();

            if (!res.ok) {
                // Laravel validation errors come as { errors: { field: ['msg'] } }
                const msg = data.message
                    ?? (data.errors ? Object.values(data.errors).flat().join(' ') : null)
                    ?? 'Terjadi kesalahan.';
                showFormError(msg);
                return;
            }

            // Reload to sync server state cleanly
            window.location.reload();

        } catch (e) {
            showFormError('Koneksi gagal. Coba lagi.');
        } finally {
            btn.text(isEdit ? 'Simpan Perubahan' : 'Simpan Alamat').prop('disabled', false);
        }
    });

    // ══════════════════════════════════════════════════════
    // SELECT address (click card)
    // ══════════════════════════════════════════════════════
    window.selectAddress = function (id, e) {
        // Ignore clicks that bubble up from action buttons
        if ($(e.target).closest('.addr-action-btns').length) return;

        const card = $(`#addr-item-${id}`);
        const d    = card.data();

        // Update UI
        $('.addr-item').removeClass('addr-item-selected');
        $('.addr-dot').css('opacity', 0);
        $('.addr-ring').css('border-color', '');
        card.addClass('addr-item-selected');
        card.find('.addr-dot').css('opacity', 1);
        card.find('.addr-ring').css('border-color', 'var(--primary)');

        // Update hidden form fields
        $('#hidden_receiver_name').val(d.name);
        $('#hidden_receiver_phone').val(d.phone);
        $('#hidden_receiver_address').val(d.address);
        $('#hidden_destination_id').val(d.destination || '');
        $('#hidden_receiver_city').val(d.city || '');
        $('#hidden_receiver_zip').val(d.zip || '');

        // Update display
        $('#display-name').text(d.name);
        $('#display-phone').text(d.phone);
        $('#display-address').text(d.address);
        $('#display-location').text(d.label || d.city);

        // Persist as default on server (fire-and-forget)
        fetch(`/profile/addresses/${id}/select`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `_token=${CSRF}`
        });

        if (d.destination) {
            fetchShippingRates(d.destination);
        } else {
            $('#courier_list').html(`
                <div style="background:#fffbeb;border:1.5px solid #fcd34d;border-radius:14px;padding:20px;text-align:center;">
                    <p style="font-size:11px;color:#d97706;">Alamat ini belum punya kode lokasi JNE.</p>
                    <p style="font-size:10px;color:#b45309;margin-top:4px;">Pilih alamat lain atau tambah alamat baru.</p>
                </div>`);
        }

        setTimeout(() => closeAddressModal(), 220);
    };

    // ══════════════════════════════════════════════════════
    // DELETE address
    // ══════════════════════════════════════════════════════
    window.deleteAddress = async function (id, btn) {
        if (!confirm('Hapus alamat ini? Tindakan tidak bisa dibatalkan.')) return;

        $(btn).text('Menghapus...').prop('disabled', true);

        try {
           const res = await fetch(`/profile/addresses/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': CSRF,
                'Content-Type': 'application/x-www-form-urlencoded',
                'Accept':       'application/json',   // ← tambah ini
                'X-Requested-With': 'XMLHttpRequest'  // ← tambah ini
            },
            body: `_token=${CSRF}`
        });

            if (res.ok) {
                // Remove card from DOM with animation
                const card = $(`#addr-item-${id}`);
                card.css({ transition: 'opacity .25s, transform .25s', opacity: 0, transform: 'scale(.97)' });
                setTimeout(() => {
                    card.remove();
                    // If no more cards, show empty state
                    if ($('.addr-item').length === 0) {
                        $('.modal-body').first().html(`
                            <div style="padding:40px;text-align:center;font-size:11px;color:var(--olive-tint);">Belum ada alamat tersimpan.</div>
                        `);
                        // Reset main display
                        $('#display-name').text('{{ $user->name }}');
                        $('#display-phone').text('—');
                        $('#display-address').text('Belum ada alamat').css('color', '#d97706');
                        $('#display-location').text('');
                        $('#hidden_destination_id, #hidden_receiver_name, #hidden_receiver_phone, #hidden_receiver_address').val('');
                    }
                    // If deleted card was selected default, reload to get new default
                    window.location.reload();
                }, 250);
            } else {
                $(btn).text('🗑 Hapus').prop('disabled', false);
                alert('Gagal menghapus alamat.');
            }
        } catch (e) {
            $(btn).text('🗑 Hapus').prop('disabled', false);
            alert('Koneksi gagal. Coba lagi.');
        }
    };

    // ══════════════════════════════════════════════════════
    // FORM ERROR helpers
    // ══════════════════════════════════════════════════════
    function showFormError(msg) {
        $('#form-error-box').text(msg).css('display', 'block');
    }
    function hideFormError() {
        $('#form-error-box').css('display', 'none').text('');
    }

    // ══════════════════════════════════════════════════════
    // SHIPPING
    // ══════════════════════════════════════════════════════
    const initDest = $('#hidden_destination_id').val();
    if (initDest) fetchShippingRates(initDest);

    function fetchShippingRates(destId) {
        if (!destId) return;

        $('#hidden_shipping_cost, #hidden_courier_name, #hidden_service_code').val('');
        $('#shipping_cost_display').text('Calculating...').css({ fontStyle: 'italic', color: 'rgba(255,255,255,.35)', fontSize: '10px' });
        $('#grand_total_display').text('Rp ' + subtotal.toLocaleString('id-ID'));
        checkFormValidity();

        $('#courier_list').html(`
            <div class="skeleton" style="height:68px;margin-bottom:8px;"></div>
            <div class="skeleton" style="height:68px;margin-bottom:8px;"></div>
            <div class="skeleton" style="height:68px;"></div>`);

        $.post("{{ route('api.shipping') }}", {
            _token: CSRF,
            destination_id: destId,
            weight: 1
        }, function (res) {
            if (res.success && res.pricing?.length > 0) {
                const rates = excludedServices.length > 0
                    ? res.pricing.filter(c => !excludedServices.some(ex => c.service_code.includes(ex)))
                    : res.pricing;

                if (rates.length > 0) {
                    let html = '';
                    rates.forEach(c => {
                        const key  = Object.keys(serviceLabels).find(k => c.service_code.startsWith(k));
                        const info = key ? serviceLabels[key] : null;
                        const name = info ? info.label : c.courier_service_name;
                        const desc = info ? info.desc  : (c.duration && c.duration !== 'null-null null' ? c.duration : 'Standard delivery');
                        html += `
                        <label style="cursor:pointer;display:block;">
                            <input type="radio" name="shipping_option" value="${c.price}"
                                data-courier="JNE ${c.courier_service_name}"
                                data-service-code="${c.service_code}"
                                class="hidden courier-radio">
                            <div class="option-card courier-card">
                                <div class="option-card-left">
                                    <div class="option-badge" style="border-radius:10px; background:#fff; border:1px solid var(--light-gray); overflow:hidden; padding:4px;">
                                        <img src="${jneLogoUrl}" alt="JNE" style="width:100%; height:100%; object-fit:contain;">
                                    </div>
                                    <div>
                                        <div class="option-title">${name}</div>
                                        <div class="option-desc">${desc}</div>
                                    </div>
                                </div>
                                <div style="display:flex;align-items:center;gap:12px;">
                                    <span class="option-price">Rp ${c.price.toLocaleString('id-ID')}</span>
                                    <div class="radio-ring"><div class="radio-fill"></div></div>
                                </div>
                            </div>
                        </label>`;
                    });
                    $('#courier_list').html(html);
                } else {
                    $('#courier_list').html('<div class="courier-empty" style="color:#f87171;border-color:#fca5a5;">Shipping unavailable for this area.</div>');
                }
            } else {
                $('#courier_list').html('<div class="courier-empty" style="color:#fb923c;border-color:#fed7aa;">Could not load shipping rates.</div>');
            }
            $('#shipping_cost_display').text('Select method');
        }).fail(() => {
            $('#courier_list').html('<div class="courier-empty" style="color:#f87171;border-color:#fca5a5;">Connection error. Please try again.</div>');
        });
    }

    // ── Pick courier ──────────────────────────────────────
    $(document).on('change', 'input[name="shipping_option"]', function () {
        const cost = parseInt($(this).val());
        $('#shipping_cost_display')
            .text('Rp ' + cost.toLocaleString('id-ID'))
            .css({ fontStyle: 'normal', color: 'rgba(255,255,255,.85)', fontSize: '11px' });
        $('#grand_total_display').text('Rp ' + (subtotal + cost).toLocaleString('id-ID'));
        $('#hidden_shipping_cost').val(cost);
        $('#hidden_courier_name').val($(this).data('courier'));
        $('#hidden_service_code').val($(this).data('service-code'));

        $('.courier-card').removeClass('selected');
        $('.courier-card .radio-fill').css('opacity', 0);
        $(this).closest('label').find('.courier-card').addClass('selected');
        $(this).closest('label').find('.radio-fill').css('opacity', 1);
        checkFormValidity();
    });

    // ── Validity check ────────────────────────────────────
    function checkFormValidity() {
        const ok = $('#hidden_destination_id').val() !== ''
                && $('#hidden_shipping_cost').val() !== ''
                && $('input[name="shipping_option"]:checked').length > 0;
        $('#btn-place-order').prop('disabled', !ok);
    }

});
</script>
</body>
</html>