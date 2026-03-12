<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Kategori Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-sm rounded-lg">
                <form action="{{ route('categories.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Nama Kategori</label>
                        <input type="text" name="name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-black focus:border-black" placeholder="Contoh: Pashmina" required>
                    </div>
                    <div class="flex justify-end gap-2">
                        <a href="{{ route('categories.index') }}" class="bg-gray-200 px-4 py-2 rounded-md">Batal</a>
                        <button type="submit" class="bg-black text-white px-4 py-2 rounded-md">Simpan Kategori</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>