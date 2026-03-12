<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h2 class="text-2xl font-bold mb-6  text-gray-700" >Tambah Produk Baru (Abaya/Khimar)</h2>

                <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 gap-6">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block font-medium text-sm text-gray-700">Nama Produk</label>
                                <input type="text" name="name" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            </div>
                            <div>
                                <label class="block font-medium text-sm text-gray-700">Kategori</label>
                                <select name="category_id" class="w-full border-gray-300 rounded-md shadow-sm">
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block font-medium text-sm text-gray-700">Harga (Rp)</label>
                                <input type="number" name="price" class="w-full border-gray-300 rounded-md shadow-sm" required>
                            </div>
                            <div>
                                <label class="block font-medium text-sm text-gray-700">Stok Awal</label>
                                <input type="number" name="stock" class="w-full border-gray-300 rounded-md shadow-sm" required>
                            </div>
                        </div>

                        <div>
                            <label class="block font-medium text-sm text-gray-700">Deskripsi Detail</label>
                            <textarea name="description" rows="5" class="w-full border-gray-300 rounded-md shadow-sm"></textarea>
                        </div>

                        <div>
                            <label class="block font-medium text-sm text-gray-700">Foto Detail Produk (Bisa banyak sekaligus)</label>
                            <input type="file" name="images[]" multiple class="w-full mt-1 text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
                            <p class="text-xs text-gray-500 mt-1">*Pilih banyak foto detail kain, jahitan, dan tampak depan/belakang.</p>
                        </div>

                        <button type="submit" class="bg-black text-gray px-6 py-2 rounded-md hover:bg-gray-800 transition">
                            Simpan Produk
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>