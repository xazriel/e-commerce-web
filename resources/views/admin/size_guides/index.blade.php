<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight tracking-tight">
            {{ __('Size Guide Templates') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Header Section --}}
            <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between px-4 sm:px-0 gap-4">
                <div>
                    <h3 class="text-2xl font-bold text-gray-900">Size Guides</h3>
                    <p class="text-[11px] uppercase tracking-[0.3em] text-gray-400 mt-1">Manajemen Tabel Ukuran Farhana</p>
                    <div class="h-1 w-12 bg-[#5A5A00] mt-4"></div>
                </div>
                
                <a href="{{ route('size-guides.create') }}" class="inline-flex items-center px-6 py-3 bg-black text-white text-[10px] font-bold uppercase tracking-[0.2em] rounded-xl hover:bg-[#5A5A00] transition duration-300 shadow-lg">
                    <span class="mr-2">+</span> Tambah Template
                </a>
            </div>

            @if(session('success'))
                <div class="mb-6 mx-4 sm:mx-0 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 text-[11px] uppercase tracking-widest font-bold">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($templates as $template)
                    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-sm hover:shadow-xl transition-shadow duration-300">
                        <div class="relative h-48 bg-gray-50 flex items-center justify-center overflow-hidden">
                            @if($template->image)
                                <img src="{{ asset('storage/' . $template->image) }}" class="w-full h-full object-contain p-4">
                            @else
                                <span class="text-gray-300 italic text-[10px]">No Image</span>
                            @endif
                            <div class="absolute top-4 right-4 px-3 py-1 bg-white/90 backdrop-blur rounded-full shadow-sm">
                                <span class="text-[9px] font-bold uppercase tracking-widest text-[#5A5A00]">{{ $template->type }}</span>
                            </div>
                        </div>
                        <div class="p-6">
                            <h4 class="text-sm font-bold text-gray-900 uppercase tracking-tight truncate mb-4">{{ $template->name }}</h4>
                            <div class="flex items-center justify-between border-t border-gray-50 pt-4">
                                <a href="{{ route('size-guides.edit', $template->id) }}" class="text-[10px] font-bold uppercase tracking-widest text-blue-500 hover:text-blue-700 transition">Edit</a>
                                <form action="{{ route('size-guides.destroy', $template->id) }}" method="POST" onsubmit="return confirm('Hapus?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-[10px] font-bold uppercase tracking-widest text-red-400 hover:text-red-600">Hapus</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-20 bg-white rounded-2xl border border-dashed border-gray-200 text-center">
                        <p class="text-gray-400 text-[11px] uppercase tracking-[0.2em]">Belum ada data.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>