<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Your Cart - Farhana Web</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .cart-container { max-width: 900px; }
        .product-img { width: 230px; height: 330px; object-fit: cover; }
    </style>
</head>
<body class="bg-[#FCFCFA] text-[#4A4A4A] antialiased font-sans">

    <header class="py-6 px-8 flex justify-between items-center border-b border-gray-100 bg-white">
        <a href="{{ route('home') }}" class="text-[10px] uppercase tracking-widest flex items-center gap-2 hover:opacity-60 transition">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Farhana
        </a>
    </header>
    
    <main class="cart-container mx-auto px-6 py-12">
        <h1 class="text-center text-lg tracking-[0.3em] uppercase mb-16 text-[#8B864E]">Cart</h1>

        @if(session('cart') && count(session('cart')) > 0)
            <div class="bg-white p-8 border border-gray-50 shadow-sm rounded-sm">
                @php $total = 0; @endphp
                @foreach(session('cart') as $id => $details)
                    @php $total += $details['price'] * $details['quantity']; @endphp
                    
                    <div class="flex items-start gap-8 py-6 border-b border-gray-50 last:border-0">
                        <div class="flex-shrink-0 bg-gray-50 rounded-sm overflow-hidden border border-gray-100">
                            @if($details['image'])
                                <img src="{{ asset('storage/' . $details['image']) }}" alt="{{ $details['name'] }}" class="product-img">
                            @endif
                        </div>

                        <div class="flex-grow">
                            <h2 class="text-[12px] font-medium tracking-wide text-gray-800 mb-1 uppercase italic">{{ $details['name'] }}</h2>
                            
                            <p class="text-[10px] text-gray-400 mb-2 uppercase tracking-widest">
                                {{ $details['color'] }} / {{ $details['size'] }}
                            </p>
                            
                            <p class="text-[13px] font-semibold text-[#8B864E]">Rp {{ number_format($details['price'], 0, ',', '.') }}</p>
                            
                            <div class="mt-6 flex items-center justify-end gap-6">
                                <form action="{{ route('cart.remove', $id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-[10px] uppercase tracking-widest text-[#8B864E] hover:text-red-800 transition border-b border-[#8B864E] pb-0.5">
                                        Remove
                                    </button>
                                </form>

                                <div class="flex items-center border border-gray-200 rounded-sm px-4 py-1">
                                    <span class="text-[11px] font-bold">Qty: {{ $details['quantity'] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8 flex flex-col items-end gap-6">
                <div class="flex justify-between w-full sm:w-1/2 text-sm border-t border-gray-100 pt-6">
                    <span class="text-[11px] uppercase tracking-[0.2em] text-gray-400">Total Price ({{ count(session('cart')) }})</span>
                    <span class="text-md font-bold text-[#8B864E]">Rp {{ number_format($total, 0, ',', '.') }}</span>
                </div>

                <div class="w-full sm:w-1/3">
                    <a href="{{ route('checkout.index') }}" class="block w-full bg-[#6B6631] text-white text-center py-4 text-[10px] font-bold uppercase tracking-[0.2em] rounded-full hover:bg-[#5A5629] transition shadow-md flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        Secure Checkout
                    </a>
                </div>
            </div>

        @else
            <div class="text-center py-20 bg-white border border-gray-50">
                <p class="text-[11px] text-gray-400 uppercase tracking-widest italic mb-8">Your cart is empty</p>
                <a href="{{ route('home') }}" class="text-[10px] font-bold uppercase tracking-widest text-[#8B864E] border border-[#8B864E] px-10 py-3 rounded-full hover:bg-[#8B864E] hover:text-white transition">Explore Now</a>
            </div>
        @endif
    </main>

    <footer class="mt-20 py-10 text-center border-t border-gray-50">
        <p class="text-[9px] text-gray-300 uppercase tracking-[0.5em]">&copy; 2026 Farhana Official</p>
    </footer>

</body>
</html>