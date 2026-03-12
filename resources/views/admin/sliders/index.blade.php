<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Kelola Banner Slider</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
                <form action="{{ route('sliders.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="flex flex-col">
                            <label class="text-[10px] font-bold text-gray-500 uppercase mb-1">Desktop (Landscape)</label>
                            <input type="file" name="image" class="border p-2 rounded text-sm">
                        </div>
                        <div class="flex flex-col">
                            <label class="text-[10px] font-bold text-gray-500 uppercase mb-1">Mobile (Portrait/Square)</label>
                            <input type="file" name="image_mobile" class="border p-2 rounded text-sm">
                        </div>
                        <div class="flex flex-col">
                            <label class="text-[10px] font-bold text-gray-500 uppercase mb-1">Judul Banner</label>
                            <input type="text" name="title" placeholder="Contoh: Ramadan Sale" class="border p-2 rounded text-sm">
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="w-full bg-black text-white px-4 py-2.5 rounded font-bold hover:bg-gray-800 transition uppercase text-xs tracking-widest">
                                Upload Banner
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @forelse($sliders as $slider)
                <div class="bg-white p-2 rounded shadow-sm border">
                    <div class="relative group aspect-video overflow-hidden rounded mb-2 bg-gray-100">
                        <img src="{{ asset('storage/' . $slider->image_path) }}" class="w-full h-full object-cover">
                        @if($slider->image_mobile_path)
                            <div class="absolute top-1 right-1 bg-black/50 text-[8px] text-white px-1.5 py-0.5 rounded uppercase">Mobile Ready</div>
                        @endif
                    </div>
                    <p class="text-[10px] text-gray-400 truncate mb-2 px-1">{{ $slider->title ?? 'Tanpa Judul' }}</p>
                    
                    <form action="{{ route('sliders.destroy', $slider) }}" method="POST" onsubmit="return confirm('Hapus banner ini?')">
                        @csrf 
                        @method('DELETE')
                        <button class="w-full text-red-500 text-[10px] uppercase font-bold py-2 border border-red-50 rounded hover:bg-red-50 transition">
                            Hapus
                        </button>
                    </form>
                </div>
                @empty
                <div class="col-span-full bg-gray-50 p-10 text-center rounded-lg border-2 border-dashed">
                    <p class="text-gray-400 text-xs uppercase tracking-widest">Belum ada banner yang diupload.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>