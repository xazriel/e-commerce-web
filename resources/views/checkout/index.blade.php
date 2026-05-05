<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Checkout - Farhana</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap');
        body { font-family: 'Inter', sans-serif; }
        input:focus, textarea:focus, select:focus, button:focus { outline: none !important; box-shadow: none !important; }
        input:-webkit-autofill { -webkit-text-fill-color: #1a1a1a; -webkit-box-shadow: 0 0 0px 1000px #f7f7f7 inset; transition: background-color 5000s ease-in-out 0s; }
        .form-input-container { background-color: #f7f7f7; border: 1px solid #f0f0f0; border-radius: 8px; padding: 12px 16px; transition: all 0.3s ease; }
        .form-input-container:focus-within { border-color: #5A5A00; background-color: #fff; }
        .form-label-custom { font-size: 10px; text-transform: uppercase; letter-spacing: 0.05em; color: #9ca3af; margin-bottom: 4px; display: block; font-weight: 500; }
        .form-field-custom { width: 100%; background: transparent; border: none; padding: 0; font-size: 13px; color: #1a1a1a; outline: none; }
        .select2-container { width: 100% !important; }
        .select2-container--default .select2-selection--single { background-color: #f7f7f7; border: 1px solid #f0f0f0; border-radius: 8px; height: 60px; display: flex; align-items: center; transition: border-color 0.3s ease; }
        .select2-container--default.select2-container--focus .select2-selection--single { border-color: #5A5A00 !important; }
        .payment-radio:checked + .payment-card { border-color: #5A5A00; background-color: #fafff0; }
        .payment-radio:checked + .payment-card .radio-circle { border-color: #5A5A00; }
        .payment-radio:checked + .payment-card .radio-dot { opacity: 1; }
        .courier-radio:checked + .courier-card { border-color: #5A5A00 !important; background-color: #fafff0; }
        .custom-scrollbar::-webkit-scrollbar { width: 3px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f9f9f9; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e5e7eb; }
        @keyframes shimmer { 0% { background-position: -468px 0; } 100% { background-position: 468px 0; } }
        .skeleton { background: #f6f7f8; background-image: linear-gradient(to right, #f6f7f8 0%, #edeef1 20%, #f6f7f8 40%, #f6f7f8 100%); background-repeat: no-repeat; background-size: 800px 104px; animation: shimmer 1.5s infinite linear; }
        .modal-overlay { display: none; }
        .modal-overlay.active { display: flex; }
        .addr-item-selected { border-color: #5A5A00 !important; background-color: #fafff0; }
        .addr-item-selected .addr-dot { opacity: 1 !important; }
        .addr-item-selected .addr-ring { border-color: #5A5A00 !important; }
    </style>
</head>
<body class="bg-[#FCFCFA] text-[#1a1a1a] antialiased">

@if(session('error'))
<div class="fixed top-4 left-1/2 -translate-x-1/2 z-[9999] bg-red-50 border border-red-200 text-red-700 text-[11px] px-6 py-3 rounded-xl shadow-sm">
    {{ session('error') }}
</div>
@endif

{{-- ══════════════════════════════════════════════════════
     MODAL: SELECT / ADD ADDRESS
══════════════════════════════════════════════════════ --}}
<div id="address-modal" class="modal-overlay fixed inset-0 z-[200] bg-black/50 items-center justify-center p-4">
    <div class="bg-white w-full max-w-lg rounded-2xl shadow-2xl overflow-hidden max-h-[90vh] flex flex-col">

        {{-- Modal Header --}}
        <div class="flex justify-between items-center px-6 py-5 border-b border-gray-50 flex-shrink-0">
            <h3 id="modal-title" class="text-[13px] font-bold uppercase tracking-widest">Select Delivery Address</h3>
            <button type="button" onclick="closeAddressModal()" class="text-gray-400 hover:text-black transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Panel 1: Daftar Alamat --}}
        <div id="panel-list" class="flex flex-col flex-1 overflow-hidden">
            <div class="flex-1 overflow-y-auto p-6 space-y-3 custom-scrollbar">
                @forelse($addresses as $addr)
                <div class="addr-item border border-gray-100 rounded-xl p-5 cursor-pointer hover:border-[#5A5A00] transition-all {{ $addr->is_default ? 'addr-item-selected' : '' }}"
                    data-id="{{ $addr->id }}"
                    data-name="{{ $addr->recipient_name }}"
                    data-phone="{{ $addr->phone }}"
                    data-address="{{ $addr->address }}"
                    data-destination="{{ $addr->destination_id }}"
                    data-city="{{ $addr->city_name }}"
                    data-zip="{{ $addr->zip_code ?? $addr->postal_code }}"
                    data-label="{{ $addr->address_label ?? ($addr->city_name . ($addr->province_name ? ', '.$addr->province_name : '')) }}">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="font-bold text-[12px]">{{ $addr->recipient_name }}</span>
                                @if($addr->is_default)
                                <span class="text-[9px] bg-[#5A5A00] text-white px-2 py-0.5 rounded-full">Default</span>
                                @endif
                            </div>
                            <p class="text-[11px] text-gray-500 mt-0.5">{{ $addr->phone }}</p>
                            <p class="text-[11px] text-gray-500 mt-0.5 truncate">{{ $addr->address }}</p>
                            <p class="text-[11px] text-gray-400 mt-0.5">
                                {{ $addr->city_name }}{{ $addr->province_name ? ', '.$addr->province_name : '' }}
                            </p>
                            @if(!$addr->destination_id)
                            <p class="text-[10px] text-amber-500 mt-1">⚠ Belum punya kode JNE — perlu dipilih ulang lokasinya.</p>
                            @endif
                        </div>
                        <div class="addr-ring w-5 h-5 rounded-full border-2 border-gray-200 flex items-center justify-center flex-shrink-0 mt-0.5 transition-all {{ $addr->is_default ? 'border-[#5A5A00]' : '' }}">
                            <div class="addr-dot w-2.5 h-2.5 rounded-full bg-[#5A5A00] transition-opacity {{ $addr->is_default ? 'opacity-100' : 'opacity-0' }}"></div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="py-10 text-center">
                    <p class="text-[11px] text-gray-400">Belum ada alamat tersimpan.</p>
                </div>
                @endforelse
            </div>
            <div class="p-4 border-t border-gray-50 flex-shrink-0">
                <button type="button" onclick="showNewAddressPanel()"
                    class="w-full border-2 border-dashed border-gray-200 text-gray-400 text-[11px] py-3 rounded-xl hover:border-[#5A5A00] hover:text-[#5A5A00] transition-all uppercase tracking-widest">
                    + Add New Address
                </button>
            </div>
        </div>

        {{-- Panel 2: Form Alamat Baru --}}
        <div id="panel-new" class="hidden flex-col flex-1 overflow-hidden">
            <div class="flex-1 overflow-y-auto p-6 space-y-4 custom-scrollbar">
                <div class="grid grid-cols-2 gap-4">
                    <div class="form-input-container">
                        <label class="form-label-custom">Full Name</label>
                        <input type="text" id="new_name" class="form-field-custom" placeholder="Nama penerima">
                    </div>
                    <div class="form-input-container">
                        <label class="form-label-custom">Phone</label>
                        <input type="text" id="new_phone" class="form-field-custom" placeholder="08xxxxxxxxxx">
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="form-label-custom ml-1">Sub-district, District, City</label>
                    <select id="new_destination" class="w-full">
                        <option value="">Search location...</option>
                    </select>
                </div>
                <div class="form-input-container !h-auto">
                    <label class="form-label-custom">Detailed Address</label>
                    <textarea id="new_address" rows="3" class="form-field-custom resize-none" placeholder="Jalan, nomor rumah, RT/RW, dll."></textarea>
                </div>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" id="new_is_default" class="accent-[#5A5A00] w-4 h-4">
                    <span class="text-[11px] text-gray-600">Set as default address</span>
                </label>
            </div>
            <div class="p-4 border-t border-gray-50 flex gap-3 flex-shrink-0">
                <button type="button" onclick="showListPanel()"
                    class="flex-1 py-3 border border-gray-200 rounded-xl text-[11px] uppercase tracking-widest hover:bg-gray-50 transition text-gray-600">
                    ← Back
                </button>
                <button type="button" id="btn-save-new-addr"
                    class="flex-1 py-3 bg-[#5A5A00] text-white rounded-xl text-[11px] uppercase tracking-widest hover:bg-black transition font-bold">
                    Save Address
                </button>
            </div>
        </div>

    </div>
</div>

{{-- ══════════════════════════════════════════════════════
     HEADER
══════════════════════════════════════════════════════ --}}
<header class="py-6 px-8 flex justify-between items-center bg-white border-b border-gray-50 sticky top-0 z-50">
    <a href="{{ route('cart.index') }}" class="text-[10px] uppercase tracking-widest flex items-center gap-2 hover:opacity-60 transition">
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to Bag
    </a>
    <div class="text-[11px] font-bold tracking-[0.5em] uppercase">Farhana</div>
    <div class="w-10"></div>
</header>

<main class="max-w-6xl mx-auto px-6 py-16">
    <form action="{{ route('checkout.store') }}" method="POST" id="checkout-form">
        @csrf

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

        <div class="flex flex-col lg:flex-row gap-16 items-start">
            <div class="w-full lg:flex-1 space-y-12">

                {{-- ── SECTION 1: SHIPPING ADDRESS ── --}}
                <section>
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-bold text-[#1a1a1a]">Shipping Address</h2>
                        <button type="button" onclick="openAddressModal()"
                            class="text-[10px] uppercase tracking-widest text-[#5A5A00] hover:text-black transition flex items-center gap-1 font-medium">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                            </svg>
                            Change
                        </button>
                    </div>

                    @if($defaultAddr)
                    <div class="bg-white border border-gray-100 rounded-xl p-5 space-y-0.5">
                        <p id="display-name" class="font-bold text-[13px]">{{ $defaultAddr->recipient_name }}</p>
                        <p id="display-phone" class="text-[11px] text-gray-500">{{ $defaultAddr->phone }}</p>
                        <p id="display-address" class="text-[11px] text-gray-500 mt-1">{{ $defaultAddr->address }}</p>
                        <p id="display-location" class="text-[11px] text-gray-400">
                            {{ $defaultAddr->address_label ?? ($defaultAddr->city_name . ($defaultAddr->province_name ? ', '.$defaultAddr->province_name : '')) }}
                        </p>
                    </div>
                    @else
                    <div class="bg-white border border-amber-100 rounded-xl p-5">
                        <p class="text-[11px] text-amber-600 mb-4">Kamu belum punya alamat tersimpan. Klik <strong>Change</strong> untuk menambah alamat baru.</p>
                        <div class="space-y-1">
                            <p id="display-name" class="font-bold text-[13px]">{{ $user->name }}</p>
                            <p id="display-phone" class="text-[11px] text-gray-500">{{ $user->phone ?? '—' }}</p>
                            <p id="display-address" class="text-[11px] text-gray-400">Belum ada alamat</p>
                            <p id="display-location" class="text-[11px] text-gray-400"></p>
                        </div>
                    </div>
                    @endif
                </section>

                {{-- ── SECTION 2: SHIPPING METHOD ── --}}
                <section>
                    <h2 class="text-xl font-bold mb-6 text-[#1a1a1a]">Shipping Method</h2>
                    <div id="courier_list" class="space-y-3">
                        <div class="py-8 text-center border-2 border-dashed border-gray-100 rounded-xl">
                            <p class="text-[11px] text-gray-400 uppercase tracking-widest">Select location to calculate shipping</p>
                        </div>
                    </div>
                </section>

                {{-- ── SECTION 3: PAYMENT METHOD ── --}}
                <section>
                    <h2 class="text-xl font-bold mb-6 text-[#1a1a1a]">Payment Method</h2>
                    <div class="space-y-3">
                        @php
                        $payments = [
                            ['id' => 'bca_va',     'name' => 'BCA Virtual Account',     'desc' => 'Transfer via ATM, m-Banking, atau i-Banking BCA',     'icon' => 'BCA'],
                            ['id' => 'bni_va',     'name' => 'BNI Virtual Account',     'desc' => 'Transfer via ATM, m-Banking, atau i-Banking BNI',     'icon' => 'BNI'],
                            ['id' => 'bri_va',     'name' => 'BRI Virtual Account',     'desc' => 'Transfer via ATM, m-Banking, atau i-Banking BRI',     'icon' => 'BRI'],
                            ['id' => 'mandiri_va', 'name' => 'Mandiri Virtual Account', 'desc' => 'Transfer via ATM, m-Banking, atau Livin Mandiri',     'icon' => 'MND'],
                            ['id' => 'permata_va', 'name' => 'Permata Virtual Account', 'desc' => 'Transfer via ATM atau m-Banking Permata',             'icon' => 'PRM'],
                            ['id' => 'gopay',      'name' => 'GoPay',                   'desc' => 'Bayar langsung lewat aplikasi Gojek atau GoPay',      'icon' => 'GP'],
                            ['id' => 'shopeepay',  'name' => 'ShopeePay',               'desc' => 'Bayar lewat aplikasi Shopee',                         'icon' => 'SP'],
                            ['id' => 'qris',       'name' => 'QRIS',                    'desc' => 'Scan QR dari aplikasi e-wallet atau m-banking apapun','icon' => 'QR'],
                        ];
                        @endphp
                        @foreach($payments as $pay)
                        <label class="cursor-pointer">
                            <input type="radio" name="payment_method" value="{{ $pay['id'] }}" class="hidden payment-radio" required>
                            <div class="payment-card flex items-center justify-between border border-gray-100 p-5 rounded-xl bg-white transition-all hover:border-gray-200 hover:shadow-sm">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-lg bg-gray-50 border border-gray-100 flex items-center justify-center flex-shrink-0">
                                        <span class="text-[9px] font-bold text-gray-500">{{ $pay['icon'] }}</span>
                                    </div>
                                    <div>
                                        <div class="font-bold text-[11px] uppercase tracking-wider text-gray-800">{{ $pay['name'] }}</div>
                                        <div class="text-[10px] text-gray-400 mt-0.5">{{ $pay['desc'] }}</div>
                                    </div>
                                </div>
                                <div class="radio-circle w-5 h-5 rounded-full border border-gray-200 flex items-center justify-center transition-all flex-shrink-0">
                                    <div class="radio-dot w-2.5 h-2.5 rounded-full bg-[#5A5A00] opacity-0 transition-opacity"></div>
                                </div>
                            </div>
                        </label>
                        @endforeach
                    </div>
                </section>

            </div>

            {{-- ── ORDER SUMMARY SIDEBAR ── --}}
            <aside class="w-full lg:w-[400px] sticky top-28">
                <div class="bg-white border border-gray-100 p-8 shadow-[0_20px_40px_rgba(0,0,0,0.02)] rounded-2xl">
                    <h3 class="text-[10px] uppercase tracking-[0.2em] font-bold text-gray-400 mb-6">Your Order</h3>
                    <div class="space-y-5 mb-8 max-h-[300px] overflow-y-auto pr-2 custom-scrollbar">
                        @foreach($cart as $id => $item)
                        <div class="flex gap-4">
                            <div class="w-14 flex-shrink-0 overflow-hidden rounded-lg border border-gray-50" style="height:72px">
                                <img src="{{ asset('storage/' . $item['image']) }}" class="w-full h-full object-cover">
                            </div>
                            <div class="flex-grow py-0.5">
                                <h4 class="text-[11px] font-bold uppercase tracking-wider text-gray-800 line-clamp-1">{{ $item['name'] }}</h4>
                                <p class="text-[10px] text-gray-400 mt-0.5">{{ $item['size'] }} / {{ $item['color'] }} · Qty {{ $item['quantity'] }}</p>
                                <div class="flex justify-end mt-1">
                                    <span class="text-[11px] font-bold">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="space-y-3 pt-6 border-t border-gray-50 text-[13px]">
                        <div class="flex justify-between items-center text-gray-500">
                            <span>Subtotal</span>
                            <span>Rp {{ number_format($totalAmount, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center text-gray-500">
                            <span>Shipping Fee</span>
                            <span id="shipping_cost_display" class="font-medium text-gray-400 italic text-[11px]">Select shipping first</span>
                        </div>
                        <div class="flex justify-between items-center pt-4 border-t border-gray-50">
                            <span class="font-bold uppercase text-[11px] tracking-wider">Total</span>
                            <span id="grand_total_display" class="text-xl font-bold">Rp {{ number_format($totalAmount, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    <div class="mt-8">
                        <button type="submit" id="btn-place-order" disabled
                            class="w-full bg-[#5A5A00] text-white py-4 rounded-xl text-[12px] font-bold uppercase tracking-widest transition-all opacity-40 cursor-not-allowed">
                            Complete Purchase
                        </button>
                        <p class="text-[9px] text-center text-gray-400 mt-3 px-4 leading-relaxed">
                            By clicking, you agree to our Terms of Service and Privacy Policy.
                        </p>
                    </div>
                </div>
            </aside>
        </div>
    </form>
</main>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function () {
    const subtotal      = {{ $totalAmount }};
    const isProduction  = {{ app()->isProduction() ? 'true' : 'false' }};
    const excludedServices = isProduction
        ? ['JTR250', 'JTR<150', 'JTR<130', 'PELIKAN', 'POPBOX', 'CTCSPS', 'SPS']
        : [];
    const serviceLabels = {
        'REG':    { label: 'Reguler',               desc: 'Estimasi 2-3 hari kerja' },
        'YES':    { label: 'YES (Esok Sampai)',      desc: 'Estimasi 1 hari kerja' },
        'OKE':    { label: 'OKE (Ekonomis)',         desc: 'Estimasi 3-5 hari kerja' },
        'CTC':    { label: 'City Courier',           desc: 'Pengiriman dalam kota' },
        'CTCYES': { label: 'City Courier YES',       desc: 'Estimasi 1 hari kerja dalam kota' },
        'CTCOKE': { label: 'City Courier OKE',       desc: 'Ekonomis dalam kota' },
        'JTR':    { label: 'JNE Trucking',           desc: 'Estimasi 3-4 hari kerja' },
    };

    // ── Init Select2 modal ────────────────────────────────
    function makeSelect2(selector, parentSelector) {
        const opts = {
            ajax: {
                url: "{{ route('api.locations') }}",
                dataType: 'json', delay: 250,
                data: p => ({ q: p.term }),
                processResults: data => ({
                    results: data.results.map(i => ({
                        id: i.id, text: i.text, city: i.city, zip_code: i.zip_code
                    }))
                }),
                cache: true
            },
            placeholder: "Type sub-district or city...",
            minimumInputLength: 3
        };
        if (parentSelector) opts.dropdownParent = $(parentSelector);
        $(selector).select2(opts);
    }
    makeSelect2('#new_destination', '#address-modal');

    // ── Modal open/close ──────────────────────────────────
    window.openAddressModal = function () {
        showListPanel();
        $('#address-modal').addClass('active');
    };
    window.closeAddressModal = function () {
        $('#address-modal').removeClass('active');
    };
    $('#address-modal').on('click', function (e) {
        if ($(e.target).is('#address-modal')) closeAddressModal();
    });

    // ── Panel toggle ──────────────────────────────────────
    window.showListPanel = function () {
        $('#panel-list').removeClass('hidden').addClass('flex');
        $('#panel-new').addClass('hidden').removeClass('flex');
        $('#modal-title').text('Select Delivery Address');
    };
    window.showNewAddressPanel = function () {
        $('#panel-list').addClass('hidden').removeClass('flex');
        $('#panel-new').removeClass('hidden').addClass('flex');
        $('#modal-title').text('Add New Address');
        $('#new_name, #new_phone, #new_address').val('');
        $('#new_is_default').prop('checked', false);
        if ($('#new_destination').data('select2')) {
            $('#new_destination').val(null).trigger('change');
        }
    };

    // ── Pilih alamat dari list ────────────────────────────
    $(document).on('click', '.addr-item', function () {
        const d = $(this).data();
        $('.addr-item').removeClass('addr-item-selected');
        $('.addr-dot').addClass('opacity-0');
        $('.addr-ring').removeClass('border-[#5A5A00]').addClass('border-gray-200');
        $(this).addClass('addr-item-selected');
        $(this).find('.addr-dot').removeClass('opacity-0');
        $(this).find('.addr-ring').addClass('border-[#5A5A00]').removeClass('border-gray-200');

        $('#hidden_receiver_name').val(d.name);
        $('#hidden_receiver_phone').val(d.phone);
        $('#hidden_receiver_address').val(d.address);
        $('#hidden_destination_id').val(d.destination || '');
        $('#hidden_receiver_city').val(d.city || '');
        $('#hidden_receiver_zip').val(d.zip || '');

        $('#display-name').text(d.name);
        $('#display-phone').text(d.phone);
        $('#display-address').text(d.address);
        $('#display-location').text(d.label || d.city);

        if (d.destination) {
            fetchShippingRates(d.destination);
        } else {
            $('#courier_list').html(`
                <div class="py-6 bg-amber-50 border border-amber-100 rounded-xl text-center">
                    <p class="text-[11px] text-amber-600">Alamat ini belum punya kode lokasi JNE.</p>
                    <p class="text-[10px] text-amber-500 mt-1">Pilih alamat lain atau tambah alamat baru.</p>
                </div>`);
        }

        setTimeout(() => closeAddressModal(), 250);
    });

    // ── Simpan alamat baru ────────────────────────────────
    $('#btn-save-new-addr').on('click', function () {
        const name   = $('#new_name').val().trim();
        const phone  = $('#new_phone').val().trim();
        const detail = $('#new_address').val().trim();
        const dest   = $('#new_destination').select2('data')[0];

        if (!name || !phone || !detail || !dest) {
            alert('Mohon lengkapi semua field alamat.');
            return;
        }

        const btn = $(this).text('Saving...').prop('disabled', true);

        $.ajax({
            url:  "{{ route('address.store') }}",
            type: 'POST',
            data: {
                _token:         "{{ csrf_token() }}",
                recipient_name: name,
                phone:          phone,
                address:        detail,
                destination_id: dest.id,
                address_label:  dest.text,
                city_name:      dest.city || '',
                zip_code:       dest.zip_code || '',
                postal_code:    dest.zip_code || '',
                is_default:     $('#new_is_default').is(':checked') ? 1 : 0,
                from_checkout:  1,
            },
            success: function () {
                window.location.reload();
            },
            error: function (xhr) {
                btn.text('Save Address').prop('disabled', false);
                const errors = xhr.responseJSON?.errors;
                alert(errors ? Object.values(errors).flat().join('\n') : 'Gagal menyimpan alamat.');
            }
        });
    });

    // ── Load ongkir awal ──────────────────────────────────
    const initDest = $('#hidden_destination_id').val();
    if (initDest) fetchShippingRates(initDest);

    // ── Fetch ongkir ──────────────────────────────────────
    function fetchShippingRates(destId) {
        if (!destId) return;

        $('#hidden_shipping_cost, #hidden_courier_name, #hidden_service_code').val('');
        $('#shipping_cost_display').text('Calculating...').addClass('italic text-gray-400').removeClass('text-gray-700');
        $('#grand_total_display').text('Rp ' + subtotal.toLocaleString('id-ID'));
        checkFormValidity();

        $('#courier_list').html(`
            <div class="skeleton h-20 w-full rounded-xl mb-3"></div>
            <div class="skeleton h-20 w-full rounded-xl mb-3"></div>
            <div class="skeleton h-20 w-full rounded-xl"></div>`);

        $.post("{{ route('api.shipping') }}", {
            _token: "{{ csrf_token() }}",
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
                        const desc = info ? info.desc : (c.duration && c.duration !== 'null-null null' ? c.duration : 'Standard delivery');
                        html += `
                        <label class="cursor-pointer">
                            <input type="radio" name="shipping_option" value="${c.price}"
                                data-courier="JNE ${c.courier_service_name}"
                                data-service-code="${c.service_code}"
                                class="hidden courier-radio">
                            <div class="courier-card flex items-center justify-between border border-gray-100 p-5 rounded-xl bg-white transition-all hover:border-gray-200 hover:shadow-sm">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-lg bg-[#f7f7f7] border border-gray-100 flex items-center justify-center flex-shrink-0">
                                        <span class="text-[9px] font-bold text-[#5A5A00]">JNE</span>
                                    </div>
                                    <div>
                                        <div class="font-bold text-[11px] uppercase tracking-wider text-gray-800">${name}</div>
                                        <div class="text-[10px] text-gray-400 mt-0.5">${desc}</div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="text-[13px] font-bold">Rp ${c.price.toLocaleString('id-ID')}</span>
                                    <div class="radio-circle w-5 h-5 rounded-full border border-gray-200 flex items-center justify-center transition-all flex-shrink-0">
                                        <div class="radio-dot w-2.5 h-2.5 rounded-full bg-[#5A5A00] opacity-0 transition-opacity"></div>
                                    </div>
                                </div>
                            </div>
                        </label>`;
                    });
                    $('#courier_list').html(html);
                } else {
                    $('#courier_list').html('<div class="py-8 text-center text-[11px] text-red-400 bg-red-50 rounded-xl">Shipping unavailable for this area.</div>');
                }
            } else {
                $('#courier_list').html('<div class="py-8 text-center text-[11px] text-orange-400 bg-orange-50 rounded-xl">Could not load shipping rates.</div>');
            }
            $('#shipping_cost_display').text('Select shipping first');
        }).fail(() => {
            $('#courier_list').html('<div class="py-8 text-center text-[11px] text-red-400 bg-red-50 rounded-xl">Connection error. Please try again.</div>');
        });
    }

    // ── Pilih kurir ───────────────────────────────────────
    $(document).on('change', 'input[name="shipping_option"]', function () {
        const cost = parseInt($(this).val());
        $('#shipping_cost_display').text('Rp ' + cost.toLocaleString('id-ID'))
            .removeClass('italic text-gray-400').addClass('text-gray-700');
        $('#grand_total_display').text('Rp ' + (subtotal + cost).toLocaleString('id-ID'));
        $('#hidden_shipping_cost').val(cost);
        $('#hidden_courier_name').val($(this).data('courier'));
        $('#hidden_service_code').val($(this).data('service-code'));
        $('.courier-card').removeClass('border-[#5A5A00] bg-[#fafff0]');
        $('.courier-card .radio-dot').addClass('opacity-0');
        $(this).closest('label').find('.courier-card').addClass('border-[#5A5A00] bg-[#fafff0]');
        $(this).closest('label').find('.radio-dot').removeClass('opacity-0');
        checkFormValidity();
    });

    // ── Pilih payment ─────────────────────────────────────
    $(document).on('change', 'input[name="payment_method"]', checkFormValidity);

    // ── Cek form valid ────────────────────────────────────
    function checkFormValidity() {
        const ok = $('#hidden_destination_id').val() !== ''
                && $('#hidden_shipping_cost').val() !== ''
                && $('input[name="shipping_option"]:checked').length > 0
                && $('input[name="payment_method"]:checked').length > 0;

        if (ok) {
            $('#btn-place-order').prop('disabled', false)
                .removeClass('opacity-40 cursor-not-allowed').addClass('opacity-100 hover:bg-black');
        } else {
            $('#btn-place-order').prop('disabled', true)
                .addClass('opacity-40 cursor-not-allowed').removeClass('opacity-100 hover:bg-black');
        }
    }
});
</script>
</body>
</html>