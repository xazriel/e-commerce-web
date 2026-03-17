<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $product->name }} - Farhana Web</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        #mainImage { transition: opacity 0.3s ease-in-out; }
        .product-title { letter-spacing: 0.1em; }
        .description-text { line-height: 1.8; letter-spacing: 0.05em; }
        input[type='number']::-webkit-inner-spin-button,
        input[type='number']::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
        .sold-out-overlay { background: rgba(255, 255, 255, 0.7); pointer-events: none; }
    </style>
</head>
<body class="bg-white text-gray-900 font-sans antialiased">
    
    <nav class="bg-white border-b border-gray-100 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex-shrink-0">
                    <a href="{{ route('home') }}" class="text-2xl font-light tracking-[0.4em] uppercase">Farhana</a>
                </div>
                <div class="flex items-center gap-8">
                    <a href="{{ route('home') }}" class="text-[10px] font-bold uppercase tracking-[0.2em] text-gray-400 hover:text-black transition">
                        &larr; Back to Collection
                    </a>
                </div>
            </div>
        </div>
        @if(session('success'))
            <div class="bg-black text-white text-[10px] tracking-[0.2em] uppercase py-3 text-center animate-fade-in-down">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-500 text-white text-[10px] tracking-[0.2em] uppercase py-3 text-center animate-fade-in-down">
                {{ session('error') }}
            </div>
        @endif
    </nav>

    <div class="max-w-7xl mx-auto px-4 py-12 lg:py-20">
        <div class="flex flex-col lg:flex-row gap-16">
            
            <div class="lg:w-3/5 space-y-6">
                <div class="bg-gray-50 overflow-hidden aspect-[3/4] border border-gray-50 relative">
                    <img id="mainImage" 
                         src="{{ asset('storage/' . ($product->images->where('is_primary', true)->first()->image_path ?? $product->images->first()->image_path)) }}" 
                         class="w-full h-full object-cover" 
                         alt="{{ $product->name }}">
                </div>

                <div class="grid grid-cols-6 gap-3">
                    @foreach($product->images as $image)
                        <div class="cursor-pointer border-b-2 {{ $loop->first ? 'border-black' : 'border-transparent' }} hover:border-black transition pb-2 thumb-img"
                             data-color="{{ strtolower($image->color) }}"
                             onclick="changeImage('{{ asset('storage/' . $image->image_path) }}', this)">
                            <div class="aspect-square bg-gray-50 overflow-hidden">
                                <img src="{{ asset('storage/' . $image->image_path) }}" class="w-full h-full object-cover">
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="lg:w-2/5">
                <div class="sticky top-32">
                    <div class="flex flex-wrap gap-2 mb-4">
                        <p class="text-[10px] text-gray-400 uppercase tracking-[0.4em]">
                            {{ $product->category->name ?? 'Collection' }}
                        </p>
                        @foreach($product->tags as $tag)
                            <span class="text-[9px] bg-gray-100 px-2 py-0.5 uppercase font-bold tracking-widest">{{ $tag->name }}</span>
                        @endforeach
                    </div>

                    <h1 class="text-3xl font-light mb-6 tracking-widest uppercase text-gray-900 leading-snug">
                        {{ $product->name }}
                    </h1>
                    <p class="text-2xl font-light mb-10 text-gray-900 tracking-wider">
                        IDR {{ number_format($product->price, 0, ',', '.') }}
                    </p>

                    <form action="{{ route('cart.add', $product->id) }}" method="POST" id="addToCartForm">
                        @csrf
                        
                        <input type="hidden" name="variant_id" id="variant_id_input" required>

                        @php
                            $uniqueColors = $product->variants->pluck('color')->unique();
                            $uniqueSizes = $product->variants->pluck('size')->unique();
                        @endphp

                        @if($uniqueColors->count() > 0)
                        <div class="mb-8">
                            <h3 class="text-[10px] font-bold uppercase tracking-[0.2em] mb-4 text-gray-900">Select Color</h3>
                            <div class="flex flex-wrap gap-3">
                                @foreach($uniqueColors as $color)
                                    <label class="cursor-pointer">
                                        <input type="radio" name="color" value="{{ $color }}" 
                                               class="hidden peer color-radio" 
                                               onchange="filterSizeByColor('{{ $color }}')" required>
                                        <span class="px-5 py-2 border border-gray-200 text-[10px] uppercase tracking-widest peer-checked:border-black peer-checked:bg-black peer-checked:text-white transition block">
                                            {{ $color }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <div class="mb-8">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-[10px] font-bold uppercase tracking-[0.2em] text-gray-900">Select Size</h3>
                                <button type="button" onclick="toggleModal('sizeGuideModal')" class="text-[9px] uppercase tracking-widest border-b border-gray-300 pb-0.5 text-gray-400 hover:text-black transition">Size Guide</button>
                            </div>
                            <div class="flex flex-wrap gap-3" id="size-container">
                                @foreach($uniqueSizes as $size)
                                    <label class="cursor-pointer size-option" data-size="{{ $size }}">
                                        <input type="radio" name="size" value="{{ $size }}" 
                                               class="hidden peer size-radio" 
                                               onchange="updateStockDisplay()" required>
                                        <span class="w-12 h-12 flex items-center justify-center border border-gray-200 text-[10px] peer-checked:border-black peer-checked:bg-black peer-checked:text-white transition">
                                            {{ $size }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="mb-10">
                            <div class="flex justify-between mb-4">
                                <h3 class="text-[10px] font-bold uppercase tracking-[0.2em] text-gray-900">Quantity</h3>
                                <span id="stock-count" class="text-[9px] uppercase text-gray-400 tracking-widest">Select size to see stock</span>
                            </div>
                            <div class="flex items-center border border-gray-200 w-32">
                                <button type="button" onclick="decrementQty()" class="px-4 py-2 hover:bg-gray-50 transition text-gray-400">-</button>
                                <input type="number" id="qtyInput" name="quantity" value="1" min="1" class="w-full text-center border-none text-[11px] focus:ring-0" readonly>
                                <button type="button" onclick="incrementQty()" class="px-4 py-2 hover:bg-gray-50 transition text-gray-400">+</button>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <button type="submit" id="mainSubmitBtn" class="w-full bg-black text-white py-5 text-[10px] font-bold uppercase tracking-[0.3em] hover:bg-gray-800 transition duration-500 shadow-lg">
                                Add to Cart
                            </button>
                            <a href="https://wa.me/628123456789?text=Halo%20Farhana,%20saya%20tertarik%20dengan%20produk%20{{ $product->name }}" 
                               target="_blank"
                               class="block w-full text-center py-5 text-[10px] font-bold uppercase tracking-[0.3em] text-gray-400 hover:text-black transition duration-500">
                                 Inquire via WhatsApp
                            </a>
                        </div>
                    </form>

                    <div class="border-t border-gray-100 pt-10 mt-10">
                        <h3 class="text-[10px] font-bold uppercase mb-6 tracking-[0.3em] text-gray-900">Description</h3>
                        <div class="text-gray-500 text-xs description-text uppercase">
                            {!! nl2br(e($product->description)) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($relatedProducts->count() > 0)
    <div class="max-w-7xl mx-auto px-4 py-24 border-t border-gray-50">
        <div class="text-center mb-16">
            <h3 class="text-[11px] font-bold uppercase tracking-[0.5em] text-gray-900">You May Also Like</h3>
        </div>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-10">
            @foreach($relatedProducts as $related)
                <a href="{{ route('product.details', $related->slug) }}" class="group text-center">
                    <div class="aspect-[3/4] bg-gray-50 overflow-hidden mb-6 relative">
                        @php $relImg = $related->images->where('is_primary', true)->first() ?? $related->images->first(); @endphp
                        <img src="{{ asset('storage/' . ($relImg->image_path ?? '')) }}" class="w-full h-full object-cover transition duration-1000 group-hover:scale-105">
                    </div>
                    <h4 class="text-[10px] font-bold uppercase tracking-widest mb-2">{{ $related->name }}</h4>
                    <p class="text-[10px] text-gray-400 tracking-widest">IDR {{ number_format($related->price, 0, ',', '.') }}</p>
                </a>
            @endforeach
        </div>
    </div>
    @endif

    <div id="sizeGuideModal" class="fixed inset-0 z-[60] hidden bg-black bg-opacity-50 flex items-center justify-center p-4">
        <div class="bg-white max-w-lg w-full p-8 relative shadow-2xl">
            <button onclick="toggleModal('sizeGuideModal')" class="absolute top-4 right-4 text-gray-400 hover:text-black">&times;</button>
            <h2 class="text-[11px] font-bold uppercase tracking-[0.3em] mb-6 text-center">Size Guide</h2>
            <table class="w-full text-[10px] uppercase tracking-widest text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-100 text-black">
                        <th class="py-3 font-bold">Size</th>
                        <th class="py-3 font-bold">Chest (cm)</th>
                        <th class="py-3 font-bold">Length (cm)</th>
                    </tr>
                </thead>
                <tbody class="text-gray-500">
                    <tr class="border-b border-gray-50"><td>S</td><td>96</td><td>135</td></tr>
                    <tr class="border-b border-gray-50"><td>M</td><td>100</td><td>138</td></tr>
                    <tr class="border-b border-gray-50"><td>L</td><td>104</td><td>140</td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <footer class="py-12 border-t border-gray-50 text-center">
        <p class="text-[9px] text-gray-300 uppercase tracking-[0.5em]">&copy; 2026 Farhana Official</p>
    </footer>

    <script>
        const variants = @json($product->variants);

        function filterSizeByColor(color) {
            const colorLower = color.toLowerCase();
            const matchingThumb = document.querySelector(`.thumb-img[data-color="${colorLower}"]`);
            if (matchingThumb) matchingThumb.click();

            // Reset pilihan size & qty
            document.querySelectorAll('.size-radio').forEach(radio => radio.checked = false);
            document.getElementById('qtyInput').value = 1;
            document.getElementById('variant_id_input').value = ''; // Reset ID Variant
            updateStockDisplay();
        }

        function updateStockDisplay() {
            const selectedColor = document.querySelector('input[name="color"]:checked')?.value;
            const selectedSize = document.querySelector('input[name="size"]:checked')?.value;
            const stockDisplay = document.getElementById('stock-count');
            const submitBtn = document.getElementById('mainSubmitBtn');
            const qtyInput = document.getElementById('qtyInput');
            const variantIdInput = document.getElementById('variant_id_input');

            if (selectedColor && selectedSize) {
                const variant = variants.find(v => v.color === selectedColor && v.size === selectedSize);
                
                if (variant) {
                    const stock = variant.stock;
                    variantIdInput.value = variant.id; // SIMPAN ID VARIANT KE HIDDEN INPUT

                    if (stock > 0) {
                        stockDisplay.innerText = `${stock} Pieces Available`;
                        stockDisplay.className = 'text-[9px] uppercase text-gray-400 tracking-widest';
                        qtyInput.max = stock;
                        submitBtn.disabled = false;
                        submitBtn.innerText = "Add to Cart";
                        submitBtn.classList.remove('bg-gray-300', 'cursor-not-allowed');
                        submitBtn.classList.add('bg-black');
                    } else {
                        stockDisplay.innerText = "Out of Stock for this variant";
                        stockDisplay.className = 'text-[9px] uppercase text-red-500 tracking-widest font-bold';
                        submitBtn.disabled = true;
                        submitBtn.innerText = "Sold Out";
                        submitBtn.classList.add('bg-gray-300', 'cursor-not-allowed');
                        submitBtn.classList.remove('bg-black');
                    }
                } else {
                    variantIdInput.value = '';
                    stockDisplay.innerText = "Combination not available";
                    submitBtn.disabled = true;
                }
            }
        }

        function changeImage(imageSrc, element) {
            const mainImage = document.getElementById('mainImage');
            mainImage.style.opacity = '0';
            setTimeout(() => {
                mainImage.src = imageSrc;
                mainImage.style.opacity = '1';
            }, 300);

            document.querySelectorAll('.thumb-img').forEach(el => el.classList.replace('border-black', 'border-transparent'));
            element.classList.replace('border-transparent', 'border-black');
        }

        function incrementQty() {
            const input = document.getElementById('qtyInput');
            const max = parseInt(input.max) || 1;
            if (parseInt(input.value) < max) input.value = parseInt(input.value) + 1;
        }

        function decrementQty() {
            const input = document.getElementById('qtyInput');
            if (parseInt(input.value) > 1) input.value = parseInt(input.value) - 1;
        }

        function toggleModal(id) {
            document.getElementById(id).classList.toggle('hidden');
        }
    </script>
</body>
</html>