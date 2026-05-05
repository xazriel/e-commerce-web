<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:justify-between sm:items-center">
            <div>
                <p class="text-[9px] font-black text-[#6B705C] uppercase tracking-[0.25em] mb-1">Web Content · Homepage</p>
                <h2 class="font-black text-xl md:text-2xl text-[#2F3526] leading-tight tracking-tight">
                    Kelola Banner Slider
                </h2>
            </div>
            <a href="{{ route('admin.dashboard') }}"
               class="inline-flex items-center gap-2 text-[9px] font-black text-[#6B705C] uppercase tracking-widest hover:text-[#2F3526] transition-all group">
                <span class="w-5 h-px bg-[#6B705C] group-hover:w-8 transition-all duration-300"></span>
                Dashboard
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
                        <p class="text-[9px] font-black uppercase tracking-[0.2em] mb-1.5">Gagal Mengunggah</p>
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
                 FORM UPLOAD
            ══════════════════════════════════════ --}}
            <div class="bg-white border border-[#E9E9E9] rounded-3xl overflow-hidden shadow-sm">

                {{-- Header form --}}
                <div class="bg-[#2F3526] px-8 py-5 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-black text-white uppercase tracking-[0.2em]">Tambah Banner Baru</h3>
                        <p class="text-[8px] text-[#6B705C] uppercase tracking-wider mt-0.5">JPG · PNG · WebP · GIF · MP4 · Maks 100MB per file</p>
                    </div>
                    <div class="w-8 h-8 rounded-full bg-white/5 border border-white/10 flex items-center justify-center">
                        <svg class="w-4 h-4 text-[#6B705C]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </div>
                </div>

                <form action="{{ route('sliders.store') }}" method="POST" enctype="multipart/form-data" class="p-8">
                    @csrf

                    {{-- Nama Banner --}}
                    <div class="mb-7">
                        <label class="block text-[9px] font-black text-[#2F3526] uppercase tracking-[0.2em] mb-2">
                            Nama / Label Banner
                        </label>
                        <input type="text" name="title" value="{{ old('title') }}"
                               placeholder="Contoh: PROMO SUMMER 2025"
                               class="w-full sm:w-1/2 bg-[#E9E9E9]/40 border border-[#E9E9E9] focus:border-[#2F3526] focus:ring-0 rounded-xl text-[11px] font-bold text-[#2F3526] placeholder:text-[#6B705C]/40 py-3 px-4 tracking-tight transition">
                        <p class="mt-1.5 text-[8px] text-[#6B705C] uppercase tracking-widest">Digunakan untuk identifikasi internal, tidak tampil di publik</p>
                    </div>

                    {{-- Upload Area --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-7">

                        {{-- Desktop Upload --}}
                        <div class="relative">
                            <div class="upload-zone rounded-2xl border-2 border-dashed border-[#E9E9E9] hover:border-[#2F3526] transition-all duration-300 p-6 cursor-pointer group bg-[#E9E9E9]/20 hover:bg-[#2F3526]/5"
                                 onclick="this.querySelector('input').click()">
                                {{-- Icon --}}
                                <div class="flex items-start gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-[#2F3526] flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 mb-1">
                                            <p class="text-[10px] font-black text-[#2F3526] uppercase tracking-[0.15em]">Desktop / Utama</p>
                                            <span class="bg-[#2F3526] text-[6px] text-white px-2 py-0.5 rounded-full font-black uppercase tracking-widest">WAJIB</span>
                                        </div>
                                        <p class="text-[8px] text-[#6B705C] font-bold uppercase">Rasio 16:9 · Landscape</p>
                                        <p class="text-[8px] text-[#6B705C] font-bold uppercase mt-0.5">IMG: JPG, PNG, WebP, GIF · VID: MP4</p>
                                    </div>
                                </div>

                                {{-- File preview name --}}
                                <div id="desktop-preview" class="mt-4 hidden">
                                    <div class="flex items-center gap-2 bg-[#2F3526]/5 rounded-lg px-3 py-2">
                                        <svg class="w-3 h-3 text-[#2F3526]" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/>
                                        </svg>
                                        <span id="desktop-filename" class="text-[9px] font-bold text-[#2F3526] truncate"></span>
                                    </div>
                                </div>

                                <div id="desktop-empty" class="mt-4 flex items-center gap-2">
                                    <div class="flex-1 h-px bg-[#E9E9E9]"></div>
                                    <span class="text-[8px] text-[#6B705C]/60 uppercase font-bold">Klik untuk pilih file</span>
                                    <div class="flex-1 h-px bg-[#E9E9E9]"></div>
                                </div>

                                <input type="file" name="image" accept="image/*,video/mp4" class="hidden"
                                       onchange="previewFile(this, 'desktop')">
                            </div>
                        </div>

                        {{-- Mobile Upload --}}
                        <div class="relative">
                            <div class="upload-zone rounded-2xl border-2 border-dashed border-[#E9E9E9] hover:border-[#6B705C] transition-all duration-300 p-6 cursor-pointer group bg-[#E9E9E9]/20 hover:bg-[#6B705C]/5"
                                 onclick="this.querySelector('input').click()">
                                <div class="flex items-start gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-[#6B705C] flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 mb-1">
                                            <p class="text-[10px] font-black text-[#2F3526] uppercase tracking-[0.15em]">Mobile / Portrait</p>
                                            <span class="bg-[#6B705C] text-[6px] text-white px-2 py-0.5 rounded-full font-black uppercase tracking-widest">OPSIONAL</span>
                                        </div>
                                        <p class="text-[8px] text-[#6B705C] font-bold uppercase">Rasio 9:16 · Portrait</p>
                                        <p class="text-[8px] text-[#6B705C] font-bold uppercase mt-0.5">IMG: JPG, PNG, WebP, GIF · VID: MP4</p>
                                    </div>
                                </div>

                                <div id="mobile-preview" class="mt-4 hidden">
                                    <div class="flex items-center gap-2 bg-[#6B705C]/10 rounded-lg px-3 py-2">
                                        <svg class="w-3 h-3 text-[#6B705C]" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/>
                                        </svg>
                                        <span id="mobile-filename" class="text-[9px] font-bold text-[#6B705C] truncate"></span>
                                    </div>
                                </div>

                                <div id="mobile-empty" class="mt-4 flex items-center gap-2">
                                    <div class="flex-1 h-px bg-[#E9E9E9]"></div>
                                    <span class="text-[8px] text-[#6B705C]/60 uppercase font-bold">Klik untuk pilih file</span>
                                    <div class="flex-1 h-px bg-[#E9E9E9]"></div>
                                </div>

                                <input type="file" name="image_mobile" accept="image/*,video/mp4" class="hidden"
                                       onchange="previewFile(this, 'mobile')">
                            </div>
                        </div>
                    </div>

                    {{-- Guide tipe file --}}
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-7">
                        <div class="flex items-center gap-2.5 bg-[#E9E9E9]/40 rounded-xl px-3 py-2.5 border border-[#E9E9E9]">
                            <div class="w-7 h-7 rounded-lg bg-[#2F3526] flex items-center justify-center flex-shrink-0">
                                <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-[8px] font-black text-[#2F3526] uppercase tracking-widest">IMG Desktop</p>
                                <p class="text-[7px] text-[#6B705C] uppercase">JPG / PNG / WebP</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2.5 bg-[#E9E9E9]/40 rounded-xl px-3 py-2.5 border border-[#E9E9E9]">
                            <div class="w-7 h-7 rounded-lg bg-black flex items-center justify-center flex-shrink-0">
                                <svg class="w-3.5 h-3.5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-[8px] font-black text-[#2F3526] uppercase tracking-widest">VID Desktop</p>
                                <p class="text-[7px] text-[#6B705C] uppercase">MP4 · 16:9</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2.5 bg-[#E9E9E9]/40 rounded-xl px-3 py-2.5 border border-[#E9E9E9]">
                            <div class="w-7 h-7 rounded-lg bg-[#6B705C] flex items-center justify-center flex-shrink-0">
                                <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-[8px] font-black text-[#2F3526] uppercase tracking-widest">IMG Mobile</p>
                                <p class="text-[7px] text-[#6B705C] uppercase">JPG / PNG · 9:16</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2.5 bg-[#E9E9E9]/40 rounded-xl px-3 py-2.5 border border-[#E9E9E9]">
                            <div class="w-7 h-7 rounded-lg bg-[#6B705C]/60 flex items-center justify-center flex-shrink-0">
                                <svg class="w-3.5 h-3.5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-[8px] font-black text-[#2F3526] uppercase tracking-widest">VID Mobile</p>
                                <p class="text-[7px] text-[#6B705C] uppercase">MP4 · 9:16</p>
                            </div>
                        </div>
                    </div>

                    {{-- Submit --}}
                    <div class="flex justify-end">
                        <button type="submit" id="btnSubmit"
                                class="inline-flex items-center gap-3 bg-[#2F3526] text-white px-8 py-3.5 rounded-xl font-black uppercase text-[10px] tracking-[0.25em] hover:bg-black transition-all duration-300 active:scale-95 disabled:opacity-40 disabled:cursor-not-allowed shadow-lg shadow-[#2F3526]/20">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                            </svg>
                            Simpan Banner
                        </button>
                    </div>
                </form>
            </div>


            {{-- ══════════════════════════════════════
                 GALLERY BANNER
            ══════════════════════════════════════ --}}
            <div>
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h3 class="text-[10px] font-black text-[#2F3526] uppercase tracking-[0.2em]">Banner Aktif</h3>
                        <p class="text-[8px] text-[#6B705C] uppercase tracking-widest mt-0.5">{{ $sliders->count() }} banner terdaftar</p>
                    </div>
                    {{-- Legend pill --}}
                    <div class="hidden sm:flex items-center gap-2">
                        <div class="flex items-center gap-1.5 bg-[#E9E9E9]/60 rounded-full px-3 py-1.5">
                            <span class="w-2 h-2 rounded-full bg-[#2F3526]"></span>
                            <span class="text-[7px] font-black uppercase tracking-widest text-[#2F3526]">DSK-IMG</span>
                        </div>
                        <div class="flex items-center gap-1.5 bg-[#E9E9E9]/60 rounded-full px-3 py-1.5">
                            <span class="w-2 h-2 rounded-full bg-black"></span>
                            <span class="text-[7px] font-black uppercase tracking-widest text-[#2F3526]">DSK-VID</span>
                        </div>
                        <div class="flex items-center gap-1.5 bg-[#E9E9E9]/60 rounded-full px-3 py-1.5">
                            <span class="w-2 h-2 rounded-full bg-[#6B705C]"></span>
                            <span class="text-[7px] font-black uppercase tracking-widest text-[#2F3526]">MOB-IMG</span>
                        </div>
                        <div class="flex items-center gap-1.5 bg-[#E9E9E9]/60 rounded-full px-3 py-1.5">
                            <span class="w-2 h-2 rounded-full bg-[#6B705C]/50"></span>
                            <span class="text-[7px] font-black uppercase tracking-widest text-[#2F3526]">MOB-VID</span>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
                    @forelse($sliders as $slider)
                    <div class="bg-white border border-[#E9E9E9] rounded-2xl overflow-hidden group hover:shadow-md hover:-translate-y-0.5 transition-all duration-300">

                        {{-- ── DESKTOP THUMB ── --}}
                        <div class="relative aspect-[16/9] bg-[#E9E9E9]/40 overflow-hidden">
                            @if($slider->type === 'video' && $slider->video_path)
                                <video muted loop playsinline class="w-full h-full object-cover">
                                    <source src="{{ asset('storage/' . $slider->video_path) }}" type="video/mp4">
                                </video>
                                <div class="absolute inset-0 bg-black/30 flex items-center justify-center">
                                    <div class="w-8 h-8 rounded-full bg-white/20 backdrop-blur-sm border border-white/30 flex items-center justify-center">
                                        <svg class="w-3.5 h-3.5 text-white ml-0.5" fill="currentColor" viewBox="0 0 20 20">
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
                                    <div class="w-8 h-8 rounded-xl bg-[#E9E9E9] flex items-center justify-center">
                                        <svg class="w-4 h-4 text-[#6B705C]/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01"/>
                                        </svg>
                                    </div>
                                    <span class="text-[7px] font-bold text-[#6B705C]/40 uppercase">No Content</span>
                                </div>
                            @endif

                            {{-- Badges overlay --}}
                            <div class="absolute top-2.5 left-2.5 flex gap-1.5">
                                @if($slider->type === 'video')
                                    <span class="inline-flex items-center gap-1 bg-black/70 backdrop-blur-md text-[6px] text-white px-2 py-0.5 rounded-full font-black uppercase tracking-widest">
                                        <span class="w-1 h-1 rounded-full bg-white inline-block"></span>VID
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 bg-[#2F3526]/80 backdrop-blur-md text-[6px] text-white px-2 py-0.5 rounded-full font-black uppercase tracking-widest">
                                        <span class="w-1 h-1 rounded-full bg-white inline-block"></span>IMG
                                    </span>
                                @endif
                                <span class="bg-white/20 backdrop-blur-md text-[6px] text-white px-2 py-0.5 rounded-full font-black border border-white/20">🖥 DSK</span>
                            </div>

                            <span class="absolute top-2.5 right-2.5 bg-black/60 backdrop-blur-md text-[7px] text-white/80 px-2 py-0.5 rounded-full font-black">#{{ $slider->order }}</span>
                        </div>

                        {{-- ── MOBILE THUMB ── --}}
                        @if($slider->image_mobile_path)
                            @php
                                $mobileExt = strtolower(pathinfo($slider->image_mobile_path, PATHINFO_EXTENSION));
                                $isMobileVideo = $mobileExt === 'mp4';
                            @endphp
                            <div class="mx-3 mt-3 bg-[#E9E9E9]/30 rounded-xl p-3 border border-[#E9E9E9] flex items-center gap-3">
                                {{-- Portrait thumbnail --}}
                                <div class="relative flex-shrink-0 w-9 rounded-lg overflow-hidden bg-[#E9E9E9]" style="aspect-ratio:9/16;max-height:64px;">
                                    @if($isMobileVideo)
                                        <video muted loop playsinline class="w-full h-full object-cover">
                                            <source src="{{ asset('storage/' . $slider->image_mobile_path) }}" type="video/mp4">
                                        </video>
                                        <div class="absolute inset-0 bg-black/30 flex items-center justify-center">
                                            <svg class="w-2.5 h-2.5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"/>
                                            </svg>
                                        </div>
                                    @else
                                        <img src="{{ asset('storage/' . $slider->image_mobile_path) }}"
                                             alt="Mobile: {{ $slider->title }}" loading="lazy"
                                             class="w-full h-full object-cover">
                                    @endif
                                </div>
                                {{-- Mobile info --}}
                                <div>
                                    <div class="flex items-center gap-1.5 mb-1">
                                        <span class="text-[7px] font-black text-[#6B705C] uppercase tracking-widest">📱 Mobile</span>
                                        @if($isMobileVideo)
                                            <span class="bg-[#6B705C]/60 text-[5px] text-white px-1.5 py-0.5 rounded-full font-black uppercase">VID</span>
                                        @else
                                            <span class="bg-[#6B705C] text-[5px] text-white px-1.5 py-0.5 rounded-full font-black uppercase">IMG</span>
                                        @endif
                                    </div>
                                    <p class="text-[7px] text-[#6B705C]/70 font-bold uppercase">{{ strtoupper($mobileExt) }} · 9:16 ✓</p>
                                </div>
                            </div>
                        @else
                            <div class="mx-3 mt-3 rounded-xl px-3 py-2.5 border border-dashed border-[#E9E9E9] flex items-center gap-2.5">
                                <div class="w-6 h-6 rounded-lg bg-[#E9E9E9]/60 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-3 h-3 text-[#6B705C]/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <span class="text-[7px] text-[#6B705C]/40 font-bold uppercase tracking-widest">Tidak ada versi mobile</span>
                            </div>
                        @endif

                        {{-- ── INFO ── --}}
                        <div class="px-4 py-3">
                            <h4 class="text-[10px] font-black text-[#2F3526] truncate uppercase tracking-tight mb-2">
                                {{ $slider->title ?? 'Untitled Banner' }}
                            </h4>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    {{-- Desktop type pill --}}
                                    <div class="flex items-center gap-1">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $slider->type === 'video' ? 'bg-black' : 'bg-[#2F3526]' }}"></span>
                                        <span class="text-[7px] font-black text-[#6B705C] uppercase">
                                            🖥 {{ $slider->type === 'video' ? 'MP4' : strtoupper(pathinfo($slider->image_path ?? '', PATHINFO_EXTENSION)) }}
                                        </span>
                                    </div>
                                    {{-- Mobile type pill --}}
                                    @if($slider->image_mobile_path)
                                    <div class="flex items-center gap-1">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#6B705C]"></span>
                                        <span class="text-[7px] font-black text-[#6B705C] uppercase">
                                            📱 {{ strtoupper(pathinfo($slider->image_mobile_path, PATHINFO_EXTENSION)) }}
                                        </span>
                                    </div>
                                    @endif
                                </div>
                                <span class="text-[7px] text-[#6B705C]/50 font-bold uppercase">{{ $slider->created_at->format('d/m/y') }}</span>
                            </div>
                        </div>

                        {{-- ── HAPUS ── --}}
                        <div class="px-4 pb-4">
                            <form action="{{ route('sliders.destroy', $slider) }}" method="POST"
                                  onsubmit="return confirm('Hapus banner \'{{ $slider->title ?? 'ini' }}\'?')">
                                @csrf
                                @method('DELETE')
                                <button class="w-full text-[9px] font-black uppercase tracking-widest text-[#6B705C]/60 py-2.5 rounded-xl border border-[#E9E9E9] hover:bg-red-500 hover:text-white hover:border-red-500 transition-all duration-300">
                                    Hapus Banner
                                </button>
                            </form>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-full">
                        <div class="bg-white border-2 border-dashed border-[#E9E9E9] rounded-3xl py-24 flex flex-col items-center justify-center gap-4">
                            <div class="w-14 h-14 rounded-2xl bg-[#E9E9E9]/60 flex items-center justify-center">
                                <svg class="w-6 h-6 text-[#6B705C]/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div class="text-center">
                                <p class="text-[10px] font-black text-[#6B705C]/50 uppercase tracking-[0.3em]">Belum ada banner aktif</p>
                                <p class="text-[8px] text-[#6B705C]/30 uppercase tracking-widest mt-1">Tambahkan banner pertama di atas</p>
                            </div>
                        </div>
                    </div>
                    @endforelse
                </div>
            </div>

            {{-- Footer --}}
            <div class="flex justify-center pt-4">
                <div class="flex items-center gap-3">
                    <div class="w-6 h-px bg-[#E9E9E9]"></div>
                    <p class="text-[7px] font-black text-[#6B705C]/30 uppercase tracking-[0.5em]">Visual Manager v1.0</p>
                    <div class="w-6 h-px bg-[#E9E9E9]"></div>
                </div>
            </div>

        </div>
    </div>

    <script>
        // File preview on input change
        function previewFile(input, type) {
            const file = input.files[0];
            const prefix = type; // 'desktop' or 'mobile'
            const preview = document.getElementById(prefix + '-preview');
            const empty   = document.getElementById(prefix + '-empty');
            const fname   = document.getElementById(prefix + '-filename');

            if (file) {
                fname.textContent = file.name;
                preview.classList.remove('hidden');
                empty.classList.add('hidden');
            } else {
                preview.classList.add('hidden');
                empty.classList.remove('hidden');
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
                    <span class="animate-pulse tracking-[0.25em]">Mengunggah...</span>
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