<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Menunggu Pembayaran - Farhana</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap');
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-[#FCFCFA] min-h-screen px-4 py-12">
    <div class="max-w-lg mx-auto space-y-6">

        {{-- Header --}}
        <div class="text-center">
            <div class="text-[11px] font-bold tracking-[0.5em] uppercase mb-2">Farhana</div>
            <p class="text-[10px] text-gray-400 uppercase tracking-widest">Complete your payment</p>
        </div>

        {{-- Countdown --}}
        <div class="bg-white border border-gray-100 rounded-2xl p-8 text-center shadow-sm">
            <p class="text-[10px] uppercase tracking-widest text-gray-400 mb-3">Selesaikan pembayaran dalam</p>
            <div id="countdown" class="text-5xl font-bold text-[#5A5A00] tabular-nums">--:--</div>
            <p class="text-[10px] text-gray-400 mt-3">Order <span class="font-bold text-gray-600">{{ $order->order_number }}</span></p>
        </div>

        {{-- Order Summary --}}
        <div class="bg-white border border-gray-100 rounded-2xl p-8 shadow-sm space-y-4">
            <h3 class="text-[10px] uppercase tracking-[0.2em] font-bold text-gray-400">Order Summary</h3>

            {{-- Items --}}
            @foreach($order->items as $item)
            <div class="flex justify-between items-center text-[12px]">
                <span class="text-gray-600">
                    {{ $item->product->name ?? 'Produk' }}
                    <span class="text-gray-400">× {{ $item->quantity }}</span>
                </span>
                <span class="font-medium">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</span>
            </div>
            @endforeach

            <div class="border-t border-gray-50 pt-4 space-y-3">
                <div class="flex justify-between text-[12px] text-gray-500">
                    <span>Subtotal</span>
                    <span>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-[12px] text-gray-500">
                    <span>Ongkos Kirim</span>
                    <span>Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-[12px] text-gray-500">
                    <span>Kurir</span>
                    <span class="font-medium">{{ $order->courier_name }}</span>
                </div>
                <div class="flex justify-between text-[12px] font-bold pt-2 border-t border-gray-50">
                    <span class="uppercase tracking-wider text-[11px]">Total</span>
                    <span class="text-lg">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        {{-- Shipping Info --}}
        <div class="bg-white border border-gray-100 rounded-2xl p-8 shadow-sm">
            <h3 class="text-[10px] uppercase tracking-[0.2em] font-bold text-gray-400 mb-4">Shipping To</h3>
            <p class="text-[12px] font-bold">{{ $order->receiver_name }}</p>
            <p class="text-[11px] text-gray-500 mt-1">{{ $order->receiver_phone }}</p>
            <p class="text-[11px] text-gray-500 mt-1">{{ $order->receiver_address }}</p>
            @if($order->receiver_city)
            <p class="text-[11px] text-gray-400">{{ $order->receiver_city }} {{ $order->receiver_zip }}</p>
            @endif
        </div>

        {{-- Tombol Bayar --}}
        <button id="pay-button"
            class="w-full bg-[#5A5A00] text-white py-4 rounded-xl text-[12px] font-bold uppercase tracking-widest hover:bg-black transition-all shadow-sm">
            Bayar Sekarang
        </button>

        {{-- Cancel --}}
        <form action="{{ route('checkout.cancel', $order->order_number) }}" method="POST"
            onsubmit="return confirm('Batalkan pesanan ini?')">
            @csrf
            @method('PATCH')
            <button type="submit"
                class="w-full border border-gray-200 text-gray-400 py-3 rounded-xl text-[11px] uppercase tracking-widest hover:border-red-200 hover:text-red-400 transition-all">
                Batalkan Pesanan
            </button>
        </form>

        <p class="text-center text-[9px] text-gray-400 leading-relaxed px-4">
            Klik "Bayar Sekarang" untuk membuka halaman pembayaran Midtrans yang aman.
        </p>
    </div>

    <script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ $clientKey }}"></script>

    <script>
        const deadline = new Date("{{ $order->payment_deadline }}").getTime();

        function updateCountdown() {
            const diff = deadline - new Date().getTime();
            if (diff <= 0) {
                document.getElementById('countdown').textContent = '00:00';
                document.getElementById('pay-button').disabled = true;
                document.getElementById('pay-button').classList.add('opacity-50', 'cursor-not-allowed');
                return;
            }
            const m = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            const s = Math.floor((diff % (1000 * 60)) / 1000);
            document.getElementById('countdown').textContent =
                String(m).padStart(2, '0') + ':' + String(s).padStart(2, '0');
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