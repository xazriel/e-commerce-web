<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:justify-between sm:items-center">
            <div>
                <p class="text-[9px] font-black text-[#6B705C] uppercase tracking-[0.25em] mb-1">Web Content · Homepage</p>
                <h2 class="font-black text-xl md:text-2xl text-[#2F3526] leading-tight tracking-tight">
                    Kelola Banner Promo
                </h2>
            </div>
            <a href="{{ route('admin.dashboard') }}"
               class="inline-flex items-center gap-2 text-[9px] font-black text-[#6B705C] uppercase tracking-widest hover:text-[#2F3526] transition-all group">
                <span class="w-5 h-px bg-[#6B705C] group-hover:w-8 transition-all duration-300"></span>
                Kembali ke Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-10">

            {{-- ── NOTIFIKASI ── --}}
            @if(session('success'))
                <div class="flex items-center gap-4 px-6 py-4 bg-[#2F3526] text-white rounded-2xl animate-fade-in-down">
                    <div class="w-7 h-7 rounded-full bg-white/10 flex items-center justify-center flex-shrink-0">
                        <svg class="w-3.5 h-3.5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <p class="text-[10px] font-black uppercase tracking-[0.2em]">{{ session('success') }}</p>
                </div>
            @endif

            @if($errors->any() || session('error'))
                <div class="flex items-start gap-4 px-6 py-4 bg-red-50 border border-red-100 text-red-600 rounded-2xl animate-fade-in-down">
                    <div class="w-7 h-7 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                        <svg class="w-3.5 h-3.5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-[9px] font-black uppercase tracking-[0.2em] mb-1.5">Gagal Menyimpan</p>
                        @if(session('error'))
                            <p class="text-[10px] font-semibold">· {{ session('error') }}</p>
                        @endif
                        @foreach ($errors->all() as $error)
                            <p class="text-[10px] font-semibold">· {{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- ══════════════════════════════════════
                 FORM UPLOAD (Dibuat sangat awam)
            ══════════════════════════════════════ --}}
            <div class="bg-white border border-[#E9E9E9] rounded-3xl overflow-hidden shadow-sm">

                {{-- Header form --}}
                <div class="bg-[#2F3526] px-8 py-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h3 class="text-[13px] font-black text-white uppercase tracking-[0.15em]">Tambahkan Banner / Promo Baru</h3>
                        <p class="text-[9px] text-[#E9E9E9]/70 uppercase tracking-widest mt-1">Banner akan langsung tampil di halaman depan website.</p>
                    </div>
                </div>

                <form action="{{ route('admin.sliders.store') }}" method="POST" enctype="multipart/form-data" class="p-8">
                    @csrf

                    {{-- Nama Banner --}}
                    <div class="mb-8">
                        <label class="block text-[10px] font-black text-[#2F3526] uppercase tracking-[0.2em] mb-2">
                            Nama Banner / Promo
                        </label>
                        <input type="text" name="title" value="{{ old('title') }}"
                               placeholder="Contoh: Promo Lebaran 2025"
                               class="w-full sm:w-2/3 bg-[#E9E9E9]/30 border border-[#E9E9E9] focus:border-[#2F3526] focus:ring-0 rounded-xl text-[12px] font-bold text-[#2F3526] placeholder:text-[#6B705C]/50 py-3.5 px-4 transition">
                        <p class="mt-2 text-[9px] text-[#6B705C] uppercase tracking-widest">Hanya untuk catatan internal tim, tidak akan terbaca oleh pengunjung.</p>
                    </div>

                    {{-- Upload Area (Side by side) --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">

                        {{-- Desktop Upload (Wajib) --}}
                        <div class="relative group cursor-pointer" onclick="this.querySelector('input').click()">
                            <div class="h-full rounded-2xl border-2 border-dashed border-[#E9E9E9] group-hover:border-[#2F3526] transition-all duration-300 p-8 bg-white flex flex-col items-center justify-center text-center">
                                <div class="w-14 h-14 mb-4 rounded-full bg-[#E9E9E9]/60 group-hover:bg-[#2F3526] group-hover:text-white transition-colors duration-300 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-inherit" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <h4 class="text-[11px] font-black text-[#2F3526] uppercase tracking-[0.1em] mb-1">Banner Layar Komputer</h4>
                                <span class="bg-[#2F3526] text-[7px] text-white px-2.5 py-0.5 rounded-full font-black uppercase tracking-widest mb-3">WAJIB DIISI</span>
                                <p class="text-[9px] text-[#6B705C] font-semibold">Klik di sini untuk memilih foto atau video (Mendatar)</p>
                                <p class="text-[8px] text-[#6B705C]/60 uppercase tracking-widest mt-3">Maks 100MB (JPG / PNG / MP4)</p>
                                
                                {{-- Preview File Name --}}
                                <div id="desktop-preview" class="w-full mt-4 p-3 bg-[#E9E9E9]/40 rounded-xl hidden">
                                    <p id="desktop-filename" class="text-[9px] font-bold text-[#2F3526] truncate text-center"></p>
                                </div>
                            </div>
                            <input type="file" name="image" accept="image/*,video/mp4" class="hidden" onchange="previewFile(this, 'desktop')">
                        </div>

                        {{-- Mobile Upload (Opsional) --}}
                        <div class="relative group cursor-pointer" onclick="this.querySelector('input').click()">
                            <div class="h-full rounded-2xl border-2 border-dashed border-[#E9E9E9] group-hover:border-[#6B705C] transition-all duration-300 p-8 bg-white flex flex-col items-center justify-center text-center">
                                <div class="w-14 h-14 mb-4 rounded-full bg-[#E9E9E9]/60 group-hover:bg-[#6B705C] group-hover:text-white transition-colors duration-300 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-inherit" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <h4 class="text-[11px] font-black text-[#2F3526] uppercase tracking-[0.1em] mb-1">Banner Layar Handphone</h4>
                                <span class="bg-[#E9E9E9] text-[7px] text-[#6B705C] px-2.5 py-0.5 rounded-full font-black uppercase tracking-widest mb-3 border border-[#E9E9E9]">OPSIONAL</span>
                                <p class="text-[9px] text-[#6B705C] font-semibold">Klik di sini untuk versi foto/video Handphone (Berdiri)</p>
                                <p class="text-[8px] text-[#6B705C]/60 uppercase tracking-widest mt-3">Maks 100MB (JPG / PNG / MP4)</p>
                                
                                {{-- Preview File Name --}}
                                <div id="mobile-preview" class="w-full mt-4 p-3 bg-[#E9E9E9]/40 rounded-xl hidden">
                                    <p id="mobile-filename" class="text-[9px] font-bold text-[#6B705C] truncate text-center"></p>
                                </div>
                            </div>
                            <input type="file" name="image_mobile" accept="image/*,video/mp4" class="hidden" onchange="previewFile(this, 'mobile')">
                        </div>
                    </div>

                    {{-- Submit --}}
                    <div class="flex justify-end border-t border-[#E9E9E9] pt-6">
                        <button type="submit" id="btnSubmit"
                                class="inline-flex items-center gap-3 bg-[#2F3526] text-white px-8 py-4 rounded-xl font-black uppercase text-[10px] tracking-[0.25em] hover:bg-black transition-all duration-300 active:scale-95 disabled:opacity-40 disabled:cursor-not-allowed shadow-lg shadow-[#2F3526]/20">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                            </svg>
                            Simpan & Tampilkan Banner
                        </button>
                    </div>
                </form>
            </div>

            {{-- ══════════════════════════════════════
                 GALLERY BANNER (Dibuat sederhana)
            ══════════════════════════════════════ --}}
            <div>
                <div class="mb-6">
                    <h3 class="text-[12px] font-black text-[#2F3526] uppercase tracking-[0.2em]">Daftar Banner Saat Ini</h3>
                    <p class="text-[9px] text-[#6B705C] uppercase tracking-widest mt-1">Terdapat {{ $sliders->count() }} banner yang sedang aktif di website.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($sliders as $slider)
                    <div class="bg-white border border-[#E9E9E9] rounded-2xl overflow-hidden group hover:shadow-lg hover:-translate-y-1 transition-all duration-300 flex flex-col">

                        {{-- Thumbnail Utama (Hanya Desktop) --}}
                        <div class="relative aspect-[16/9] bg-[#E9E9E9]/40 overflow-hidden">
                            @if($slider->type === 'video' && $slider->video_path)
                                <video muted loop playsinline class="w-full h-full object-cover">
                                    <source src="{{ asset('storage/' . $slider->video_path) }}" type="video/mp4">
                                </video>
                                <div class="absolute inset-0 bg-black/20 flex items-center justify-center">
                                    <div class="w-10 h-10 rounded-full bg-white/20 backdrop-blur-md border border-white/30 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-white ml-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"/>
                                        </svg>
                                    </div>
                                </div>
                            @elseif($slider->type === 'image' && $slider->image_path)
                                <img src="{{ asset('storage/' . $slider->image_path) }}"
                                     alt="{{ $slider->title }}" loading="lazy"
                                     class="w-full h-full object-cover group-hover:scale-105 transition duration-700">
                            @else
                                <div class="w-full h-full flex flex-col items-center justify-center gap-2">
                                    <svg class="w-6 h-6 text-[#6B705C]/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01"/>
                                    </svg>
                                    <span class="text-[8px] font-bold text-[#6B705C]/50 uppercase tracking-widest">Visual Kosong</span>
                                </div>
                            @endif

                            {{-- Badge Indikator HP --}}
                            @if($slider->image_mobile_path)
                            <div class="absolute bottom-3 left-3">
                                <span class="inline-flex items-center gap-1.5 bg-white/95 backdrop-blur-md text-[7px] text-[#2F3526] px-3 py-1.5 rounded-full font-black uppercase tracking-widest shadow-sm">
                                    <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                    </svg>
                                    Dilengkapi Versi HP
                                </span>
                            </div>

                            {{-- Preview Mobile (Picture in Picture) --}}
                            <div class="absolute bottom-3 right-3 w-10 sm:w-12 rounded border border-white/50 shadow-2xl overflow-hidden bg-[#E9E9E9] z-10" style="aspect-ratio: 9/16;">
                                @php
                                    $mobileExt = strtolower(pathinfo($slider->image_mobile_path, PATHINFO_EXTENSION));
                                    $isMobileVideo = $mobileExt === 'mp4';
                                @endphp
                                @if($isMobileVideo)
                                    <video muted loop playsinline class="w-full h-full object-cover">
                                        <source src="{{ asset('storage/' . $slider->image_mobile_path) }}" type="video/mp4">
                                    </video>
                                @else
                                    <img src="{{ asset('storage/' . $slider->image_mobile_path) }}"
                                         alt="Mobile Version"
                                         class="w-full h-full object-cover">
                                @endif
                            </div>
                            @endif

                            {{-- Urutan --}}
                            <span class="absolute top-3 right-3 bg-black/60 backdrop-blur-md text-[8px] text-white px-2.5 py-1 rounded-full font-black">#{{ $slider->order }}</span>
                        </div>

                        {{-- Info dan Action --}}
                        <div class="p-5 flex-1 flex flex-col justify-between">
                            <div class="mb-5">
                                <h4 class="text-[12px] font-black text-[#2F3526] uppercase tracking-wide mb-1">
                                    {{ $slider->title ?? 'Banner Tanpa Nama' }}
                                </h4>
                                <p class="text-[9px] text-[#6B705C] font-semibold">Diunggah: {{ $slider->created_at->format('d M Y') }}</p>
                            </div>

                            <form action="{{ route('admin.sliders.destroy', $slider) }}" method="POST"
                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus banner ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="w-full flex items-center justify-center gap-2 text-[9px] font-black uppercase tracking-widest text-red-500 bg-red-50 py-3 rounded-xl hover:bg-red-500 hover:text-white transition-all duration-300">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Hapus Banner
                                </button>
                            </form>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-full">
                        <div class="bg-white border-2 border-dashed border-[#E9E9E9] rounded-3xl py-24 flex flex-col items-center justify-center gap-4">
                            <div class="w-16 h-16 rounded-full bg-[#E9E9E9]/60 flex items-center justify-center text-[#6B705C]/30">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div class="text-center">
                                <p class="text-[12px] font-black text-[#6B705C]/70 uppercase tracking-[0.2em]">Belum Ada Banner</p>
                                <p class="text-[10px] text-[#6B705C]/50 mt-1">Silakan tambahkan banner pertama Anda melalui form di atas.</p>
                            </div>
                        </div>
                    </div>
                    @endforelse
                </div>
            </div>

            {{-- Footer --}}
            <div class="flex justify-center pt-8">
                <p class="text-[8px] font-black text-[#6B705C]/30 uppercase tracking-[0.4em]">Sistem Manajemen Konten Farhana</p>
            </div>

        </div>
    </div>

    <script>
        // File preview on input change
        function previewFile(input, type) {
            const file = input.files[0];
            const prefix = type; // 'desktop' or 'mobile'
            const preview = document.getElementById(prefix + '-preview');
            const fname   = document.getElementById(prefix + '-filename');

            if (file) {
                fname.textContent = file.name;
                preview.classList.remove('hidden');
                
                // Beri efek border solid untuk menandakan sudah terpilih
                input.closest('.group').querySelector('.border-dashed').classList.replace('border-dashed', 'border-solid');
                input.closest('.group').querySelector('.border-solid').classList.add('border-[#2F3526]');
            } else {
                preview.classList.add('hidden');
                
                // Kembalikan ke dashed
                const borderDiv = input.closest('.group').querySelector('.border-solid');
                if(borderDiv) {
                    borderDiv.classList.replace('border-solid', 'border-dashed');
                    borderDiv.classList.remove('border-[#2F3526]');
                }
            }
        }

        // Hover-play videos
        document.querySelectorAll('video').forEach(vid => {
            const container = vid.closest('.relative');
            if (!container) return;
            container.addEventListener('mouseenter', () => vid.play());
            container.addEventListener('mouseleave', () => { vid.pause(); vid.currentTime = 0; });
        });

        // Upload loading state
        const uploadForm = document.querySelector('form[action*="sliders"]');
        const btn = document.getElementById('btnSubmit');
        if (uploadForm && btn) {
            uploadForm.addEventListener('submit', () => {
                btn.disabled = true;
                btn.innerHTML = `
                    <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                    </svg>
                    <span class="animate-pulse tracking-[0.2em]">Menyimpan Data...</span>
                `;
            });
        }
    </script>

    <style>
        @keyframes fade-in-down {
            from { opacity: 0; transform: translateY(-8px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-down { animation: fade-in-down 0.35s ease-out; }
    </style>
</x-app-layout>
