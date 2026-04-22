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

        /* MENGHILANGKAN OUTLINE BIRU DEFAULT */
        input:focus, textarea:focus, select:focus, button:focus {
            outline: none !important;
            box-shadow: none !important;
        }

        /* MENGHILANGKAN BG BIRU AUTOFILL BROWSER */
        input:-webkit-autofill {
            -webkit-text-fill-color: #1a1a1a;
            -webkit-box-shadow: 0 0 0px 1000px #f7f7f7 inset;
            transition: background-color 5000s ease-in-out 0s;
        }

        /* Custom Input Styling */
        .form-input-container {
            background-color: #f7f7f7;
            border: 1px solid #f0f0f0;
            border-radius: 8px;
            padding: 12px 16px;
            transition: all 0.3s ease;
        }
        .form-input-container:focus-within {
            border-color: #5A5A00;
            background-color: #fff;
        }

        .form-label-custom {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #9ca3af;
            margin-bottom: 4px;
            display: block;
            font-weight: 500;
        }
        .form-field-custom {
            width: 100%;
            background: transparent;
            border: none;
            padding: 0;
            font-size: 13px;
            color: #1a1a1a;
            outline: none;
        }

        /* Select2 Refinement */
        .select2-container--default .select2-selection--single {
            background-color: #f7f7f7;
            border: 1px solid #f0f0f0;
            border-radius: 8px;
            height: 65px; 
            display: flex;
            align-items: center;
            transition: border-color 0.3s ease;
        }
        .select2-container--default.select2-container--focus .select2-selection--single {
            border-color: #5A5A00 !important;
        }

        /* Payment Method Custom Radios */
        .payment-radio:checked + .payment-card {
            border-color: #5A5A00;
            background-color: #fff;
        }
        .payment-radio:checked + .payment-card .radio-circle {
            border-color: #5A5A00;
        }
        .payment-radio:checked + .payment-card .radio-dot {
            opacity: 1;
        }

        .custom-scrollbar::-webkit-scrollbar { width: 3px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f9f9f9; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e5e7eb; }
    </style>
