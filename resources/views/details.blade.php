<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <title>{{ $product->name }} - Farhana Web</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        #mainImage { transition: opacity 0.3s ease-in-out; }
        .product-title { letter-spacing: 0.1em; }
        .description-text { line-height: 1.8; letter-spacing: 0.05em; }
        input[type='number']::-webkit-inner-spin-button,
        input[type='number']::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
        .sold-out-overlay { background: rgba(255, 255, 255, 0.7); pointer-events: none; }

        .whatsapp-float {
            position: fixed; width: 50px; height: 50px; bottom: 30px; right: 30px;
            background-color: #5A5A00; color: #FFF; border-radius: 50px;
            text-align: center; font-size: 24px; box-shadow: 2px 2px 15px rgba(0,0,0,0.2);
            z-index: 100; display: flex; align-items: center; justify-content: center;
            transition: all 0.3s ease;
        }
        .whatsapp-float:hover { transform: scale(1.1); background-color: #3E3E00; }

        .nav-btn {
            position: absolute; top: 50%; transform: translateY(-50%);
            background-color: rgba(255, 255, 255, 0.3); color: #5A5A00;
            border: 1px solid #5A5A00; width: 40px; height: 40px; border-radius: 50%;
            font-size: 20px; cursor: pointer; z-index: 20; transition: all 0.3s ease;
        }
        .nav-btn:hover { background-color: #5A5A00; color: white; }
        .prev-btn { left: 15px; }
        .next-btn { right: 15px; }
        .dot-item { transition: all 0.3s ease; }
        .image-swipe-zone { touch-action: pan-y; position: relative; overflow: hidden; }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="bg-white text-gray-900 font-sans antialiased">
    
    <a href="https://wa.me/628123456789?text=Halo%20Farhana,%20saya%20tertarik%20dengan%20produk%20{{ urlencode($product->name) }}" 
       class="whatsapp-float" target="_blank">
        <i class="fab fa-whatsapp"></i>
    </a>

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
    </nav>

    <div class="max-w-7xl mx-auto px-4 py-8 lg:py-20">
        <div class="flex flex-col lg:flex-row justify-between gap-12">
            
            {{-- Galeri Foto --}}
            <div class="lg:w-[50%] space-y-6">
                <div id="swipeArea" class="image-swipe-zone bg-gray-50 overflow-hidden aspect-[3/5] border border-gray-50 relative group">
                    <button type="button" class="nav-btn prev-btn hidden lg:flex items-center justify-center" onclick="prevImage()">&#10094;</button>
                    <img id="mainImage" 
                         src="{{ asset('storage/' . ($product->images->where('is_primary', true)->first()->image_path ?? $product->images->first()->image_path)) }}" 
                         class="w-full h-full object-cover pointer-events-none select-none" 
                         alt="{{ $product->name }}">
                    <button type="button" class="nav-btn next-btn hidden lg:flex items-center justify-center" onclick="nextImage()">&#10095;</button>
                </div>
                
                <div class="flex justify-center gap-2 lg:hidden" id="imageDots">
                    @foreach($product->images as $index => $image)
                        <div class="h-1.5 w-1.5 rounded-full dot-item {{ $loop->first ? 'bg-[#5A5A00] w-4' : 'bg-gray-300' }}" data-index="{{ $index }}"></div>
                    @endforeach
                </div>

                <div class="hidden lg:grid grid-cols-6 gap-3">
                    @foreach($product->images as $index => $image)
                        <div class="cursor-pointer border-b-2 {{ $loop->first ? 'border-black' : 'border-transparent' }} hover:border-black transition pb-2 thumb-img"
                             data-index="{{ $index }}" data-color="{{ strtolower(trim($image->color)) }}"
                             onclick="changeImage('{{ asset('storage/' . $image->image_path) }}', this)">
                            <div class="aspect-square bg-gray-50 overflow-hidden">
                                <img src="{{ asset('storage/' . $image->image_path) }}" class="w-full h-full object-cover">
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Detail Produk --}}
            <div class="lg:w-[55%]">
                <div class="sticky top-32">
                    <div class="flex flex-wrap gap-2 mb-4">
                        <p class="text-[10px] text-gray-400 uppercase tracking-[0.4em]">
                            {{ $product->category->name ?? 'Collection' }}
                        </p>
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
                                        <input type="radio" name="color" value="{{ $color }}" class="hidden peer color-radio" onchange="filterSizeByColor('{{ $color }}')" required>
                                        <span class="px-5 py-2 border border-gray-200 text-[10px] uppercase tracking-widest peer-checked:border-[#5A5A00] peer-checked:bg-[#5A5A00] peer-checked:text-white hover:border-[#5A5A00] transition block">
                                            {{ $color }}
                                        </span> 
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <div class="mb-8">
                            <div class="flex items-center w-full mb-4">
                                <h3 class="text-[10px] font-bold uppercase tracking-[0.2em] text-gray-900">Select Size</h3>
                                <button type="button" onclick="toggleModal('sizeGuideModal')" 
                                    class="ml-auto text-[9px] uppercase tracking-widest border-b border-gray-300 pb-0.5 text-gray-400 hover:text-black transition">
                                    Size Guide
                                </button>
                            </div>
                            <div class="flex flex-wrap gap-3" id="size-container">
                                @foreach($uniqueSizes as $size)
                                    <label class="cursor-pointer size-option" data-size="{{ $size }}">
                                        <input type="radio" name="size" value="{{ $size }}" class="hidden peer size-radio" onchange="updateStockDisplay()" required>
                                        <span class="w-12 h-12 flex items-center justify-center border border-gray-200 text-[10px] peer-checked:border-[#5A5A00] peer-checked:bg-[#5A5A00] peer-checked:text-white hover:border-[#5A5A00] transition">
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
                            <button type="submit" id="mainSubmitBtn" class="w-full bg-white border border-gray-300 text-black py-5 text-[10px] font-bold uppercase tracking-[0.3em] hover:bg-gray-100 transition duration-500">
                                Add to Cart
                            </button>
                            <button type="button" onclick="document.getElementById('addToCartForm').submit()" class="w-full py-5 text-[10px] font-bold uppercase tracking-[0.3em] text-white transition duration-500" style="background-color:#5A5A00;">
                                Buy It Now
                            </button>
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

    {{-- SMART MODAL SIZE GUIDE (Sesuai Katalog) --}}
    <div id="sizeGuideModal" class="fixed inset-0 z-[60] hidden bg-black bg-opacity-50 flex items-center justify-center p-4">
        <div class="bg-white max-w-xl w-full p-6 md:p-8 relative shadow-2xl overflow-y-auto max-h-[90vh]">
            <button onclick="toggleModal('sizeGuideModal')" class="absolute top-4 right-4 text-gray-400 hover:text-black text-2xl">&times;</button>
            
            <h2 class="text-[11px] font-bold uppercase tracking-[0.3em] mb-2 text-center">Size Guide</h2>
            <p class="text-[10px] text-center text-gray-400 uppercase tracking-widest mb-8">{{ $product->name }}</p>
            
            <div class="space-y-10">
                @php
                    $name = strtolower($product->name);
                    $category = strtolower($product->category->name ?? '');
                @endphp

                {{-- LOGIKA 1: ABAYA ADULT (AMEERA & LYRA) --}}
                @if((Str::contains($name, 'lyra') && !Str::contains($name, 'kids')) || Str::contains($name, 'ameera'))
                    <div>
                        <h3 class="text-[10px] font-bold uppercase tracking-widest mb-4 text-[#5A5A00]">
                            {{ Str::contains($name, 'ameera') ? 'Ameera' : 'Lyra' }} Collection Details
                        </h3>
                        <div class="overflow-x-auto">
                            <table class="w-full text-[10px] uppercase tracking-widest text-left border-collapse">
                                <thead>
                                    <tr class="border-b border-gray-100 text-black text-center">
                                        <th class="py-2 text-left">Size</th>
                                        <th class="py-2 font-bold">S</th>
                                        <th class="py-2 font-bold">M</th>
                                        <th class="py-2 font-bold">L</th>
                                        <th class="py-2 font-bold">XL</th>
                                    </tr>
                                </thead>
                                <tbody class="text-gray-500 text-center">
                                    <tr class="border-b border-gray-50">
                                        <td class="py-3 font-bold text-black text-left">Bust (LD)</td>
                                        <td class="py-3">104</td><td class="py-3">108</td><td class="py-3">115</td><td class="py-3">125</td>
                                    </tr>
                                    <tr class="border-b border-gray-50">
                                        <td class="py-3 font-bold text-black text-left">Length (PB)</td>
                                        <td class="py-3">130</td><td class="py-3">135</td><td class="py-3">140</td><td class="py-3">145</td>
                                    </tr>
                                    <tr class="border-b border-gray-50">
                                        <td class="py-3 font-bold text-black text-left">Sleeve (PT)</td>
                                        <td class="py-3">56</td><td class="py-3">58</td><td class="py-3">60</td><td class="py-3">62</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <p class="mt-3 text-[9px] text-gray-400 italic leading-relaxed">
                            * Shrinkage of +/- 2 cm for each size after the first wash
                        </p>
                    </div>

                {{-- LOGIKA 2: LYRA KIDS --}}
                @elseif(Str::contains($name, 'kids'))
                    <div>
                        <h3 class="text-[10px] font-bold uppercase tracking-widest mb-4 text-[#5A5A00]">Lyra Kids Size Chart</h3>
                        <div class="overflow-x-auto">
                            <table class="w-full text-[10px] uppercase tracking-widest text-center border-collapse">
                                <thead>
                                    <tr class="border-b border-gray-100 text-black">
                                        <th class="py-2 text-left italic">Age</th>
                                        <th class="py-2">3-4</th>
                                        <th class="py-2">5-6</th>
                                        <th class="py-2">7-8</th>
                                        <th class="py-2">9-10</th>
                                        <th class="py-2">11-12</th>
                                    </tr>
                                </thead>
                                <tbody class="text-gray-500">
                                    <tr class="border-b border-gray-50">
                                        <td class="py-3 font-bold text-black text-left">Bust (LD)</td>
                                        <td>68</td><td>80</td><td>80</td><td>90</td><td>96</td>
                                    </tr>
                                    <tr class="border-b border-gray-50">
                                        <td class="py-3 font-bold text-black text-left">Length (PB)</td>
                                        <td>70</td><td>100</td><td>110</td><td>115</td><td>120</td>
                                    </tr>
                                    <tr class="border-b border-gray-50">
                                        <td class="py-3 font-bold text-black text-left">Sleeve (PT)</td>
                                        <td>25</td><td>48</td><td>48</td><td>50</td><td>52</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                {{-- LOGIKA 3: KHIMAR & NIQAB --}}
                @elseif(Str::contains($category, 'khimar') || Str::contains($category, 'niqab') || Str::contains($name, 'khimar'))
                    <div class="space-y-8">
                        <div>
                            <h3 class="text-[10px] font-bold uppercase tracking-widest mb-3 text-[#5A5A00]">Khimar</h3>
                            <table class="w-full text-[10px] uppercase tracking-widest text-left border-collapse">
                                <tbody class="text-gray-500">
                                    <tr class="border-b border-gray-50"><td class="py-3 font-bold text-black">Back Length</td><td class="py-3 text-right">133 cm</td></tr>
                                    <tr class="border-b border-gray-50"><td class="py-3 font-bold text-black">Front Length</td><td class="py-3 text-right">104 cm</td></tr>
                                    <tr class="border-b border-gray-50"><td class="py-3 font-bold text-black">Face Opening</td><td class="py-3 text-right">31 cm</td></tr>
                                </tbody>
                            </table>
                        </div>
                        <div>
                            <h3 class="text-[10px] font-bold uppercase tracking-widest mb-3 text-[#5A5A00]">Niqab</h3>
                            <table class="w-full text-[10px] uppercase tracking-widest text-left border-collapse">
                                <tbody class="text-gray-500">
                                    <tr class="border-b border-gray-50"><td class="py-3 font-bold text-black">Niqab Length</td><td class="py-3 text-right">29 cm</td></tr>
                                    <tr class="border-b border-gray-50"><td class="py-3 font-bold text-black">Bottom Width</td><td class="py-3 text-right">29 cm</td></tr>
                                    <tr class="border-b border-gray-50"><td class="py-3 font-bold text-black">Top Width</td><td class="py-3 text-right">44 cm</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                {{-- LOGIKA 4: DEFAULT --}}
                @else
                    <div class="text-center py-10">
                        <p class="text-[10px] text-gray-400 uppercase tracking-widest italic leading-relaxed">
                            Specific size details are available in the product description.
                        </p>
                    </div>
                @endif
            </div>

            <div class="mt-8 pt-4 border-t border-gray-50">
                <p class="text-[9px] text-gray-400 italic text-center">Size tolerance: ±1–3 cm</p>
            </div>
        </div>
    </div>

    <footer class="py-12 border-t border-gray-50 text-center">
        <p class="text-[9px] text-gray-300 uppercase tracking-[0.5em]">&copy; 2026 Farhana Official</p>
    </footer>

    <script>
        const variants = @json($product->variants);
        const images = @json($product->images->map(fn($img) => asset('storage/' . $img->image_path)));
        let currentIndex = 0;

        // Swipe Functionality
        let touchstartX = 0;
        let touchendX = 0;
        const swipeArea = document.getElementById('swipeArea');

        swipeArea.addEventListener('touchstart', e => { touchstartX = e.changedTouches[0].screenX; }, {passive: true});
        swipeArea.addEventListener('touchend', e => { touchendX = e.changedTouches[0].screenX; handleGesture(); }, {passive: true});

        function handleGesture() {
            const threshold = 50; 
            if (touchendX < touchstartX - threshold) nextImage(); 
            if (touchendX > touchstartX + threshold) prevImage(); 
        }

        function filterSizeByColor(color) {
            const colorLower = color.toLowerCase().trim();
            const matchingThumb = document.querySelector(`.thumb-img[data-color="${colorLower}"]`);
            if (matchingThumb) matchingThumb.click();

            document.querySelectorAll('.size-radio').forEach(radio => radio.checked = false);
            document.getElementById('qtyInput').value = 1;
            document.getElementById('variant_id_input').value = ''; 
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
                    variantIdInput.value = variant.id; 
                    if (stock > 0) {
                        stockDisplay.innerText = `${stock} Pieces Available`;
                        stockDisplay.className = 'text-[9px] uppercase text-gray-400 tracking-widest';
                        qtyInput.max = stock;
                        submitBtn.disabled = false;
                        submitBtn.innerText = "Add to Cart";
                    } else {
                        stockDisplay.innerText = "Out of Stock";
                        submitBtn.disabled = true;
                        submitBtn.innerText = "Sold Out";
                    }
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

            document.querySelectorAll('.thumb-img').forEach(el => {
                el.classList.remove('border-black');
                el.classList.add('border-transparent');
            });

            if(element) {
                element.classList.remove('border-transparent');
                element.classList.add('border-black');
                currentIndex = parseInt(element.getAttribute('data-index'));
            }
            updateDots(currentIndex);
        }

        function updateDots(index) {
            const dots = document.querySelectorAll('.dot-item');
            dots.forEach((dot, idx) => {
                if(idx === index) {
                    dot.classList.add('bg-[#5A5A00]', 'w-4');
                    dot.classList.remove('bg-gray-300');
                } else {
                    dot.classList.remove('bg-[#5A5A00]', 'w-4');
                    dot.classList.add('bg-gray-300');
                }
            });
        }

        function nextImage() {
            currentIndex = (currentIndex + 1) % images.length;
            const targetThumb = document.querySelector(`.thumb-img[data-index="${currentIndex}"]`);
            changeImage(images[currentIndex], targetThumb);
        }

        function prevImage() {
            currentIndex = (currentIndex - 1 + images.length) % images.length;
            const targetThumb = document.querySelector(`.thumb-img[data-index="${currentIndex}"]`);
            changeImage(images[currentIndex], targetThumb);
        }

        function incrementQty() {
            const input = document.getElementById('qtyInput');
            const max = parseInt(input.max) || 100;
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