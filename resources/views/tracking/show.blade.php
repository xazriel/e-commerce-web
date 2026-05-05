<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lacak Paket {{ $awb }} - Farhana</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap');
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-[#FCFCFA] min-h-screen px-4 py-12">
<div class="max-w-2xl mx-auto space-y-6">

    {{-- Header --}}
    <div class="text-center">
        <a href="{{ route('home') }}" class="text-[11px] font-bold tracking-[0.5em] uppercase">Farhana</a>
        <h1 class="text-2xl font-bold mt-3">Lacak Paket</h1>
        <p class="text-[11px] text-gray-400 mt-1">
            No. Resi: <span class="font-bold text-[#5A5A00]">{{ $awb }}</span>
        </p>
    </div>

    @if(session('error'))
    <div class="bg-red-50 border border-red-200 text-red-700 text-[11px] px-5 py-3 rounded-xl">
        {{ session('error') }}
    </div>
    @endif

    {{-- Status Utama --}}
    @if($cnote)
    <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <span class="text-[10px] uppercase tracking-widest text-gray-400">Status Pengiriman</span>
            <span class="text-[11px] font-bold px-3 py-1 rounded-full
                {{ ($cnote['pod_status'] ?? '') === 'DELIVERED'
                    ? 'bg-green-100 text-green-700'
                    : 'bg-yellow-100 text-yellow-700' }}">
                {{ $cnote['pod_status'] ?? 'ON PROCESS' }}
            </span>
        </div>

        <p class="text-[12px] text-gray-700 font-medium leading-relaxed">
            {{ $cnote['last_status'] ?? '-' }}
        </p>

        <div class="grid grid-cols-2 gap-4 mt-5 pt-5 border-t border-gray-50 text-[11px]">
            <div>
                <p class="text-gray-400 mb-1">Pengirim</p>
                <p class="font-bold">{{ $detail['cnote_shipper_name'] ?? '-' }}</p>
                <p class="text-gray-500">{{ $detail['cnote_shipper_city'] ?? '-' }}</p>
            </div>
            <div>
                <p class="text-gray-400 mb-1">Penerima</p>
                <p class="font-bold">{{ $cnote['cnote_receiver_name'] ?? '-' }}</p>
                <p class="text-gray-500">{{ $cnote['city_name'] ?? '-' }}</p>
            </div>
            <div>
                <p class="text-gray-400 mb-1">Layanan</p>
                <p class="font-bold">JNE {{ $cnote['cnote_services_code'] ?? '-' }}</p>
            </div>
            <div>
                <p class="text-gray-400 mb-1">Estimasi</p>
                <p class="font-bold">{{ $cnote['estimate_delivery'] ?? '-' }}</p>
            </div>
            @if(isset($cnote['cnote_pod_date']) && $cnote['cnote_pod_date'])
            <div class="col-span-2">
                <p class="text-gray-400 mb-1">Tanggal Terima</p>
                <p class="font-bold">{{ \Carbon\Carbon::parse($cnote['cnote_pod_date'])->format('d M Y, H:i') }}</p>
                @if(isset($cnote['cnote_pod_receiver']))
                <p class="text-gray-500">Diterima oleh: {{ $cnote['cnote_pod_receiver'] }}</p>
                @endif
            </div>
            @endif
        </div>
    </div>
    @endif

    {{-- Timeline History --}}
    @if(count($history) > 0)
    <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm">
        <h3 class="text-[10px] uppercase tracking-widest text-gray-400 mb-6">Riwayat Pengiriman</h3>
        <div class="relative">
            <div class="absolute left-[7px] top-2 bottom-2 w-px bg-gray-100"></div>
            <div class="space-y-5">
                @foreach(array_reverse($history) as $i => $h)
                <div class="flex gap-4 relative">
                    <div class="w-4 h-4 rounded-full border-2 flex-shrink-0 flex items-center justify-center mt-0.5 z-10
                        {{ $i === 0
                            ? 'border-[#5A5A00] bg-[#5A5A00]'
                            : 'border-gray-200 bg-white' }}">
                        @if($i === 0)
                        <div class="w-1.5 h-1.5 rounded-full bg-white"></div>
                        @endif
                    </div>
                    <div class="flex-1 pb-1">
                        <p class="text-[11px] font-medium text-gray-800 leading-relaxed">
                            {{ $h['desc'] }}
                        </p>
                        <p class="text-[10px] text-gray-400 mt-0.5">{{ $h['date'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    {{-- Kalau tidak ada data --}}
    @if(!$cnote && count($history) === 0)
    <div class="bg-white border border-gray-100 rounded-2xl p-10 text-center shadow-sm">
        <p class="text-[12px] text-gray-400">Data tracking belum tersedia untuk resi ini.</p>
        <p class="text-[10px] text-gray-300 mt-1">Coba beberapa saat lagi.</p>
    </div>
    @endif

    <a href="{{ route('profile.orders') }}"
        class="block text-center text-[11px] text-gray-400 hover:text-black transition uppercase tracking-widest py-2">
        ← Kembali ke Pesanan
    </a>

</div>
</body>
</html>