</head>
<body class="bg-[#FCFCFA] text-[#1a1a1a] antialiased">

    <header class="py-6 px-8 flex justify-between items-center bg-white border-b border-gray-50 sticky top-0 z-50">
        <a href="{{ route('cart.index') }}" class="text-[10px] uppercase tracking-widest flex items-center gap-2 hover:opacity-60 transition font-sans">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to Bag
        </a>
        <div class="text-[11px] font-bold tracking-[0.5em] uppercase">Farhana</div>
        <div class="w-10"></div>
    </header>

    <main class="max-w-6xl mx-auto px-6 py-16">
        <form action="{{ route('checkout.store') }}" method="POST" id="checkout-form">
            @csrf
            <div class="flex flex-col lg:flex-row gap-16 items-start">
                
                <div class="w-full lg:flex-1 space-y-12">
                    <section>
                        <h2 class="text-xl font-bold mb-8 text-[#1a1a1a]">Address Details</h2>
                        <div class="space-y-4">
                            <div class="form-input-container">
                                <label class="form-label-custom">Email Address (Optional)</label>
                                <input type="email" name="email" value="{{ $user->email }}" placeholder="Enter your email" class="form-field-custom">
                            </div>
                            <div class="form-input-container">
                                <label class="form-label-custom">Recipient Full Name</label>
                                <input type="text" name="receiver_name" value="{{ $user->name }}" required class="form-field-custom">
                            </div>
                            <div class="form-input-container">
                                <label class="form-label-custom">Recipient Phone Number</label>
                                <input type="text" name="receiver_phone" value="{{ $user->phone }}" required class="form-field-custom">
                            </div>
                            <div class="space-y-2">
                                <label class="form-label-custom ml-1">Sub-district, District, City</label>
                                <select name="destination_id" id="destination_select" class="w-full" required>
                                    @if($user->destination_id && $user->address_label)
                                        <option value="{{ $user->destination_id }}" selected>{{ $user->address_label }}</option>
                                    @else
                                        <option value="">Search location...</option>
                                    @endif
                                </select>
                            </div>
                            <div class="form-input-container !h-auto">
                                <label class="form-label-custom">Address Details</label>
                                <textarea name="receiver_address" rows="4" required class="form-field-custom resize-none" placeholder="Street name, Building, House No.">{{ $user->address }}</textarea>
                            </div>
                        </div>
                    </section>

                    <section id="courier_section">
                        <h2 class="text-xl font-bold mb-8 text-[#1a1a1a]">Shipment Method</h2>
                        <div id="courier_list" class="grid grid-cols-1 gap-4">
                            <div class="py-4 text-[10px] text-gray-400 italic">Please select your location first to see shipping rates.</div>
                        </div>
                    </section>

                    <section id="payment_section">
                        <div class="flex items-center justify-between mb-8">
                            <h2 class="text-xl font-bold text-[#1a1a1a]">Payment Method</h2>
                            <span class="text-[10px] text-gray-400 uppercase tracking-widest">Virtual Account</span>
                        </div>
                        
                        <div class="grid grid-cols-1 gap-3">
                            <label class="cursor-pointer">
                                <input type="radio" name="payment_method" value="bca_va" class="hidden payment-radio" required>
                                <div class="payment-card flex items-center justify-between border border-gray-100 p-5 rounded-xl bg-white transition-all hover:border-gray-300">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-8 flex items-center">
                                            <img src="https://upload.wikimedia.org/wikipedia/commons/5/5c/Bank_Central_Asia.svg" class="w-full h-auto grayscale opacity-80" alt="BCA">
                                        </div>
                                        <div>
                                            <div class="font-bold text-[11px] uppercase tracking-wider">BCA Virtual Account</div>
                                            <div class="text-[9px] text-gray-400">Receive from all banks</div>
                                        </div>
                                    </div>
                                    <div class="radio-circle w-5 h-5 rounded-full border border-gray-200 flex items-center justify-center transition-all">
                                        <div class="radio-dot w-2.5 h-2.5 rounded-full bg-[#5A5A00] opacity-0 transition-opacity"></div>
                                    </div>
                                </div>
                            </label>

                            <label class="cursor-pointer">
                                <input type="radio" name="payment_method" value="mandiri_va" class="hidden payment-radio">
                                <div class="payment-card flex items-center justify-between border border-gray-100 p-5 rounded-xl bg-white transition-all hover:border-gray-300">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-8 flex items-center">
                                            <img src="https://upload.wikimedia.org/wikipedia/commons/a/ad/Bank_Mandiri_logo_2016.svg" class="w-full h-auto grayscale opacity-80" alt="Mandiri">
                                        </div>
                                        <div>
                                            <div class="font-bold text-[11px] uppercase tracking-wider">Mandiri Virtual Account</div>
                                            <div class="text-[9px] text-gray-400">Receive from Mandiri only</div>
                                        </div>
                                    </div>
                                    <div class="radio-circle w-5 h-5 rounded-full border border-gray-200 flex items-center justify-center transition-all">
                                        <div class="radio-dot w-2.5 h-2.5 rounded-full bg-[#5A5A00] opacity-0 transition-opacity"></div>
                                    </div>
                                </div>
                            </label>

                            <label class="cursor-pointer">
                                <input type="radio" name="payment_method" value="bni_va" class="hidden payment-radio">
                                <div class="payment-card flex items-center justify-between border border-gray-100 p-5 rounded-xl bg-white transition-all hover:border-gray-300">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-8 flex items-center">
                                            <img src="https://upload.wikimedia.org/wikipedia/id/5/55/BNI_logo.svg" class="w-full h-auto grayscale opacity-80" alt="BNI">
                                        </div>
                                        <div>
                                            <div class="font-bold text-[11px] uppercase tracking-wider">BNI Virtual Account</div>
                                            <div class="text-[9px] text-gray-400">Receive from all banks</div>
                                        </div>
                                    </div>
                                    <div class="radio-circle w-5 h-5 rounded-full border border-gray-200 flex items-center justify-center transition-all">
                                        <div class="radio-dot w-2.5 h-2.5 rounded-full bg-[#5A5A00] opacity-0 transition-opacity"></div>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </section>
                </div>

                <aside class="w-full lg:w-[420px] sticky top-28">
                    <div class="bg-white border border-gray-100 p-8 shadow-[0_20px_40px_rgba(0,0,0,0.02)] rounded-xl">
                        <div class="space-y-6 mb-10 max-h-[320px] overflow-y-auto pr-3 custom-scrollbar">
                            @foreach($cart as $id => $item)
                            <div class="flex gap-5">
                                <div class="w-20 h-24 bg-[#f9f9f7] flex-shrink-0 overflow-hidden rounded-md border border-gray-50">
                                    <img src="{{ asset('storage/' . $item['image']) }}" class="w-full h-full object-cover mix-blend-multiply">
                                </div>
                                <div class="flex-grow py-1">
                                    <h4 class="text-[11px] font-bold uppercase tracking-wider text-gray-800 line-clamp-1">{{ $item['name'] }}</h4>
                                    <p class="text-[10px] text-gray-400 mt-1">{{ $item['color'] }} • {{ $item['size'] }}</p>
                                    <p class="text-[10px] text-gray-400">Quantity: {{ $item['quantity'] }}</p>
                                    <div class="flex justify-end mt-2">
                                        <span class="text-[11px] font-bold">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div class="space-y-4 pt-8 border-t border-gray-50 text-[13px]">
                            <div class="flex justify-between items-center text-gray-500">
                                <span>Subtotal • {{ count($cart) }} items</span>
                                <span>Rp {{ number_format($totalAmount, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between items-center text-gray-500">
                                <span>Shipping</span>
                                <span id="shipping_cost_display">-</span>
                            </div>
                            <div class="flex justify-between items-center pt-5 border-t border-gray-50">
                                <span class="font-bold">Total Payment</span>
                                <span id="grand_total_display" class="text-lg font-bold">Rp {{ number_format($totalAmount, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <div class="mt-10">
                            <input type="hidden" name="shipping_cost" id="hidden_shipping_cost">
                            <input type="hidden" name="courier_name" id="hidden_courier_name">

                            <button type="submit" id="btn-place-order" disabled 
                                class="w-full bg-[#5A5A00] text-white py-4 rounded-full text-[12px] font-bold uppercase tracking-widest transition-all opacity-50 cursor-not-allowed">
                                Order Now
                            </button>
                        </div>
                    </div>
                </aside>
            </div>
        </form>
    </main>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            const subtotal = {{ $totalAmount }};

            $('#destination_select').select2({
                ajax: {
                    url: "{{ route('api.locations') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) { return { q: params.term }; },
                    processResults: function (data) { return { results: data.results }; },
                    cache: true
                },
                placeholder: "Search location...",
                minimumInputLength: 3
            });

            if($('#destination_select').val()){
                fetchShippingRates($('#destination_select').val());
            }

            function fetchShippingRates(destId) {
                if(!destId) return;
                $('#courier_list').html('<div class="py-4 text-[10px] text-gray-400 italic">Searching available shipment...</div>');
                $.post("{{ route('api.shipping') }}", {
                    _token: "{{ csrf_token() }}",
                    destination_id: destId
                }, function(res) {
                    if(res.success && res.pricing && res.pricing.length > 0) {
                        let html = '';
                        const jneRates = res.pricing.filter(c => c.courier_name.toLowerCase() === 'jne');
                        if(jneRates.length > 0) {
                            jneRates.forEach(function(c) {
                                html += `
                                <label class="group flex items-center justify-between border border-gray-100 p-6 rounded-xl cursor-pointer hover:border-[#5A5A00] transition-all bg-white">
                                    <div class="flex items-center gap-4">
                                        <div class="font-bold text-[11px] uppercase tracking-wider">
                                            ${c.courier_name} <span class="text-gray-400 ml-1 font-normal">${c.courier_service_name}</span>
                                            <div class="text-[9px] text-gray-400 font-normal normal-case mt-0.5">${c.duration || 'Estimasi tidak tersedia'}</div>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-4">
                                        <span class="text-[13px] font-bold">Rp ${c.price.toLocaleString('id-ID')}</span>
                                        <input type="radio" name="shipping_option" value="${c.price}" data-courier="${c.courier_name} ${c.courier_service_name}" class="w-4 h-4 accent-[#5A5A00] courier-radio">
                                    </div>
                                </label>`;
                            });
                            $('#courier_list').html(html);
                        } else {
                            $('#courier_list').html('<div class="py-4 text-[10px] text-red-400">JNE is not available for this area.</div>');
                        }
                    }
                });
            }

            function checkFormValidity() {
                const courierPicked = $('input[name="shipping_option"]:checked').length > 0;
                const paymentPicked = $('input[name="payment_method"]:checked').length > 0;
                if(courierPicked && paymentPicked) {
                    $('#btn-place-order').prop('disabled', false).removeClass('opacity-50 cursor-not-allowed').addClass('hover:bg-black');
                }
            }

            $('#destination_select').on('change', function() { fetchShippingRates($(this).val()); });

            $(document).on('change', 'input[name="shipping_option"]', function() {
                const cost = parseInt($(this).val());
                $('#shipping_cost_display').text('Rp ' + cost.toLocaleString('id-ID'));
                $('#grand_total_display').text('Rp ' + (subtotal + cost).toLocaleString('id-ID'));
                $('#hidden_shipping_cost').val(cost);
                $('#hidden_courier_name').val($(this).data('courier'));
                checkFormValidity();
            });

            $(document).on('change', 'input[name="payment_method"]', function() {
                checkFormValidity();
            });
        });
    </script>
</body>
</html>