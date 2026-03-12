@php
    // Ambil data keranjang dari session
    $cart = session()->get('cart', []);
    
    // Hitung total kuantitas (misal: 2 Hijab + 1 Gamis = 3)
    $totalCount = 0;
    foreach ($cart as $item) {
        $totalCount += $item['quantity'] ?? 0;
    }
@endphp

<div class="relative inline-block">
    <a href="{{ route('cart.index') }}" class="text-gray-600 hover:text-black transition-colors duration-300 flex items-center p-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
        </svg>

        @if($totalCount > 0)
            <span class="absolute top-1 right-0 transform translate-x-1/2 -translate-y-1/2 bg-black text-white text-[9px] font-bold px-1.5 py-0.5 rounded-full min-w-[18px] text-center border border-white uppercase tracking-tighter">
                {{ $totalCount }}
            </span>
        @endif
    </a>
</div>