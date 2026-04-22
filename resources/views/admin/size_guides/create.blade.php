<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight tracking-tight">
            {{ __('Add New Size Guide') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-8 flex items-center justify-between px-4 sm:px-0">
                <div>
                    <h3 class="text-2xl font-bold text-gray-900 uppercase tracking-widest">New Template</h3>
                    <div class="h-1 w-12 bg-[#5A5A00] mt-4"></div>
                </div>
                <a href="{{ route('size-guides.index') }}" class="text-xs font-bold text-gray-500 hover:text-black transition uppercase tracking-widest">&larr; Back</a>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-8">
                    <form action="{{ route('size-guides.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                            <div class="space-y-6">
                                <div>
                                    <label class="block text-[10px] font-bold uppercase tracking-widest text-gray-900 mb-2">Template Name</label>
                                    <input type="text" name="name" value="{{ old('name') }}" class="w-full border-gray-200 focus:border-[#5A5A00] focus:ring-0 text-sm py-3 px-4 rounded-lg">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold uppercase tracking-widest text-gray-900 mb-2">Category Type</label>
                                    <select name="type" class="w-full border-gray-200 focus:border-[#5A5A00] focus:ring-0 text-sm py-3 px-4 rounded-lg">
                                        <option value="general">General</option>
                                        <option value="abaya">Abaya</option>
                                        <option value="khimar">Khimar</option>
                                        <option value="kids">Kids</option>
                                    </select>
                                </div>
                                <div class="pt-4">
                                    <button type="submit" class="w-full bg-black text-white py-4 text-[10px] font-bold uppercase tracking-[0.3em] hover:bg-[#5A5A00] transition duration-300 shadow-lg rounded-xl">Save Template</button>
                                </div>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-widest text-gray-900 mb-2">Upload Image</label>
                                <div class="relative h-64 border-2 border-dashed border-gray-100 rounded-2xl bg-gray-50 flex flex-col items-center justify-center overflow-hidden">
                                    <input type="file" name="image" id="imageInput" class="absolute inset-0 opacity-0 cursor-pointer z-20">
                                    <div id="placeholder" class="text-center">
                                        <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest">Click to upload</p>
                                    </div>
                                    <img id="imagePreview" class="hidden absolute inset-0 w-full h-full object-contain z-10 bg-white">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('imageInput').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('imagePreview');
            const placeholder = document.getElementById('placeholder');
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
</x-app-layout>