<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pembayaran Berhasil - Farhana</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap');
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-[#FCFCFA] min-h-screen flex items-center justify-center px-4">
    <div class="w-full max-w-md text-center space-y-8">

        <div class="text-[11px] font-bold tracking-[0.5em] uppercase">Farhana</div>

        <div class="bg-white border border-gray-100 rounded-2xl p-10 shadow-sm space-y-6">

            <div class="w-16 h-16 bg-[#f0f4e8] rounded-full flex items-center justify-center mx-auto">
                <svg class="w-8 h-8 text-[#5A5A00]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>

            <div>
                <h1 class="text-xl font-bold text-[#1a1a1a]">Pembayaran Berhasil</h1>
                <p class="text-[11px] text-gray-400 mt-2">Terima kasih telah berbelanja di Farhana</p>
            </div>

            <div class="text-left space-y-3 border-t border-gray-50 pt-6">
                <div class="flex justify-between text-[12px]">
                    <span class="text-gray-400">No. Order</span>
                    <span class="font-bold">{{ $order->order_number }}</span>
                </div>
                <div class="flex justify-between text-[12px]">
                    <span class="text-gray-400">Status</span>
                    <span class="font-medium text-green-600 capitalize">{{ $order->status }}</span>
                </div>
                <div class="flex justify-between text-[12px]">
                    <span class="text-gray-400">Kurir</span>
                    <span class="font-medium">{{ $order->courier_name }}</span>
                </div>

                {{-- Resi: tampil link kalau ada, pesan kalau belum --}}
                <div class="flex justify-between text-[12px]">
                    <span class="text-gray-400">No. Resi JNE</span>
                    @if($order->tracking_number)
                        <a href="{{ route('tracking.show', $order->tracking_number) }}"
                            class="font-bold text-[#5A5A00] hover:underline flex items-center gap-1">
                            {{ $order->tracking_number }}
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    @else
                        <span class="text-gray-400 italic text-[11px]">Sedang diproses...</span>
                    @endif
                </div>

                <div class="flex justify-between text-[12px] pt-2 border-t border-gray-50">
                    <span class="font-bold uppercase text-[11px] tracking-wider">Total</span>
                    <span class="font-bold text-lg">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</span>
                </div>
            </div>

            @if(!$order->tracking_number)
            <div class="bg-yellow-50 border border-yellow-100 rounded-xl p-4 text-left">
                <p class="text-[10px] text-yellow-700 leading-relaxed">
                    Nomor resi JNE sedang diproses. Cek halaman pesananmu untuk update terbaru.
                </p>
            </div>
            @endif

            <div class="space-y-3 pt-2">
                <a href="{{ route('profile.orders') }}"
                    class="block w-full bg-[#5A5A00] text-white py-4 rounded-xl text-[12px] font-bold uppercase tracking-widest hover:bg-black transition-all text-center">
                    Lihat Pesanan Saya
                </a>
                <a href="{{ route('home') }}"
                    class="block w-full border border-gray-200 text-gray-600 py-4 rounded-xl text-[12px] font-bold uppercase tracking-widest hover:border-gray-400 transition-all text-center">
                    Lanjut Belanja
                </a>
            </div>
        </div>
    </div>
</body>
</html>