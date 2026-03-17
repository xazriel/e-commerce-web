<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Checkout - Farhana</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container--default .select2-selection--single {
            border-radius: 0; border-color: #f3f4f6; height: 45px; line-height: 45px;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 45px; font-size: 12px; color: #4A4A4A;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow { height: 43px; }
    </style>
</head>
<body class="bg-[#FCFCFA] text-[#4A4A4A] antialiased">
    <header class="py-6 px-8 border-b border-gray-100 bg-white flex justify-between">
        <a href="{{ route('cart.index') }}" class="text-[10px] uppercase tracking-widest flex items-center gap-2">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to Cart
        </a>
    </header>

    <main class="max-w-6xl mx-auto px-6 py-16">
        @if(session('error'))
            <div class="mb-8 p-4 bg-red-50 text-red-500 text-[10px] uppercase tracking-widest border border-red-100">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('checkout.store') }}" method="POST" id="checkout-form">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-16">
                
                {{-- Kiri: Form Alamat --}}
                <div class="lg:col-span-2 space-y-12">
                    <div>
                        <h3 class="text-[11px] font-bold tracking-[0.3em] uppercase mb-8 border-b pb-4">Shipping Information</h3>
                        <div class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="text-[9px] uppercase tracking-widest text-gray-400 block mb-2">Recipient Name</label>
                                    <input type="text" name="receiver_name" value="{{ $user->name }}" required
                                        class="w-full border-gray-100 bg-white text-[12px] p-3 focus:ring-0 focus:border-[#8B864E]">
                                </div>
                                <div>
                                    <label class="text-[9px] uppercase tracking-widest text-gray-400 block mb-2">Phone Number</label>
                                    <input type="text" name="receiver_phone" value="{{ $user->phone }}" placeholder="0812..." required
                                        class="w-full border-gray-100 bg-white text-[12px] p-3 focus:ring-0 focus:border-[#8B864E]">
                                </div>
                            </div>

                            <div>
                                <label class="text-[9px] uppercase tracking-widest text-gray-400 block mb-2">Town / City (Kecamatan)</label>
                                <select name="destination_id" id="destination_select" class="w-full" required>
                                    @if($user->destination_id && $user->address_label)
                                        <option value="{{ $user->destination_id }}" selected>{{ $user->address_label }}</option>
                                    @else
                                        <option value="">Cari Kecamatan...</option>
                                    @endif
                                </select>
                            </div>

                            <div id="courier_section" class="hidden">
                                <label class="text-[9px] uppercase tracking-widest text-gray-400 block mb-2">Available Shipping Methods</label>
                                <div id="courier_list" class="space-y-3">
                                    {{-- List kurir di-render JS --}}
                                </div>
                            </div>

                            <div>
                                <label class="text-[9px] uppercase tracking-widest text-gray-400 block mb-2">Full Address</label>
                                <textarea name="receiver_address" rows="4" required placeholder="Nama jalan, Nomor rumah, RT/RW..."
                                    class="w-full border-gray-100 bg-white text-[12px] p-3 focus:ring-0 focus:border-[#8B864E]">{{ $user->address }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Kanan: Summary --}}
                <div class="space-y-6">
                    <div class="bg-white p-8 border border-gray-100 shadow-sm h-fit">
                        <h3 class="text-[11px] font-bold tracking-[0.3em] uppercase mb-8">Order Summary</h3>
                        
                        <div class="space-y-4 mb-8 border-b border-gray-50 pb-6">
                            @foreach($cart as $id => $item)
                                <div class="flex justify-between items-start gap-4">
                                    <div class="text-[10px] uppercase tracking-widest">
                                        <p class="font-bold text-gray-800">{{ $item['name'] }}</p>
                                        <p class="text-gray-400">{{ $item['color'] ?? '' }} | {{ $item['size'] ?? '' }} (x{{ $item['quantity'] }})</p>
                                    </div>
                                    <span class="text-[10px] text-[#8B864E]">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</span>
                                </div>
                            @endforeach
                        </div>

                        <div class="space-y-4 mb-8">
                            <div class="flex justify-between text-[11px] tracking-widest uppercase">
                                <span>Subtotal</span>
                                <span>Rp {{ number_format($totalAmount, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-[11px] tracking-widest uppercase text-gray-400">
                                <span>Shipping</span>
                                <span id="shipping_cost_display">--</span>
                            </div>
                            <div class="border-t border-gray-50 pt-4 flex justify-between text-[12px] font-bold tracking-widest uppercase text-[#8B864E]">
                                <span>Total</span>
                                <span id="grand_total_display">Rp {{ number_format($totalAmount, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <input type="hidden" name="shipping_cost" id="hidden_shipping_cost">
                        <input type="hidden" name="courier_name" id="hidden_courier_name">

                        <button type="submit" id="btn-place-order" disabled class="w-full bg-gray-300 text-white py-4 text-[10px] font-bold uppercase tracking-[0.3em] cursor-not-allowed transition">
                            Select Shipping First
                        </button>
                    </div>
                </div>

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
                    processResults: function (data) {
                        return { results: data.results };
                    },
                    cache: true
                },
                placeholder: 'Cari Kecamatan/Kota...',
                minimumInputLength: 3
            });

            function fetchShippingRates(destId) {
                if(!destId) return;

                $('#courier_section').removeClass('hidden');
                $('#courier_list').html('<p class="text-[10px] animate-pulse uppercase tracking-widest text-gray-400">Calculating shipping...</p>');
                resetShippingDisplay();

                $.post("{{ route('api.shipping') }}", {
                    _token: "{{ csrf_token() }}",
                    destination_id: destId
                }, function(res) {
                    if(res.success && res.pricing.length > 0) {
                        let html = '';
                        res.pricing.forEach(function(c) {
                            html += `
                            <label class="flex items-center justify-between border border-gray-100 p-4 cursor-pointer hover:bg-gray-50 transition group">
                                <div class="flex items-center gap-4">
                                    <input type="radio" name="shipping_option" value="${c.price}" 
                                        data-courier="${c.courier_name} ${c.courier_service_name}" 
                                        class="w-3 h-3 text-[#8B864E] focus:ring-0">
                                    <div class="text-[10px] uppercase tracking-widest">
                                        <p class="font-bold text-gray-700">${c.courier_name}</p>
                                        <p class="text-gray-400 text-[9px]">${c.courier_service_name} (${c.duration})</p>
                                    </div>
                                </div>
                                <span class="text-[11px] font-bold text-[#8B864E]">Rp ${c.price.toLocaleString('id-ID')}</span>
                            </label>`;
                        });
                        $('#courier_list').html(html);
                    } else {
                        $('#courier_list').html('<p class="text-[10px] text-red-400 uppercase">No shipping service available.</p>');
                    }
                });
            }

            // Trigger jika sudah ada alamat (misal dari profil)
            if($('#destination_select').val()) {
                fetchShippingRates($('#destination_select').val());
            }

            $('#destination_select').on('change', function() {
                fetchShippingRates($(this).val());
            });

            $(document).on('change', 'input[name="shipping_option"]', function() {
                const cost = parseInt($(this).val());
                const courierName = $(this).data('courier');
                const grandTotal = subtotal + cost;

                $('#hidden_shipping_cost').val(cost);
                $('#hidden_courier_name').val(courierName);
                $('#shipping_cost_display').text('Rp ' + cost.toLocaleString('id-ID')).removeClass('text-gray-400').addClass('text-[#8B864E]');
                $('#grand_total_display').text('Rp ' + grandTotal.toLocaleString('id-ID'));
                
                $('#btn-place-order').prop('disabled', false)
                    .text('Place Order')
                    .removeClass('bg-gray-300 cursor-not-allowed')
                    .addClass('bg-black hover:bg-gray-800');
            });

            function resetShippingDisplay() {
                $('#btn-place-order').prop('disabled', true).text('Select Shipping First').addClass('bg-gray-300').removeClass('bg-black');
                $('#shipping_cost_display').text('--').addClass('text-gray-400');
                $('#grand_total_display').text('Rp ' + subtotal.toLocaleString('id-ID'));
                $('#hidden_shipping_cost').val('');
                $('#hidden_courier_name').val('');
            }
        });
    </script>
</body>
</html>