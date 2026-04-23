<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cart - Farhana Official</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap');
        body { font-family: 'Inter', sans-serif; }
        
        .cart-item-transition { transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); }
        .product-img-container { width: 120px; height: 160px; }

        .qty-wrapper {
            display: flex;
            align-items: center;
            border: 1px solid #e5e7eb;
            background-color: #f9f9f9;
            width: fit-content;
        }
        .qty-input-btn {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: 300;
            color: #4b5563;
            transition: all 0.2s;
            cursor: pointer;
            border: none;
            background: none;
        }
        .qty-input-btn:hover:not(:disabled) {
            color: #000;
            background-color: #f3f4f6;
        }
        .qty-input-btn:disabled {
            opacity: 0.3;
            cursor: not-allowed;
        }
        .qty-value {
            width: 40px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 500;
            color: #1a1a1a;
            user-select: none;
            border-left: 1px solid #e5e7eb;
            border-right: 1px solid #e5e7eb;
        }
        .updating { opacity: 0.5; pointer-events: none; }
    </style>
</head>
<body class="bg-[#FCFCFA] text-[#1a1a1a] antialiased">

    <header class="py-6 px-8 flex justify-between items-center border-b border-gray-100 bg-white sticky top-0 z-50">
        <a href="/" class="text-[10px] uppercase tracking-widest flex items-center gap-2 hover:opacity-60 transition font-sans">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Farhana
        </a>
    </header>
    
    <main class="max-w-[1000px] mx-auto px-6 py-16 min-h-[70vh]">
        
        <div class="flex items-center gap-6 mb-16">
            <h1 class="text-lg tracking-[0.4em] uppercase font-light text-[#1a1a1a]">Shopping Bag</h1>
            <div class="h-[1px] flex-grow bg-gray-100"></div>
            <span class="text-[9px] uppercase tracking-widest text-gray-400 font-medium font-sans">
                <span id="cart-count">{{ count(session('cart') ?? []) }}</span> ITEMS
            </span>
        </div>

        @if(session('cart') && count(session('cart')) > 0)
            <div class="divide-y divide-gray-50" id="cart-container">
                @php $total = 0; @endphp
                @foreach(session('cart') as $id => $details)
                    @php $total += $details['price'] * $details['quantity']; @endphp
                    
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-6 py-10 items-center cart-item-transition" id="item-row-{{ $id }}">
                        <div class="col-span-1 md:col-span-2 flex justify-center md:justify-start">
                            <div class="product-img-container overflow-hidden bg-[#f9f9f7] relative border border-gray-50">
                                @if($details['image'])
                                    <img src="{{ asset('storage/' . $details['image']) }}" 
                                         alt="{{ $details['name'] }}" 
                                         class="w-full h-full object-cover mix-blend-multiply">
                                @endif
                            </div>
                        </div>

                        <div class="col-span-1 md:col-span-5 text-center md:text-left">
                            <h2 class="text-[12px] tracking-[0.15em] uppercase font-semibold text-[#1a1a1a] mb-1">{{ $details['name'] }}</h2>
                            <p class="text-[10px] text-gray-400 uppercase tracking-[0.15em] mb-1 font-sans">{{ $details['color'] }} / {{ $details['size'] }}</p>
                            <p class="text-[11px] font-medium text-gray-600 font-sans">Rp {{ number_format($details['price'], 0, ',', '.') }}</p>
                        </div>

                        <div class="col-span-1 md:col-span-3 flex justify-center">
                            <div class="qty-wrapper" data-id="{{ $id }}">
                                <button type="button" class="qty-input-btn btn-update" data-action="decrease" {{ $details['quantity'] <= 1 ? 'disabled' : '' }}>−</button>
                                <span class="qty-value font-sans" id="qty-val-{{ $id }}">{{ $details['quantity'] }}</span>
                                <button type="button" class="qty-input-btn btn-update" data-action="increase">+</button>
                            </div>
                        </div>

                        <div class="col-span-1 md:col-span-2 flex flex-col items-center md:items-end gap-3">
                            <span class="text-[12px] font-semibold tracking-wider text-[#1a1a1a] font-sans" id="item-subtotal-{{ $id }}">
                                Rp {{ number_format($details['price'] * $details['quantity'], 0, ',', '.') }}
                            </span>
                            <form action="{{ route('cart.remove', $id) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-[9px] uppercase tracking-[0.2em] text-gray-300 hover:text-red-500 transition-colors border-b border-gray-100 hover:border-red-500 pb-0.5 font-sans">
                                    Remove
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-16 pt-10 border-t border-gray-100 flex flex-col items-center md:items-end">
                <div class="w-full md:w-[320px] space-y-6">
                    <div class="flex justify-between items-baseline">
                        <span class="text-[10px] uppercase tracking-[0.3em] text-gray-400 font-medium">Subtotal</span>
                        <span class="text-lg font-light tracking-[0.05em] text-[#1a1a1a] font-sans" id="cart-total">
                            Rp {{ number_format($total, 0, ',', '.') }}
                        </span>
                    </div>
                    
                    <a href="{{ route('checkout.index') }}" 
                    class="block w-full bg-[#5A5A00] text-white py-5 text-[10px] font-bold uppercase tracking-[0.4em] text-center border border-[#5A5A00] hover:bg-white hover:text-[#5A5A00] transition-all duration-500">
                    Checkout
                    </a>
                    <p class="text-[9px] text-gray-400 tracking-widest text-center uppercase italic opacity-60">Taxes and shipping calculated at checkout</p>
                </div>
            </div>

        @else
            <div class="text-center py-40">
                <p class="text-[11px] text-gray-400 uppercase tracking-[0.4em] mb-12 italic">Your shopping bag is empty.</p>
                <a href="/" class="inline-block text-[10px] font-bold uppercase tracking-[0.4em] border border-[#1a1a1a] px-14 py-4 hover:bg-[#1a1a1a] hover:text-white transition-all">Start Shopping</a>
            </div>
        @endif
    </main>
    
    <footer class="mt-20 py-10 text-center border-t border-gray-50">
        <p class="text-[9px] text-gray-300 uppercase tracking-[0.5em] font-sans">&copy; 2026 Farhana Official</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        document.querySelectorAll('.btn-update').forEach(button => {
            button.addEventListener('click', function() {
                const wrapper = this.closest('.qty-wrapper');
                const id = wrapper.getAttribute('data-id');
                const action = this.getAttribute('data-action');
                const qtyValSpan = document.getElementById(`qty-val-${id}`);
                const row = document.getElementById(`item-row-${id}`);

                // Visual feedback: Tambahkan kelas updating
                row.classList.add('updating');

                axios.patch(`/cart/update/${id}`, {
                    action: action
                })
                .then(response => {
                    if (response.data.success) {
                        // 1. Update Angka Quantity
                        qtyValSpan.innerText = response.data.newQty;

                        // 2. Update Subtotal Item tersebut (Format Rupiah)
                        const itemSubtotal = document.getElementById(`item-subtotal-${id}`);
                        itemSubtotal.innerText = response.data.itemSubtotal;

                        // 3. Update Total Akhir Keranjang
                        const cartTotal = document.getElementById('cart-total');
                        cartTotal.innerText = response.data.cartTotal;

                        // 4. Update Button State (Disable minus jika qty 1)
                        const btnMinus = wrapper.querySelector('[data-action="decrease"]');
                        btnMinus.disabled = (response.data.newQty <= 1);
                    }
                })
                .catch(error => {
                    const msg = error.response?.data?.message || 'Gagal memperbarui keranjang';
                    alert(msg);
                })
                .finally(() => {
                    row.classList.remove('updating');
                });
            });
        });
    </script>
</body>
</html>