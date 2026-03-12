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
    </style>
</head>
<body class="bg-white text-gray-900 font-sans antialiased">
    
    <nav class="bg-white border-b border-gray-100 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex-shrink-0">
                    <a href="{{ route('home') }}" class="text-2xl font-light tracking-[0.4em] uppercase">Farhana</a>
                </div>
                <a href="{{ route('home') }}" class="text-[10px] font-bold uppercase tracking-[0.2em] text-gray-400 hover:text-black transition">
                    &larr; Back to Collection
                </a>
            </div>
        </div>
        @if(session('success'))
    <div class="bg-black text-white text-[10px] tracking-[0.2em] uppercase py-3 text-center animate-fade-in-down">
        {{ session('success') }}
    </div>
        @endif
    </nav>

    <div class="max-w-7xl mx-auto px-4 py-12 lg:py-20">
        <div class="flex flex-col lg:flex-row gap-16">
            
            <div class="lg:w-3/5 space-y-6">
                <div class="bg-gray-50 overflow-hidden aspect-[3/4] border border-gray-50">
                    <img id="mainImage" 
                         src="{{ asset('storage/' . $product->images->where('is_primary', true)->first()->image_path ?? $product->images->first()->image_path) }}" 
                         class="w-full h-full object-cover" 
                         alt="{{ $product->name }}">
                </div>

                <div class="grid grid-cols-6 gap-3">
                    @foreach($product->images as $image)
                        <div class="cursor-pointer border-b-2 {{ $loop->first ? 'border-black' : 'border-transparent' }} hover:border-black transition pb-2"
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
                    <p class="text-[10px] text-gray-400 uppercase tracking-[0.4em] mb-4">
                        {{ $product->category->name ?? 'Collection' }}
                    </p>
                    <h1 class="text-3xl font-light mb-6 tracking-widest uppercase text-gray-900 leading-snug">
                        {{ $product->name }}
                    </h1>
                    <p class="text-2xl font-light mb-10 text-gray-900 tracking-wider">
                        IDR {{ number_format($product->price, 0, ',', '.') }}
                    </p>

                    <div class="border-t border-gray-100 pt-10 mb-10">
                        <h3 class="text-[10px] font-bold uppercase mb-6 tracking-[0.3em] text-gray-900">Description</h3>
                        <div class="text-gray-500 text-xs description-text uppercase">
                            {!! nl2br(e($product->description)) !!}
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="flex items-center justify-between text-[10px] uppercase tracking-[0.2em] text-gray-400 pb-4 border-b border-gray-50">
                            <span>Availability</span>
                            <span class="text-gray-900 font-bold">{{ $product->stock }} In Stock</span>
                        </div>
                        
                        <form action="{{ route('cart.add', $product->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full bg-black text-white py-5 text-[10px] font-bold uppercase tracking-[0.3em] hover:bg-gray-800 transition duration-500 shadow-lg mb-4">
                                Add to Cart
                            </button>
                        </form>

                        <a href="https://wa.me/628123456789?text=Halo%20Farhana,%20saya%20tertarik%20dengan%20produk%20{{ $product->name }}" 
                           target="_blank"
                           class="block w-full border border-gray-200 text-center py-5 text-[10px] font-bold uppercase tracking-[0.3em] text-gray-900 hover:bg-gray-50 transition duration-500">
                            Order via WhatsApp
                        </a>
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
                    <div class="aspect-[3/4] bg-gray-50 overflow-hidden mb-6">
                        @php $relImg = $related->images->where('is_primary', true)->first() ?? $related->images->first(); @endphp
                        <img src="{{ asset('storage/' . $relImg->image_path) }}" class="w-full h-full object-cover transition duration-1000 group-hover:scale-105">
                    </div>
                    <h4 class="text-[10px] font-bold uppercase tracking-widest mb-2">{{ $related->name }}</h4>
                    <p class="text-[10px] text-gray-400 tracking-widest">IDR {{ number_format($related->price, 0, ',', '.') }}</p>
                </a>
            @endforeach
        </div>
    </div>
    @endif

    <footer class="py-12 border-t border-gray-50 text-center">
        <p class="text-[9px] text-gray-300 uppercase tracking-[0.5em]">&copy; 2026 Farhana Official</p>
    </footer>

    <script>
        function changeImage(imageSrc, element) {
            const mainImage = document.getElementById('mainImage');
            mainImage.style.opacity = '0';
            setTimeout(() => {
                mainImage.src = imageSrc;
                mainImage.style.opacity = '1';
            }, 300);

            document.querySelectorAll('.cursor-pointer').forEach(el => {
                el.classList.remove('border-black');
                el.classList.add('border-transparent');
            });
            element.classList.remove('border-transparent');
            element.classList.add('border-black');
        }
    </script>
</body>
</html>