<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <title>{{ $product->name }} - Farhana</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        #mainImage { transition: opacity 0.3s ease-in-out; }
        .product-title { letter-spacing: 0.1em; }
        .description-text { line-height: 1.8; letter-spacing: 0.05em; }
        input[type='number']::-webkit-inner-spin-button,
        input[type='number']::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
        
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
        .countdown-box { border: 1px solid #5A5A00; background-color: #fcfcf7; }

        /* Scannability for recommended products */
        .product-card-img { transition: transform 0.7s cubic-bezier(0.4, 0, 0.2, 1); }
        .product-card:hover .product-card-img { transform: scale(1.05); }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="bg-white text-gray-900 font-sans antialiased">
    
    {{-- WhatsApp Floating Button --}}
    <a href="https://wa.me/628123456789?text=Halo%20Farhana,%20saya%20tertarik%20dengan%20produk%20{{ urlencode($product->name) }}" 
       class="whatsapp-float" target="_blank">
        <i class="fab fa-whatsapp"></i>
    </a>

    {{-- Navigation --}}
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

    <main class="max-w-7xl mx-auto px-4 py-8 lg:py-20">
        <div class="flex flex-col lg:flex-row justify-between gap-12">
            
            {{-- SEKSI GAMBAR (SLIDER) --}}
            <div class="lg:w-[50%] space-y-6">
                <div id="swipeArea" class="image-swipe-zone bg-gray-50 overflow-hidden aspect-[3/5] border border-gray-50 relative group">
                    <button type="button" class="nav-btn prev-btn hidden lg:flex items-center justify-center" onclick="prevImage()">&#10094;</button>
                    <img id="mainImage" 
                         src="{{ asset('storage/' . ($product->images->where('is_primary', true)->first()->image_path ?? $product->images->first()->image_path)) }}" 
                         class="w-full h-full object-cover pointer-events-none select-none" 
                         alt="{{ $product->name }}">
                    <button type="button" class="nav-btn next-btn hidden lg:flex items-center justify-center" onclick="nextImage()">&#10095;</button>
                </div>
                
                {{-- Dot indicators for Mobile --}}
                <div class="flex justify-center gap-2 lg:hidden" id="imageDots">
                    @foreach($product->images as $index => $image)
                        <div class="h-1.5 w-1.5 rounded-full dot-item {{ $loop->first ? 'bg-[#5A5A00] w-4' : 'bg-gray-300' }}" data-index="{{ $index }}"></div>
                    @endforeach
                </div>

                {{-- Thumbnails for Desktop --}}
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

            {{-- SEKSI INFO PRODUK --}}
            <div class="lg:w-[55%]">
                <div class="sticky top-32">
                    <div class="flex flex-col gap-2 mb-4">
                        <div class="flex flex-wrap gap-2">
                            @if($product->is_preorder)
                                <span class="px-2 py-0.5 bg-[#5A5A00] text-white text-[9px] font-bold uppercase tracking-widest border border-[#5A5A00]">Pre-Order</span>
                            @endif
                            @if($product->custom_tag)
                                <span class="px-2 py-0.5 bg-gray-100 text-[#5A5A00] text-[9px] font-bold uppercase tracking-widest border border-gray-200">{{ $product->custom_tag }}</span>
                            @endif
                        </div>
                        <p class="text-[10px] text-gray-400 uppercase tracking-[0.4em]">{{ $product->category->name ?? 'Collection' }}</p>
                    </div>

                    <h1 class="text-3xl font-light mb-6 tracking-widest uppercase text-gray-900 leading-snug">{{ $product->name }}</h1>
                    
                    <div class="mb-6">
                        <p id="product-price" data-base-price="{{ $product->price }}" class="text-2xl font-light text-gray-900 tracking-wider">
                            IDR {{ number_format($product->price, 0, ',', '.') }}
                        </p>
                    </div>

                    {{-- COUNTDOWN PRE-ORDER --}}
                    @if($product->is_preorder && $product->release_date)
                        <div id="preorder-countdown" data-expire="{{ $product->release_date }}" class="hidden mb-10 p-5 countdown-box">
                            <p class="text-[9px] uppercase tracking-[0.2em] text-gray-400 mb-3">Pre-Order Ends In:</p>
                            <div class="flex gap-6 text-center">
                                <div><span id="days" class="text-2xl font-light text-[#5A5A00]">00</span><p class="text-[8px] uppercase tracking-widest mt-1">Days</p></div>
                                <div><span id="hours" class="text-2xl font-light text-[#5A5A00]">00</span><p class="text-[8px] uppercase tracking-widest mt-1">Hours</p></div>
                                <div><span id="minutes" class="text-2xl font-light text-[#5A5A00]">00</span><p class="text-[8px] uppercase tracking-widest mt-1">Mins</p></div>
                                <div><span id="seconds" class="text-2xl font-light text-[#5A5A00]">00</span><p class="text-[8px] uppercase tracking-widest mt-1">Secs</p></div>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('cart.add', $product->id) }}" method="POST" id="addToCartForm">
                        @csrf
                        <input type="hidden" name="variant_id" id="variant_id_input" required>

                        @php
                            $uniqueColors = $product->variants->pluck('color')->unique();
                            $sizeOrder = ['XS', 'S', 'M', 'L', 'XL', 'XXL', 'ALL SIZE'];
                            $uniqueSizes = $product->variants->pluck('size')->unique()->sortBy(function($size) use ($sizeOrder) {
                                $pos = array_search(strtoupper($size), $sizeOrder);
                                return $pos !== false ? $pos : 99;
                            });
                        @endphp

                        {{-- Warna --}}
                        @if($uniqueColors->count() > 0)
                        <div class="mb-8">
                            <h3 class="text-[10px] font-bold uppercase tracking-[0.2em] mb-4 text-gray-900">Select Color</h3>
                            <div class="flex flex-wrap gap-3">
                                @foreach($uniqueColors as $color)
                                    <label class="cursor-pointer">
                                        <input type="radio" name="color" value="{{ $color }}" class="hidden peer color-radio" onchange="filterSizeByColor('{{ $color }}')" required>
                                        <span class="px-5 py-2 border border-gray-200 text-[10px] uppercase tracking-widest peer-checked:border-[#5A5A00] peer-checked:bg-[#5A5A00] peer-checked:text-white hover:border-[#5A5A00] transition block text-center">
                                            {{ $color }}
                                        </span> 
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        {{-- Ukuran & Size Guide Trigger --}}
                        <div class="mb-8">
                            <div class="flex items-center w-full mb-4">
                                <h3 class="text-[10px] font-bold uppercase tracking-[0.2em] text-gray-900">Select Size</h3>
                                @if($product->sizeGuide)
                                <button type="button" onclick="toggleModal('sizeGuideModal')" 
                                    class="ml-auto text-[9px] uppercase tracking-widest border-b border-gray-300 pb-0.5 text-gray-400 hover:text-black transition">
                                    Size Guide
                                </button>
                                @endif
                            </div>
                            <div class="flex flex-wrap gap-3" id="size-container">
                                @foreach($uniqueSizes as $size)
                                    <label class="cursor-pointer size-option" data-size="{{ $size }}">
                                        <input type="radio" name="size" value="{{ $size }}" class="hidden peer size-radio" onchange="updateStockDisplay()" required>
                                        <span class="min-w-12 h-12 px-3 flex items-center justify-center border border-gray-200 text-[10px] peer-checked:border-[#5A5A00] peer-checked:bg-[#5A5A00] peer-checked:text-white hover:border-[#5A5A00] transition">
                                            {{ $size }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- Quantity --}}
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

                        {{-- Action Buttons --}}
                        <div class="space-y-4">
                            <button type="submit" id="mainSubmitBtn" class="w-full bg-white border border-gray-300 text-black py-5 text-[10px] font-bold uppercase tracking-[0.3em] hover:bg-gray-100 transition duration-500">
                                {{ $product->is_preorder ? 'Pre-Order Now' : 'Add to Cart' }}
                            </button>
                            <button type="button" onclick="buyNow()" class="w-full py-5 text-[10px] font-bold uppercase tracking-[0.3em] text-white transition duration-500" style="background-color:#5A5A00;">
                                Buy It Now
                            </button>
                        </div>
                    </form>

                    {{-- Description --}}
                    <div class="border-t border-gray-100 pt-10 mt-10">
                        <h3 class="text-[10px] font-bold uppercase mb-6 tracking-[0.3em] text-gray-900">Description</h3>
                        <div class="text-gray-500 text-xs description-text uppercase">
                            {!! nl2br(e($product->description)) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- SEKSI REKOMENDASI PRODUK --}}
        @if(isset($relatedProducts) && $relatedProducts->count() > 0)
        <div class="mt-32 border-t border-gray-50 pt-20">
            <h2 class="text-xl font-light tracking-[0.5em] uppercase text-center mb-16">You May Also Like</h2>
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-8">
                @foreach($relatedProducts as $related)
                <a href="{{ route('product.details', $related->slug) }}" class="group product-card">
                    <div class="aspect-[3/4] bg-gray-50 overflow-hidden mb-4 relative">
                        <img src="{{ asset('storage/' . ($related->images->where('is_primary', true)->first()->image_path ?? $related->images->first()->image_path)) }}" 
                             alt="{{ $related->name }}" 
                             class="w-full h-full object-cover product-card-img">
                        
                        @if($related->is_preorder)
                            <span class="absolute top-3 left-3 px-2 py-0.5 bg-[#5A5A00] text-white text-[8px] font-bold uppercase tracking-widest">Pre-Order</span>
                        @endif
                    </div>
                    <div class="text-center px-2">
                        <h3 class="text-[10px] tracking-[0.2em] uppercase text-gray-900 mb-1 group-hover:text-[#5A5A00] transition">{{ $related->name }}</h3>
                        <p class="text-[10px] text-gray-400 tracking-wider">IDR {{ number_format($related->price, 0, ',', '.') }}</p>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif
    </main>

    {{-- FOOTER (SESUAI WELCOME.BLADE) --}}
    <footer id="about" class="py-16 bg-[#5A5A00] text-white mt-20">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-16 text-left">
                <div>
                    <h4 class="text-[10px] font-bold tracking-[0.3em] uppercase mb-6">About Farhana</h4>
                    <p class="text-[11px] text-white/80 leading-loose tracking-widest uppercase">
                        Eksklusivitas dalam balutan kesantunan. Kami menghadirkan kualitas terbaik untuk gaya Muslim modern yang elegan dan berkelas.
                    </p>
                </div>

                <div>
                    <h4 class="text-[10px] font-bold tracking-[0.3em] uppercase mb-6">Customer Care</h4>
                    <ul class="space-y-3 text-[11px] tracking-widest uppercase text-white/80">
                        <li><a href="#" class="hover:text-black transition">Contact Us</a></li>
                        <li><a href="#" class="hover:text-black transition">Shipping & Returns</a></li>
                        <li><a href="#" class="hover:text-black transition">How to Buy</a></li>
                        <li><a href="#" class="hover:text-black transition">FAQs</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-[10px] font-bold tracking-[0.3em] uppercase mb-6">Follow Us</h4>
                    <div class="flex space-x-4">
                        <a href="#" class="w-9 h-9 rounded-full bg-white flex items-center justify-center text-[#5A5A00] hover:bg-gray-200 transition">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                            </svg>
                        </a>
                        <span class="self-center text-[10px] tracking-widest uppercase">@farhana.official</span>
                    </div>
                </div>
            </div>

            <div class="mt-20 pt-8 border-t border-white/10 text-center">
                <p class="text-[9px] tracking-[0.4em] text-white/40 uppercase">
                    &copy; 2026 Farhana Official. All Rights Reserved.
                </p>
            </div>
        </div>
    </footer>

    {{-- MODAL SIZE GUIDE --}}
    @if($product->sizeGuide)
    <div id="sizeGuideModal" class="fixed inset-0 z-[60] hidden bg-black/60 flex items-center justify-center p-4 backdrop-blur-sm">
        <div class="bg-white max-w-2xl w-full p-2 relative shadow-2xl overflow-y-auto max-h-[95vh] animate-fade-in-up">
            <button onclick="toggleModal('sizeGuideModal')" class="absolute top-4 right-4 z-10 bg-white/80 rounded-full w-8 h-8 flex items-center justify-center text-black text-xl shadow-sm hover:bg-black hover:text-white transition">&times;</button>
            <div class="p-4">
                <h2 class="text-[11px] font-bold uppercase tracking-[0.3em] mb-4 text-center">Panduan Ukuran: {{ $product->sizeGuide->name }}</h2>
                <img src="{{ asset('storage/' . $product->sizeGuide->image) }}" class="w-full h-auto object-contain" alt="Panduan Ukuran">
                <div class="mt-6 text-center italic text-gray-400 text-[9px] uppercase tracking-widest">
                    * Toleransi ukuran 1-3 cm karena proses produksi massal
                </div>
            </div>
        </div>
    </div>
    @endif

    <script>
        const variants = @json($product->variants);
        const images = @json($product->images->values()->map(fn($img) => asset('storage/' . $img->image_path)));
        let currentIndex = 0;

        // --- SWIPE LOGIC ---
        let touchstartX = 0;
        let touchendX = 0;
        const swipeArea = document.getElementById('swipeArea');

        swipeArea.addEventListener('touchstart', e => { touchstartX = e.changedTouches[0].screenX; }, {passive: true});
        swipeArea.addEventListener('touchend', e => { touchendX = e.changedTouches[0].screenX; handleSwipe(); }, {passive: true});

        function handleSwipe() {
            const threshold = 50; 
            if (touchendX < touchstartX - threshold) nextImage(); 
            if (touchendX > touchstartX + threshold) prevImage(); 
        }

        function updateDots() {
            const dots = document.querySelectorAll('.dot-item');
            dots.forEach((dot, index) => {
                if (index === currentIndex) {
                    dot.classList.add('bg-[#5A5A00]', 'w-4');
                    dot.classList.remove('bg-gray-300');
                } else {
                    dot.classList.remove('bg-[#5A5A00]', 'w-4');
                    dot.classList.add('bg-gray-300');
                }
            });
        }

        function initCountdown() {
            const countdownEl = document.getElementById('preorder-countdown');
            if (!countdownEl) return;
            const expireDate = countdownEl.getAttribute('data-expire');
            const target = new Date(expireDate).getTime();
            const timer = setInterval(() => {
                const now = new Date().getTime();
                const distance = target - now;
                if (distance < 0) {
                    clearInterval(timer);
                    countdownEl.innerHTML = "<p class='text-[10px] uppercase tracking-widest text-red-500 font-bold'>Pre-Order Closed</p>";
                    return;
                }
                countdownEl.classList.remove('hidden');
                document.getElementById('days').innerText = Math.floor(distance / (1000 * 60 * 60 * 24)).toString().padStart(2, '0');
                document.getElementById('hours').innerText = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)).toString().padStart(2, '0');
                document.getElementById('minutes').innerText = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60)).toString().padStart(2, '0');
                document.getElementById('seconds').innerText = Math.floor((distance % (1000 * 60)) / 1000).toString().padStart(2, '0');
            }, 1000);
        }

        function filterSizeByColor(color) {
            const colorLower = color.toLowerCase().trim();
            const matchingThumb = document.querySelector(`.thumb-img[data-color="${colorLower}"]`);
            if (matchingThumb) {
                const imgPath = matchingThumb.querySelector('img').src;
                changeImage(imgPath, matchingThumb);
            }
            document.querySelectorAll('.size-radio').forEach(radio => radio.checked = false);
            document.getElementById('variant_id_input').value = ''; 
            updateStockDisplay();
        }

        function updateStockDisplay() {
            const selectedColor = document.querySelector('input[name="color"]:checked')?.value;
            const selectedSize = document.querySelector('input[name="size"]:checked')?.value;
            const stockDisplay = document.getElementById('stock-count');
            const submitBtn = document.getElementById('mainSubmitBtn');
            const variantIdInput = document.getElementById('variant_id_input');

            if (selectedColor && selectedSize) {
                const variant = variants.find(v => v.color === selectedColor && v.size === selectedSize);
                if (variant) {
                    variantIdInput.value = variant.id; 
                    const stock = parseInt(variant.stock);
                    if (stock > 0) {
                        stockDisplay.innerText = `${stock} Pieces Available`;
                        stockDisplay.className = 'text-[9px] uppercase text-gray-400 tracking-widest';
                        submitBtn.disabled = false;
                        submitBtn.innerText = "{{ $product->is_preorder ? 'Pre-Order Now' : 'Add to Cart' }}";
                        submitBtn.style.opacity = "1";
                    } else {
                        stockDisplay.innerText = "Out of Stock";
                        stockDisplay.className = 'text-[9px] uppercase text-red-400 tracking-widest';
                        submitBtn.disabled = true;
                        submitBtn.innerText = "Sold Out";
                        submitBtn.style.opacity = "0.5";
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
                updateDots(); 
            }, 300);
            document.querySelectorAll('.thumb-img').forEach(el => {
                el.classList.remove('border-black');
                el.classList.add('border-transparent');
            });
            if(element) {
                element.classList.add('border-black');
                currentIndex = parseInt(element.getAttribute('data-index'));
            }
        }

        function nextImage() {
            currentIndex = (currentIndex + 1) % images.length;
            const nextPath = images[currentIndex];
            const thumb = document.querySelector(`.thumb-img[data-index="${currentIndex}"]`);
            changeImage(nextPath, thumb);
        }

        function prevImage() {
            currentIndex = (currentIndex - 1 + images.length) % images.length;
            const prevPath = images[currentIndex];
            const thumb = document.querySelector(`.thumb-img[data-index="${currentIndex}"]`);
            changeImage(prevPath, thumb);
        }

        function incrementQty() {
            const input = document.getElementById('qtyInput');
            input.value = parseInt(input.value) + 1;
        }

        function decrementQty() {
            const input = document.getElementById('qtyInput');
            if (parseInt(input.value) > 1) input.value = parseInt(input.value) - 1;
        }

        function toggleModal(id) {
            const modal = document.getElementById(id);
            if (!modal) return;
            if (modal.classList.contains('hidden')) {
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            } else {
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }
        }

        function buyNow() {
            const form = document.getElementById('addToCartForm');
            if(form.reportValidity()) form.submit();
        }

        document.addEventListener('DOMContentLoaded', initCountdown);
    </script>
</body>
</html>