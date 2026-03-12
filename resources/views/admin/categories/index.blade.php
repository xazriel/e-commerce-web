<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Kelola Kategori') }}
            </h2>
            <a href="{{ route('admin.dashboard') }}" class="text-sm text-gray-600 hover:text-black underline">
                &larr; Kembali ke Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-bold text-gray-800">Daftar Kategori (Itsar Style)</h3>
                    <a href="{{ route('categories.create') }}" class="bg-black text-black px-4 py-2 rounded-md text-sm font-semibold hover:bg-gray-800 transition">
                        + Tambah Kategori Baru
                    </a>
                </div>

                <div class="overflow-x-auto border rounded-lg">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b bg-gray-50">
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">No</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">Nama Kategori</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">Slug</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($categories as $category)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $loop->iteration }}</td>
                                    <td class="px-6 py-4 text-sm font-bold text-gray-900">{{ $category->name }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500 italic">{{ $category->slug }}</td>
                                    <td class="px-6 py-4 text-sm text-right flex justify-end gap-3">
                                        <a href="{{ route('categories.edit', $category->id) }}" class="text-indigo-600 hover:text-indigo-900 font-medium">Edit</a>
                                        
                                        <span class="text-gray-300">|</span>

                                        <form action="{{ route('categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 font-medium">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-10 text-center text-gray-500">
                                        Data kategori masih kosong. Klik tombol di atas untuk menambah.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>