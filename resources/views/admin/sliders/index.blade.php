<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:justify-between sm:items-center">
            <div>
                <h2 class="font-semibold text-lg md:text-xl text-gray-800 leading-tight tracking-tight">
                    {{ __('Kelola Banner Slider') }}
                </h2>
                <p class="text-[9px] text-gray-400 uppercase tracking-widest mt-1 hidden sm:block">
                    Web Content / Homepage Sliders
                </p>
            </div>

            <div class="flex items-center">
                <a href="{{ route('admin.dashboard') }}" 
                class="inline-flex items-center text-[10px] font-bold text-gray-400 uppercase tracking-widest hover:text-black transition-all group">
                    <span class="mr-2 transform group-hover:-translate-x-1 transition-transform">&larr;</span>
                    <span class="underline decoration-gray-200 underline-offset-4 group-hover:decoration-black">Dashboard</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Notifikasi Sukses --}}
            @if(session('success'))
                <div class="mb-6 p-4 bg-black text-white border-l-4 border-green-500 text-[10px] uppercase font-bold tracking-[0.2em] animate-fade-in-down">
                    <div class="flex items-center gap-3">
                        <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            {{-- NOTIFIKASI ERROR SPESIFIK (DIPERBARUI) --}}
            @if($errors->any() || session('error'))
                <div class="mb-6 p-4 bg-red-50 text-red-600 border-l-4 border-red-500 rounded-r-xl shadow-sm animate-fade-in-down">
                    <div class="flex items-center mb-2">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-[10px] font-black uppercase tracking-widest">Gagal Mengunggah:</span>
                    </div>
                    
                    <div class="flex flex-col gap-1 ml-6">
                        {{-- Error dari Session (Masalah Server/php.ini) --}}
                        @if(session('error'))
                            <p class="text-[10px] font-bold uppercase tracking-tight">• {{ session('error') }}</p>
                        @endif

                        {{-- Error dari Validasi Laravel (Tipe file/Ukuran) --}}
                        @foreach ($errors->all() as $error)
                            <p class="text-[10px] font-bold uppercase tracking-tight">• {{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Form Upload Premium Box --}}
            <div class="bg-white p-8 rounded-2xl shadow-sm mb-10 border border-gray-100">
                <div class="mb-6">
                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-widest">Tambah Banner Baru</h3>
                    <p class="text-[9px] text-gray-400 uppercase mt-1">Mendukung format Gambar (JPG/PNG), GIF, dan Video (MP4).</p>
                </div>

                <form action="{{ route('sliders.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                        
                        {{-- Desktop Upload --}}
                        <div class="flex flex-col">
                            <label class="text-[10px] font-black text-gray-400 uppercase mb-3 tracking-widest">Desktop / Utama</label>
                            <div class="relative">
                                <input type="file" name="image" accept="image/*,video/mp4"
                                    class="text-[10px] block w-full text-gray-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-[10px] file:font-bold file:bg-gray-50 file:text-gray-700 hover:file:bg-gray-100 transition file:cursor-pointer">
                            </div>
                            <p class="text-[8px] text-gray-300 mt-2 italic uppercase">Rasio 16:9 | Maks 100MB</p>
                        </div>

                        {{-- Mobile Upload --}}
                        <div class="flex flex-col">
                            <label class="text-[10px] font-black text-gray-400 uppercase mb-3 tracking-widest">Mobile (Portrait)</label>
                            <input type="file" name="image_mobile" accept="image/*,video/mp4"
                                class="text-[10px] block w-full text-gray-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-[10px] file:font-bold file:bg-gray-50 file:text-gray-700 hover:file:bg-gray-100 transition file:cursor-pointer">
                            <p class="text-[8px] text-gray-300 mt-2 italic uppercase">Opsional (Rasio 9:16)</p>
                        </div>

                        {{-- Judul --}}
                        <div class="flex flex-col">
                            <label class="text-[10px] font-black text-gray-400 uppercase mb-3 tracking-widest">Nama / Label Banner</label>
                            <input type="text" name="title" value="{{ old('title') }}" placeholder="CONTOH: PROMO VIDEO" 
                                class="border-gray-100 focus:border-black focus:ring-0 rounded-lg text-xs placeholder:text-gray-200 bg-gray-50/50 py-2.5 font-bold tracking-tight">
                        </div>

                        {{-- Submit --}}
                        <div class="flex items-end">
                            <button type="submit" id="btnSubmit" class="w-full bg-black text-white px-4 py-3 rounded-xl font-bold hover:bg-gray-800 transition uppercase text-[10px] tracking-[0.2em] shadow-xl shadow-gray-100 active:scale-95 disabled:bg-gray-400">
                                Simpan Banner
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Gallery List --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                @forelse($sliders as $slider)
                <div class="bg-white p-3 rounded-2xl shadow-sm border border-gray-100 group hover:shadow-md transition-all duration-300">
                    <div class="relative aspect-[16/9] overflow-hidden rounded-xl mb-4 bg-gray-50">
                        
                        @if($slider->image_path)
                            @if($slider->type === 'video')
                                <video muted loop playsinline class="w-full h-full object-cover">
                                    <source src="{{ asset('storage/' . $slider->image_path) }}" type="video/mp4">
                                </video>
                                <div class="absolute inset-0 flex items-center justify-center bg-black/10">
                                    <svg class="w-6 h-6 text-white/70" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z"/></svg>
                                </div>
                            @else
                                <img src="{{ asset('storage/' . $slider->image_path) }}" 
                                    alt="{{ $slider->title }}"
                                    loading="lazy" 
                                    class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                            @endif
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center bg-gray-50 text-[9px] text-gray-300 uppercase font-bold tracking-tighter">
                                <svg class="w-6 h-6 mb-1 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                                No Content
                            </div>
                        @endif
                        
                        <div class="absolute top-2 left-2 flex flex-wrap gap-1.5">
                            <span class="bg-black/80 backdrop-blur-md text-[6px] text-white px-2 py-0.5 rounded-full uppercase font-black tracking-widest border border-white/10">
                                {{ $slider->type ?? 'IMG' }}
                            </span>
                            @if($slider->image_mobile_path)
                                <span class="bg-indigo-600/80 backdrop-blur-md text-[6px] text-white px-2 py-0.5 rounded-full uppercase font-black tracking-widest border border-white/10">MB</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="px-1 mb-4">
                        <h4 class="text-[10px] font-black text-gray-900 truncate uppercase tracking-tight">{{ $slider->title ?? 'Untitled Banner' }}</h4>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="w-4 h-px bg-gray-100"></span>
                            <p class="text-[8px] text-gray-400 uppercase tracking-widest font-medium">{{ $slider->created_at->format('d/m/Y') }}</p>
                        </div>
                    </div>
                    
                    <form action="{{ route('sliders.destroy', $slider) }}" method="POST" onsubmit="return confirm('Hapus banner ini?')">
                        @csrf 
                        @method('DELETE')
                        <button class="w-full text-red-400 text-[9px] uppercase font-bold py-2.5 rounded-xl border border-red-50 hover:bg-red-500 hover:text-white hover:border-red-500 transition-all duration-300">
                            Hapus
                        </button>
                    </form>
                </div>
                @empty
                <div class="col-span-full bg-white py-32 text-center rounded-2xl border-2 border-dashed border-gray-100">
                    <div class="flex flex-col items-center">
                        <svg class="w-10 h-10 text-gray-100 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <p class="text-gray-300 text-[10px] uppercase font-bold tracking-[0.3em]">Belum ada banner aktif</p>
                    </div>
                </div>
                @endforelse
            </div>
            
            <div class="mt-16 flex flex-col items-center">
                <div class="h-1 w-10 bg-gray-50 rounded-full"></div>
                <p class="mt-4 text-[7px] text-gray-300 uppercase tracking-[0.8em]">Visual Manager v1.0</p>
            </div>
        </div>
    </div>

    {{-- Script untuk Preview Video & Loading State --}}
    <script>
        document.querySelectorAll('video').forEach(vid => {
            vid.parentElement.addEventListener('mouseenter', () => vid.play());
            vid.parentElement.addEventListener('mouseleave', () => {
                vid.pause();
                vid.currentTime = 0;
            });
        });

        const form = document.querySelector('form');
        const btn = document.getElementById('btnSubmit');
        form.addEventListener('submit', () => {
            btn.disabled = true;
            btn.innerHTML = '<span class="animate-pulse">MENGUNGGAH...</span>';
            btn.classList.add('opacity-50', 'cursor-not-allowed');
        });
    </script>

    <style>
        @keyframes fade-in-down {
            0% { opacity: 0; transform: translateY(-10px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-down {
            animation: fade-in-down 0.4s ease-out;
        }
    </style>
</x-app-layout>