<x-app-layout>
    <div class="max-w-2xl mx-auto py-12 px-4">
        <div class="bg-white p-8 rounded-xl border border-gray-100 shadow-sm">
            <div class="mb-8">
                <h2 class="text-xs font-bold uppercase tracking-[0.3em] text-[#5A5A00]">New Shipping Address</h2>
                <p class="text-gray-400 text-[11px] uppercase mt-2">Please fill in your delivery details accurately for JNE shipping.</p>
            </div>
            
            <form action="{{ route('address.store') }}" method="POST">
                @csrf
                {{-- Input tersembunyi jika user datang dari checkout --}}
                @if(request('from') == 'checkout')
                    <input type="hidden" name="from_checkout" value="1">
                @endif

                <div class="space-y-6">
                    {{-- Label Alamat --}}
                    <div>
                        <label class="text-[10px] uppercase font-bold tracking-widest text-gray-500 block mb-2">Address Label (e.g., Home, Office)</label>
                        <input type="text" name="label" placeholder="Home" class="w-full border-gray-200 border-x-0 border-t-0 border-b p-2 text-sm focus:ring-0 focus:border-[#5A5A00] transition bg-transparent">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Nama Penerima --}}
                        <div>
                            <label class="text-[10px] uppercase font-bold tracking-widest text-gray-500 block mb-2">Recipient Name</label>
                            <input type="text" name="recipient_name" required class="w-full border-gray-200 border-x-0 border-t-0 border-b p-2 text-sm focus:ring-0 focus:border-[#5A5A00] transition bg-transparent">
                        </div>
                        {{-- Phone --}}
                        <div>
                            <label class="text-[10px] uppercase font-bold tracking-widest text-gray-500 block mb-2">Phone Number</label>
                            <input type="text" name="phone" required placeholder="08..." class="w-full border-gray-200 border-x-0 border-t-0 border-b p-2 text-sm focus:ring-0 focus:border-[#5A5A00] transition bg-transparent">
                        </div>
                    </div>

                    {{-- Alamat Lengkap --}}
                    <div>
                        <label class="text-[10px] uppercase font-bold tracking-widest text-gray-500 block mb-2">Street Address / House Number</label>
                        <textarea name="address" rows="2" required class="w-full border-gray-200 border-x-0 border-t-0 border-b p-2 text-sm focus:ring-0 focus:border-[#5A5A00] transition bg-transparent resize-none"></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Provinsi --}}
                        <div>
                            <label class="text-[10px] uppercase font-bold tracking-widest text-gray-500 block mb-2">Province</label>
                            <input type="text" name="province_name" required placeholder="e.g. Jawa Barat" class="w-full border-gray-200 border-x-0 border-t-0 border-b p-2 text-sm focus:ring-0 focus:border-[#5A5A00] transition bg-transparent">
                        </div>
                        {{-- Kota --}}
                        <div>
                            <label class="text-[10px] uppercase font-bold tracking-widest text-gray-500 block mb-2">City / Regency</label>
                            <input type="text" name="city_name" required placeholder="e.g. Bandung" class="w-full border-gray-200 border-x-0 border-t-0 border-b p-2 text-sm focus:ring-0 focus:border-[#5A5A00] transition bg-transparent">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Kecamatan --}}
                        <div>
                            <label class="text-[10px] uppercase font-bold tracking-widest text-gray-500 block mb-2">District (Kecamatan)</label>
                            <input type="text" name="district_name" required placeholder="e.g. Coblong" class="w-full border-gray-200 border-x-0 border-t-0 border-b p-2 text-sm focus:ring-0 focus:border-[#5A5A00] transition bg-transparent">
                        </div>
                        {{-- Kode Pos --}}
                        <div>
                            <label class="text-[10px] uppercase font-bold tracking-widest text-gray-500 block mb-2">Postal Code</label>
                            <input type="text" name="postal_code" required class="w-full border-gray-200 border-x-0 border-t-0 border-b p-2 text-sm focus:ring-0 focus:border-[#5A5A00] transition bg-transparent">
                        </div>
                    </div>

                    <div class="flex justify-between items-center pt-8">
                        <a href="{{ url()->previous() }}" class="text-[10px] uppercase tracking-widest text-gray-400 hover:text-black transition">Cancel</a>
                        <button type="submit" class="bg-black text-white px-10 py-3 text-[10px] uppercase tracking-[0.2em] font-bold hover:bg-[#5A5A00] transition shadow-lg">
                            Save Address
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>