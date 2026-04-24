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

        .product-card-img { transition: transform 0.7s cubic-bezier(0.4, 0, 0.2, 1); }
        .product-card:hover .product-card-img { transform: scale(1.05); }

        .size-option.disabled, .color-option.disabled {
            opacity: 0.35;
            cursor: not-allowed;
            background-color: #f3f4f6;
            text-decoration: line-through;
            pointer-events: none;
        }

        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }

        /* Notification Animation */
        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .error-notif { animation: fadeInDown 0.4s ease forwards; }
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
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="group flex items-center text-[9px] tracking-[0.3em] uppercase text-gray-400 hover:text-[#5A5A00] transition-all duration-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-3 transform group-hover:-translate-x-1 transition-transform duration-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M15 19l-7-7 7-7" />
                    </svg>
                    <span class="font-light">Back to Collection</span>
                </a>
            </div>

            <div class="flex-shrink-0">
                <a href="{{ route('home') }}" class="text-1xl font-light tracking-[0.4em] uppercase">Farhana</a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-black text-white text-[10px] tracking-[0.2em] uppercase py-3 text-center">
            {{ session('success') }}
        </div>
    @endif
</nav>

    <main class="max-w-7xl mx-auto px-4 py-8 lg:py-20">
        <div class="flex flex-col lg:flex-row justify-between gap-12">
            
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

            <div class="lg:w-[55%]">
                <div class="sticky top-32">
                    <div class="flex flex-col items-start gap-2 mb-4">
                        <div class="flex flex-wrap gap-2">
                            @if($product->is_preorder)
                                <span class="px-2 py-0.5 bg-[#5A5A00] text-white text-[9px] font-bold uppercase tracking-widest">Pre-Order</span>
                            @endif
                            @if($product->custom_tag)
                                <span class="px-2 py-0.5 bg-gray-100 text-[#5A5A00] text-[9px] font-bold uppercase tracking-widest border border-gray-200">{{ $product->custom_tag }}</span>
                            @endif
                        </div>
                        <span class="inline-block w-fit bg-[#5A5A00]/80 text-white text-[9px] font-bold uppercase tracking-[0.3em] px-4 py-1.5 rounded-full">
                            {{ $product->category->name ?? 'Collection' }}
                        </span>
                    </div>

                    <h1 class="text-2xl font-light mb-6 tracking-widest uppercase text-gray-900 leading-snug">{{ $product->name }}</h1>
                    
                    <div class="mb-6">
                        <p class="text-1xl font-light text-gray-900 tracking-wider">
                            IDR {{ number_format($product->price, 0, ',', '.') }}
                        </p>
                    </div>

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
                        <input type="hidden" name="variant_id" id="variant_id_input">

                        @php
                            $uniqueColors = $product->variants->pluck('color')->unique();
                            $sizeOrder = ['XS', 'S', 'M', 'L', 'XL', 'XXL', 'ALL SIZE'];
                            $uniqueSizes = $product->variants->pluck('size')->unique()->sortBy(function($size) use ($sizeOrder) {
                                $pos = array_search(strtoupper($size), $sizeOrder);
                                return $pos !== false ? $pos : 99;
                            });
                        @endphp

                        @if($uniqueColors->count() > 0)
                        <div class="mb-8">
                            <h3 class="text-[10px] font-bold uppercase tracking-[0.2em] mb-4 text-gray-900">Select Color</h3>
                            <div class="flex flex-wrap gap-3">
                                @foreach($uniqueColors as $color)
                                    <label class="cursor-pointer color-option">
                                        <input type="radio" name="color" value="{{ $color }}" class="hidden peer" onchange="filterSizeByColor('{{ $color }}')">
                                        <span class="px-5 py-2 border border-gray-200 text-[10px] uppercase tracking-widest peer-checked:border-[#5A5A00] peer-checked:bg-[#5A5A00] peer-checked:text-white hover:border-[#5A5A00] transition block text-center">
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
                                @if($product->sizeGuide)
                                <button type="button" onclick="toggleModal('sizeGuideModal')" class="ml-auto text-[9px] uppercase tracking-widest border-b border-gray-300 pb-0.5 text-gray-400 hover:text-black transition">Size Guide</button>
                                @endif
                            </div>
                            <div class="flex flex-wrap gap-3" id="size-container">
                                @foreach($uniqueSizes as $size)
                                    <label class="cursor-pointer size-option" data-size="{{ $size }}">
                                        <input type="radio" name="size" value="{{ $size }}" class="hidden peer" onchange="updateStockDisplay()">
                                        <span class="min-w-12 h-12 px-3 flex items-center justify-center border border-gray-200 text-[10px] peer-checked:border-[#5A5A00] peer-checked:bg-[#5A5A00] peer-checked:text-white hover:border-[#5A5A00] transition">
                                            {{ $size }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="mb-10">
                            <div class="flex justify-between mb-4">
                                <h3 class="text-[10px] font-bold uppercase tracking-[0.2em] text-gray-900">Quantity</h3>
                                <span id="stock-count" class="text-[9px] uppercase text-gray-400 tracking-widest">Select color & size</span>
                            </div>
                            <div class="flex items-center border border-gray-200 w-32">
                                <button type="button" onclick="decrementQty()" class="px-4 py-2 hover:bg-gray-50 transition text-gray-400">-</button>
                                <input type="number" id="qtyInput" name="quantity" value="1" min="1" class="w-full text-center border-none text-[11px] focus:ring-0" readonly>
                                <button type="button" onclick="incrementQty()" class="px-4 py-2 hover:bg-gray-50 transition text-gray-400">+</button>
                            </div>
                        </div>

                        <div id="validation-msg" class="hidden mb-4 text-[10px] text-red-500 font-bold uppercase tracking-widest error-notif"></div>

                        <div class="space-y-4">
                            <button type="button" onclick="handleFormSubmit('add')" class="w-full bg-white border border-gray-300 text-black py-3 text-[10px] font-bold uppercase tracking-[0.3em] hover:bg-gray-100 transition duration-500">
                                {{ $product->is_preorder ? 'Pre-Order Now' : 'Add to Cart' }}
                            </button>
                            <button type="button" onclick="handleFormSubmit('buy')" class="w-full py-3 text-[10px] font-bold uppercase tracking-[0.3em] text-white transition duration-500" style="background-color:#5A5A00;">
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

        @if(isset($relatedProducts) && $relatedProducts->count() > 0)
        <div class="mt-32 border-t border-gray-50 pt-20 relative group/slider">
            <h2 class="text-xl font-medium tracking-[0.5em] uppercase text-center mb-16">You May Also Like</h2>
            <div id="scroll-container" class="overflow-x-auto scrollbar-hide scroll-smooth">
                <div class="flex flex-nowrap lg:grid lg:grid-cols-5 gap-6 lg:gap-10 min-w-max lg:min-w-full pb-4">
                    @foreach($relatedProducts as $related)
                    <a href="{{ route('product.details', $related->slug) }}" class="group product-card block text-center w-[180px] lg:w-auto flex-none">
                        <div class="aspect-[2/3] bg-gray-50 overflow-hidden mb-4 relative border border-gray-50">
                            <img src="{{ asset('storage/' . ($related->images->where('is_primary', true)->first()->image_path ?? $related->images->first()->image_path)) }}" 
                                 alt="{{ $related->name }}" 
                                 class="w-full h-full object-cover product-card-img">
                        </div>
                        <h3 class="text-[9px] lg:text-[10px] tracking-[0.2em] uppercase text-gray-900 mb-1">{{ $related->name }}</h3>
                        <p class="text-[9px] text-gray-400 tracking-wider">IDR {{ number_format($related->price, 0, ',', '.') }}</p>
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </main>

<footer id="about" class="py-16 bg-[#5A5A00] text-white">
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

    @if($product->sizeGuide)
    <div id="sizeGuideModal" class="fixed inset-0 z-[60] hidden bg-black/60 flex items-center justify-center p-4 backdrop-blur-sm">
        <div class="bg-white max-w-2xl w-full p-2 relative shadow-2xl overflow-y-auto max-h-[95vh]">
            <button onclick="toggleModal('sizeGuideModal')" class="absolute top-4 right-4 text-black text-xl">&times;</button>
            <div class="p-4">
                <h2 class="text-[11px] font-bold uppercase tracking-[0.3em] mb-4 text-center">Panduan Ukuran</h2>
                <img src="{{ asset('storage/' . $product->sizeGuide->image) }}" class="w-full h-auto" alt="Size Guide">
            </div>
        </div>
    </div>
    @endif

    <script>
        const variants = @json($product->variants);
        const images = @json($product->images->values()->map(fn($img) => asset('storage/' . $img->image_path)));
        let currentIndex = 0;

        // NEW: Form Validation Handler
        function handleFormSubmit(type) {
            const color = document.querySelector('input[name="color"]:checked');
            const size = document.querySelector('input[name="size"]:checked');
            const notif = document.getElementById('validation-msg');

            notif.classList.add('hidden');
            notif.innerText = '';

            if (!color) {
                notif.innerText = "Please select a color first.";
                notif.classList.remove('hidden');
                return;
            }

            if (!size) {
                notif.innerText = "Please select your size.";
                notif.classList.remove('hidden');
                return;
            }

            // Jika semua oke, lanjut submit
            const form = document.getElementById('addToCartForm');
            if (type === 'buy') {
                // Kamu bisa tambah parameter buy_now=1 jika butuh logic khusus di backend
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'buy_now';
                input.value = '1';
                form.appendChild(input);
            }
            form.submit();
        }

        function filterSizeByColor(color) {
            const colorLower = color.toLowerCase().trim();
            const matchingThumb = document.querySelector(`.thumb-img[data-color="${colorLower}"]`);
            if (matchingThumb) changeImage(matchingThumb.querySelector('img').src, matchingThumb);

            document.querySelectorAll('.size-radio').forEach(r => r.checked = false);
            
            document.querySelectorAll('.size-option').forEach(option => {
                const sizeVal = option.getAttribute('data-size');
                const v = variants.find(v => v.color === color && v.size === sizeVal);
                const input = option.querySelector('input');

                if (!v || parseInt(v.stock) <= 0) {
                    option.classList.add('disabled');
                    input.disabled = true;
                } else {
                    option.classList.remove('disabled');
                    input.disabled = false;
                }
            });

            document.getElementById('variant_id_input').value = '';
            document.getElementById('validation-msg').classList.add('hidden'); // Sembunyikan notif saat pilih ulang
            updateStockDisplay();
        }

        function updateStockDisplay() {
            const c = document.querySelector('input[name="color"]:checked')?.value;
            const s = document.querySelector('input[name="size"]:checked')?.value;
            const stockTxt = document.getElementById('stock-count');
            const notif = document.getElementById('validation-msg');

            if (c && s) {
                const v = variants.find(v => v.color === c && v.size === s);
                if (v) {
                    document.getElementById('variant_id_input').value = v.id;
                    const stock = parseInt(v.stock);
                    if (stock > 0) {
                        stockTxt.innerText = `${stock} Pieces Available`;
                        notif.classList.add('hidden');
                    } else {
                        stockTxt.innerText = "Sold Out";
                    }
                }
            }
        }

        // Gallery Functions
        function changeImage(src, el) {
            const main = document.getElementById('mainImage');
            main.style.opacity = '0';
            setTimeout(() => { main.src = src; main.style.opacity = '1'; }, 300);
            document.querySelectorAll('.thumb-img').forEach(t => t.classList.replace('border-black', 'border-transparent'));
            if(el) { el.classList.replace('border-transparent', 'border-black'); currentIndex = parseInt(el.dataset.index); }
        }

        function nextImage() { currentIndex = (currentIndex + 1) % images.length; changeImage(images[currentIndex], document.querySelector(`.thumb-img[data-index="${currentIndex}"]`)); }
        function prevImage() { currentIndex = (currentIndex - 1 + images.length) % images.length; changeImage(images[currentIndex], document.querySelector(`.thumb-img[data-index="${currentIndex}"]`)); }
        function incrementQty() { const i = document.getElementById('qtyInput'); i.value = parseInt(i.value) + 1; }
        function decrementQty() { const i = document.getElementById('qtyInput'); if (parseInt(i.value) > 1) i.value = parseInt(i.value) - 1; }
        function toggleModal(id) { const m = document.getElementById(id); m.classList.toggle('hidden'); document.body.style.overflow = m.classList.contains('hidden') ? 'auto' : 'hidden'; }

        // Swipe handler
        let startX = 0;
        document.getElementById('swipeArea').addEventListener('touchstart', e => startX = e.touches[0].clientX);
        document.getElementById('swipeArea').addEventListener('touchend', e => {
            const endX = e.changedTouches[0].clientX;
            if (startX - endX > 50) nextImage();
            if (endX - startX > 50) prevImage();
        });

        // Countdown
        document.addEventListener('DOMContentLoaded', () => {
            const el = document.getElementById('preorder-countdown');
            if (el) {
                const target = new Date(el.dataset.expire).getTime();
                setInterval(() => {
                    const dist = target - new Date().getTime();
                    if (dist < 0) return;
                    el.classList.remove('hidden');
                    document.getElementById('days').innerText = Math.floor(dist / 86400000).toString().padStart(2, '0');
                    document.getElementById('hours').innerText = Math.floor((dist % 86400000) / 3600000).toString().padStart(2, '0');
                    document.getElementById('minutes').innerText = Math.floor((dist % 3600000) / 60000).toString().padStart(2, '0');
                    document.getElementById('seconds').innerText = Math.floor((dist % 60000) / 1000).toString().padStart(2, '0');
                }, 1000);
            }
        });
    </script>
</body>
</html>