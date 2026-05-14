<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lacak Paket {{ $awb }} - Farhana</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');
        
        :root {
            --primary: #2F3526; /* Warna Olive Gelap khas Farhana */
            --accent: #5A5A00;
            --bg-light: #F8F9F5;
        }

        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-color: var(--bg-light);
            color: var(--primary);
        }

        .premium-shadow {
            box-shadow: 0 10px 30px -10px rgba(47, 53, 38, 0.1);
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: var(--primary); border-radius: 10px; }
    </style>
</head>
<body class="antialiased min-h-screen px-4 py-8 md:py-16">
<div class="max-w-3xl mx-auto space-y-8">

    {{-- Header --}}
    <div class="text-center space-y-4">
        <a href="{{ route('home') }}" class="inline-block">
            <span class="text-[14px] font-black tracking-[0.6em] uppercase border-b-2 border-current pb-1">Farhana</span>
        </a>
        <div class="pt-4">
            <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight">Lacak Pengiriman</h1>
            <p class="text-sm text-gray-500 mt-2 tracking-wide">
                No. Resi: <span class="font-bold text-black bg-yellow-100 px-2 py-0.5 rounded">{{ $awb }}</span>
            </p>
        </div>
    </div>

    @if(session('error'))
    <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-xl text-sm animate-pulse">
        {{ session('error') }}
    </div>
    @endif

    {{-- Status Utama --}}
    @if($cnote)
    <div class="bg-white rounded-[2rem] p-8 md:p-10 border border-gray-100 premium-shadow">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-10">
            <div>
                <span class="text-[11px] uppercase tracking-[0.2em] text-gray-400 font-bold">Status Saat Ini</span>
                <h2 class="text-xl md:text-2xl font-bold mt-1 leading-tight text-gray-900">
                    {{ $cnote['last_status'] ?? 'Sedang diproses' }}
                </h2>
            </div>
            <div class="flex-shrink-0">
                <span class="inline-flex items-center px-5 py-2 rounded-full text-xs font-black uppercase tracking-widest
                    {{ ($cnote['pod_status'] ?? '') === 'DELIVERED'
                        ? 'bg-green-100 text-green-700'
                        : 'bg-stone-800 text-white shadow-lg shadow-stone-200' }}">
                    <span class="w-2 h-2 rounded-full mr-2 {{ ($cnote['pod_status'] ?? '') === 'DELIVERED' ? 'bg-green-500' : 'bg-yellow-400 animate-ping' }}"></span>
                    {{ $cnote['pod_status'] ?? 'On Process' }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 py-8 border-t border-gray-100">
            <div class="space-y-1">
                <p class="text-[11px] uppercase tracking-wider text-gray-400 font-bold">Informasi Pengirim</p>
                <p class="text-lg font-bold">{{ $detail['cnote_shipper_name'] ?? '-' }}</p>
                <p class="text-sm text-gray-500">{{ $detail['cnote_shipper_city'] ?? '-' }}</p>
            </div>
            <div class="space-y-1">
                <p class="text-[11px] uppercase tracking-wider text-gray-400 font-bold">Tujuan Penerima</p>
                <p class="text-lg font-bold">{{ $cnote['cnote_receiver_name'] ?? '-' }}</p>
                <p class="text-sm text-gray-500">{{ $cnote['city_name'] ?? '-' }}</p>
            </div>
            <div class="space-y-1">
                <p class="text-[11px] uppercase tracking-wider text-gray-400 font-bold">Layanan</p>
                <p class="text-lg font-bold flex items-center">
                    <span class="bg-gray-100 px-2 py-1 rounded text-sm mr-2 italic">JNE</span>
                    {{ $cnote['cnote_services_code'] ?? '-' }}
                </p>
            </div>
            <div class="space-y-1">
                <p class="text-[11px] uppercase tracking-wider text-gray-400 font-bold">Estimasi Tiba</p>
                <p class="text-lg font-bold text-stone-700">{{ $cnote['estimate_delivery'] ?? 'Menghitung...' }}</p>
            </div>

            @if(isset($cnote['cnote_pod_date']) && $cnote['cnote_pod_date'])
            <div class="col-span-1 md:col-span-2 bg-stone-50 p-6 rounded-2xl border border-stone-100 mt-4">
                <p class="text-[11px] uppercase tracking-wider text-stone-400 font-bold">Diterima Pada</p>
                <p class="text-xl font-black text-stone-800">
                    {{ \Carbon\Carbon::parse($cnote['cnote_pod_date'])->translatedFormat('d F Y, H:i') }}
                </p>
                @if(isset($cnote['cnote_pod_receiver']))
                <p class="text-sm mt-1 text-stone-600 italic">Diterima oleh: <span class="font-bold uppercase">{{ $cnote['cnote_pod_receiver'] }}</span></p>
                @endif
            </div>
            @endif
        </div>
    </div>
    @endif

    {{-- Timeline History --}}
    @if(count($history) > 0)
    <div class="bg-white rounded-[2rem] p-8 md:p-10 border border-gray-100 premium-shadow">
        <div class="flex items-center justify-between mb-10">
            <h3 class="text-sm font-black uppercase tracking-[0.3em] text-gray-800">Riwayat Perjalanan</h3>
            <span class="text-[10px] text-gray-400 font-medium">Terakhir diperbarui: {{ now()->format('H:i') }}</span>
        </div>
        
        <div class="relative pl-4 md:pl-8">
            {{-- Garis Tengah Timeline --}}
            <div class="absolute left-[19px] md:left-[35px] top-2 bottom-2 w-[2px] bg-gradient-to-b from-stone-800 via-gray-200 to-gray-100"></div>
            
            <div class="space-y-10">
                @foreach(array_reverse($history) as $i => $h)
                <div class="relative flex gap-6 md:gap-10">
                    {{-- Dot --}}
                    <div class="relative z-10">
                        <div class="w-8 h-8 md:w-10 md:h-10 rounded-full flex items-center justify-center border-4
                            {{ $i === 0
                                ? 'bg-stone-800 border-stone-200 shadow-lg scale-110'
                                : 'bg-white border-gray-100' }}">
                            @if($i === 0)
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            @else
                                <div class="w-2 h-2 rounded-full bg-gray-300"></div>
                            @endif
                        </div>
                    </div>

                    {{-- Konten History --}}
                    <div class="flex-1 pt-1">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-1 mb-1">
                            <p class="text-sm md:text-base font-bold text-gray-900 leading-snug">
                                {{ $h['desc'] }}
                            </p>
                            <span class="text-[11px] font-bold text-stone-400 whitespace-nowrap">{{ $h['date'] }}</span>
                        </div>
                        @if($i === 0)
                            <span class="text-[10px] font-black uppercase tracking-widest text-stone-500 bg-stone-100 px-2 py-0.5 rounded">Posisi Terakhir</span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    {{-- No Data --}}
    @if(!$cnote && count($history) === 0)
    <div class="bg-white rounded-[2rem] p-16 text-center border border-gray-100 premium-shadow space-y-4">
        <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto">
            <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
        </div>
        <div class="space-y-1">
            <h3 class="text-lg font-bold text-gray-800">Data Belum Tersedia</h3>
            <p class="text-sm text-gray-400 max-w-xs mx-auto">Sistem JNE sedang memperbarui data kamu. Coba cek lagi dalam beberapa menit.</p>
        </div>
    </div>
    @endif

    <div class="pt-6">
        <a href="{{ route('profile.orders') }}"
            class="flex items-center justify-center gap-2 text-[12px] font-black uppercase tracking-[0.2em] text-stone-400 hover:text-stone-800 transition-all duration-300">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Pesanan
        </a>
    </div>

</div>
</body>
</html>