<x-app-layout>
    <div class="max-w-4xl mx-auto px-4 py-16">
        
        {{-- Header --}}
        <div class="flex justify-between items-end mb-12 border-b border-gray-100 pb-8">
            <div>
                <span class="text-[10px] uppercase tracking-[0.4em] text-gray-400 block mb-2">Account</span>
                <h1 class="text-2xl font-light tracking-widest uppercase italic">Address Book</h1>
            </div>
            <a href="{{ route('address.create') }}" class="bg-black text-white px-8 py-3 text-[9px] font-bold uppercase tracking-[0.2em] hover:bg-[#5A5A00] transition shadow-sm">
                + Add New Address
            </a>
        </div>

        @if(session('success'))
            <div class="mb-8 p-4 bg-[#6B6631] text-white text-[9px] uppercase tracking-[0.3em] font-bold text-center">
                {{ session('success') }}
            </div>
        @endif

        {{-- Address Grid --}}
        @if($addresses->isEmpty())
            <div class="py-20 text-center border-2 border-dashed border-gray-100 bg-white">
                <p class="text-[10px] text-gray-400 uppercase tracking-[0.4em] mb-4 italic">No saved addresses found.</p>
                <a href="{{ route('address.create') }}" class="text-[9px] font-bold uppercase border-b border-black pb-1">Create your first address</a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($addresses as $address)
                    <div class="bg-white border {{ $address->is_default ? 'border-black' : 'border-gray-100' }} p-8 relative group transition-all shadow-sm">
                        @if($address->is_default)
                            <span class="absolute top-0 right-0 bg-black text-white text-[8px] px-4 py-1 uppercase tracking-[0.2em] font-bold">Default</span>
                        @endif
                        
                        <h5 class="text-[10px] font-black uppercase tracking-[0.2em] text-[#5A5A00] mb-4">
                            {{ $address->label ?? 'Shipping Address' }}
                        </h5>

                        <div class="text-[12px] text-gray-600 leading-relaxed uppercase tracking-wider space-y-1">
                            <p class="text-black font-bold">{{ $address->recipient_name }}</p>
                            <p>{{ $address->phone }}</p>
                            <p class="mt-2">{{ $address->address }}</p>
                            <p>{{ $address->district_name }}, {{ $address->city_name }}</p>
                            <p>{{ $address->province_name }} — {{ $address->postal_code }}</p>
                        </div>

                        <div class="mt-8 pt-6 border-t border-gray-50 flex items-center justify-between">
                            <div class="flex gap-6">
                                @if(!$address->is_default)
                                    <form action="{{ route('address.select', $address->id) }}" method="POST">
                                        @csrf
                                        <button class="text-[9px] font-bold uppercase tracking-widest text-black hover:text-[#5A5A00] transition">
                                            Select
                                        </button>
                                    </form>
                                @endif
                            </div>

                            <form action="{{ route('address.destroy', $address->id) }}" method="POST" onsubmit="return confirm('Remove this address?')">
                                @csrf
                                @method('DELETE')
                                <button class="text-[9px] font-bold uppercase tracking-widest text-red-400 hover:text-red-600 transition">
                                    Remove
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <div class="mt-12">
            <a href="{{ route('dashboard') }}" class="text-[10px] font-bold uppercase tracking-[0.3em] text-gray-400 hover:text-black flex items-center gap-2">
                ← Back to Dashboard
            </a>
        </div>
    </div>
</x-app-layout>