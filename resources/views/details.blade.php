<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $product->name }} - Farhana Web</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        #mainImage { transition: opacity 0.2s ease-in-out; }
    </style>
</head>
<body class="bg-white text-gray-900 font-sans">
    
    <nav class="border-b py-6 mb-10 bg-white sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 flex justify-between items-center">
            <a href="{{ route('home') }}" class="text-xs font-bold uppercase tracking-widest hover:text-gray-500 transition">&larr; Kembali ke Koleksi</a>
            <h1 class="text-xl font-bold tracking-tighter uppercase">Farhana</h1>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 pb-20">
        <div class="flex flex-col md:flex-row gap-12">
            
            <div class="md:w-1/2 space-y-4">
                <div class="bg-gray-50 rounded-sm overflow-hidden aspect-[3/4] border border-gray-100 shadow-sm">
                    <img id="mainImage" 
                         src="{{ asset('storage/' . $product->images->first()->image_path) }}" 
                         class="w-full h-full object-cover" 
                         alt="{{ $product->name }}">
                </div>

                <div class="grid grid-cols-5 gap-2">
                    @foreach($product->images as $image)
                        <div class="cursor-pointer border border-transparent hover:border-black transition aspect-square bg-gray-50 rounded-sm overflow-hidden"
                             onclick="changeImage('{{ asset('storage/' . $image->image_path) }}', this)">
                            <img src="{{ asset('storage/' . $image->image_path) }}" 
                                 class="w-full h-full object-cover opacity-80 hover:opacity-100 transition">
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="md:w-1/2">
                <div class="sticky top-28">
                    <p class="text-[10px] text-gray-400 uppercase tracking-[0.3em] mb-2 font-semibold">
                        {{ $product->category->name ?? 'Koleksi Farhana' }}
                    </p>
                    <h1 class="text-4xl font-light mb-4 tracking-tight text-gray-800 uppercase italic leading-tight">
                        {{ $product->name }}
                    </h1>
                    <p class="text-2xl font-medium mb-8 text-gray-900">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </p>

                    <div class="border-t border-b py-10 mb-8 border-gray-100">
                        <h3 class="text-xs font-bold uppercase mb-6 tracking-[0.2em] text-gray-400">Deskripsi Produk</h3>
                        <div class="text-gray-600 leading-relaxed text-sm space-y-4 whitespace-pre-line italic">
                            {{ $product->description }}
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div class="flex items-center justify-between text-xs uppercase tracking-widest text-gray-400">
                            <span>Ketersediaan</span>
                            <span class="text-gray-900 font-bold">{{ $product->stock }} Pcs</span>
                        </div>
                        
                        <a href="https://wa.me/628123456789?text=Halo%20Farhana,%20saya%20tertarik%20dengan%20produk%20{{ $product->name }}" 
                           target="_blank"
                           class="block w-full bg-black text-white text-center py-5 rounded-sm uppercase tracking-[0.2em] text-xs font-bold hover:bg-gray-800 transition shadow-xl">
                            Pesan Melalui WhatsApp
                        </a>
                        
                        <p class="text-[10px] text-center text-gray-400 uppercase tracking-widest">
                            * Pengiriman ke seluruh Indonesia
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>

    @if($relatedProducts->count() > 0)
    <div class="max-w-7xl mx-auto px-4 py-24 border-t border-gray-100">
        <div class="flex flex-col items-center mb-16">
            <h3 class="text-sm font-bold uppercase tracking-[0.4em] mb-2">Produk Terkait</h3>
            <div class="h-px w-20 bg-black"></div>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-10">
            @foreach($relatedProducts as $related)
                <a href="{{ route('product.details', $related->slug) }}" class="group block">
                    <div class="aspect-[3/4] bg-gray-50 overflow-hidden mb-5 rounded-sm border border-gray-50">
                        @php
                            $primaryImg = $related->images->where('is_primary', true)->first() ?? $related->images->first();
                        @endphp
                        @if($primaryImg)
                            <img src="{{ asset('storage/' . $primaryImg->image_path) }}" 
                                 class="w-full h-full object-cover transition duration-1000 group-hover:scale-110">
                        @else
                            <div class="flex items-center justify-center h-full text-gray-300 text-[10px] uppercase">No Image</div>
                        @endif
                    </div>
                    <h4 class="text-[10px] font-bold uppercase tracking-widest text-gray-800 group-hover:text-gray-500 transition">{{ $related->name }}</h4>
                    <p class="text-xs font-medium mt-2 text-gray-900 tracking-tighter">Rp {{ number_format($related->price, 0, ',', '.') }}</p>
                </a>
            @endforeach
        </div>
    </div>
    @endif

    <footer class="py-16 border-t border-gray-100 text-center">
        <p class="text-[10px] text-gray-400 uppercase tracking-[0.5em] mb-4">&copy; 2026 Farhana Web Exclusive</p>
        <div class="flex justify-center gap-6">
            </div>
    </footer>

    <script>
        function changeImage(imageSrc, element) {
            const mainImage = document.getElementById('mainImage');
            
            // Efek Fade Out
            mainImage.style.opacity = '0';
            
            setTimeout(() => {
                mainImage.src = imageSrc;
                // Efek Fade In
                mainImage.style.opacity = '1';
            }, 200);

            // Menghapus border aktif dari semua thumbnail
            document.querySelectorAll('.cursor-pointer').forEach(el => {
                el.classList.remove('border-black');
                el.classList.add('border-transparent');
            });
            
            // Menambah border aktif ke thumbnail yang diklik
            element.classList.remove('border-transparent');
            element.classList.add('border-black');
        }
    </script>
</body>
</html>