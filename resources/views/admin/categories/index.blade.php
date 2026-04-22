<x-app-layout>
    <x-slot name="header">
        {{-- Header Responsive: Stacked on Mobile, Row on Desktop --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:justify-between sm:items-center">
            <div>
                <h2 class="font-semibold text-lg md:text-xl text-gray-800 leading-tight tracking-tight">
                    {{ __('Kelola Kategori') }}
                </h2>
                <p class="text-[9px] text-gray-400 uppercase tracking-widest mt-1 hidden sm:block">
                    Admin Panel / Categories Management
                </p>
            </div>

            <div class="flex items-center">
                <a href="{{ route('admin.dashboard') }}" 
                   class="inline-flex items-center text-[10px] font-bold text-gray-400 uppercase tracking-widest hover:text-black transition-all group">
                    <span class="mr-2 transform group-hover:-translate-x-1 transition-transform">&larr;</span>
                    <span class="underline decoration-gray-200 underline-offset-4 group-hover:decoration-black">Kembali ke Dashboard</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Alert Section --}}
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 text-[10px] uppercase font-bold tracking-[0.2em] animate-fade-in-down">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden border border-gray-100">
                
                {{-- Action Bar --}}
                <div class="p-6 border-b border-gray-50 flex flex-col gap-4 sm:flex-row sm:justify-between sm:items-center bg-white">
                    <div>
                        <h3 class="text-sm font-bold text-gray-800 uppercase tracking-widest">Daftar Kategori</h3>
                        <div class="flex items-center gap-2 mt-1">
                            <div class="h-0.5 w-8 bg-black"></div>
                            <p class="text-[9px] text-gray-400 uppercase tracking-widest">Total: {{ $categories->count() }} Data</p>
                        </div>
                    </div>
                    
                    <a href="{{ route('categories.create') }}" 
                       class="inline-flex justify-center items-center bg-black text-white px-5 py-3 rounded text-[10px] font-bold uppercase tracking-[0.2em] hover:bg-gray-800 transition shadow-lg shadow-gray-200 active:scale-95">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Kategori
                    </a>
                </div>

                {{-- Table Section --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse min-w-[700px]">
                        <thead>
                            <tr class="bg-gray-50/50">
                                <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest w-16">No</th>
                                <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Nama Kategori</th>
                                <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Slug</th>
                                <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($categories as $category)
                                <tr class="hover:bg-gray-50/80 transition duration-150 group">
                                    <td class="px-6 py-4 text-[11px] font-medium text-gray-400">#{{ $loop->iteration }}</td>
                                    <td class="px-6 py-4">
                                        <span class="text-sm font-bold text-gray-900 tracking-tight group-hover:text-black transition-colors">
                                            {{ $category->name }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-[10px] bg-gray-100 text-gray-500 px-2.5 py-1 rounded font-mono lowercase">
                                            {{ $category->slug }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex justify-end items-center gap-4">
                                            <a href="{{ route('categories.edit', $category->id) }}" 
                                               class="text-[10px] font-bold text-gray-800 uppercase tracking-tighter hover:underline decoration-2 underline-offset-4">
                                                Edit
                                            </a>
                                            
                                            <span class="text-gray-200 text-xs">/</span>

                                            <form action="{{ route('categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Hapus kategori {{ $category->name }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-[10px] font-bold text-red-500 uppercase tracking-tighter hover:text-red-700 transition-colors">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-20 text-center">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-10 h-10 text-gray-200 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                                            </svg>
                                            <p class="text-[10px] text-gray-400 uppercase tracking-[0.2em]">Belum ada kategori terdaftar.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            {{-- Footer Branding --}}
            <div class="mt-8 flex flex-col items-center">
                <div class="h-px w-20 bg-gray-100"></div>
                <p class="mt-4 text-[8px] text-gray-300 uppercase tracking-[0.5em]">Farhana System v1.0</p>
            </div>
        </div>
    </div>

    <style>
        @keyframes fade-in-down {
            0% { opacity: 0; transform: translateY(-10px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-down {
            animation: fade-in-down 0.5s ease-out;
        }
    </style>
</x-app-layout>