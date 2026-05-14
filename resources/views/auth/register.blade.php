<x-guest-layout>
    {{-- Container utama dipaksa full screen --}}
    <div class="fixed inset-0 min-h-screen w-full flex flex-col lg:flex-row bg-[#2F3526] lg:bg-[#FDFDFB] overflow-hidden" style="min-width: 100vw;">
        
        {{-- SISI KIRI: Branding & Aesthetic Elements --}}
        <div class="w-full lg:w-1/2 h-16 lg:h-full bg-[#2F3526] flex flex-col justify-between pt-10 px-16 pb-16 relative overflow-hidden shrink-0">
            
            {{-- ELEMEN GEOMETRIS (Hanya Desktop) --}}
            <div class="hidden lg:block absolute top-[35%] -right-20 w-80 h-80 border border-white/[0.03] rounded-full pointer-events-none"></div>
            <div class="hidden lg:block absolute bottom-20 right-12 w-32 h-32 border border-white/[0.05] rounded-full pointer-events-none"></div>
            <div class="hidden lg:block absolute left-10 top-1/4 h-1/2 w-[1px] bg-gradient-to-b from-transparent via-white/[0.05] to-transparent pointer-events-none"></div>

            {{-- LOGO & TAHUN (Hidden di Mobile agar tampilan putih bisa naik) --}}
            <div class="hidden lg:block relative z-10">
                <a href="/" class="block">
                    <img src="{{ Storage::url('LOGO-FARHANA-NEW-TRANSPARENT.png') }}"
                         alt="Farhana"
                         class="h-40 w-auto object-contain brightness-0 invert opacity-95 -ml-12 -mt-2">
                </a>
                <p class="text-[#6B705C] text-[10px] tracking-[0.4em] uppercase mt-0 ml-1">Est. MMXXVI</p>
            </div>

            {{-- QUOTE (Hanya Desktop) --}}
            <div class="hidden lg:block relative z-10">
                <p class="text-white/30 text-[9px] uppercase tracking-[0.4em] leading-loose max-w-xs ml-1">
                    "Simplicity is the ultimate sophistication."
                </p>
            </div>

            {{-- Background Curve (Hanya Desktop) --}}
            <div class="hidden lg:block absolute inset-0 opacity-5 pointer-events-none">
                <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                    <path d="M0 100 C 30 0 70 0 100 100" stroke="white" fill="transparent" stroke-width="0.1" />
                </svg>
            </div>
        </div>

        {{-- SISI KANAN: Form Register --}}
        {{-- flex-1 memastikan area ini mengisi sisa layar di bawah header hijau mobile --}}
        <div class="flex-1 w-full lg:w-1/2 flex flex-col bg-[#2F3526] lg:bg-white relative overflow-hidden">
            
            {{-- Card Putih --}}
            {{-- rounded-t-[40px] diletakkan di sini agar tetap melengkung secara statis --}}
            <div class="flex-1 w-full bg-white rounded-t-[40px] lg:rounded-none shadow-[0_-10px_40px_rgba(0,0,0,0.1)] lg:shadow-none flex flex-col overflow-hidden">
                
                {{-- Container Scrollable (Hanya bagian ini yang scroll) --}}
                <div class="flex-1 overflow-y-auto px-8 lg:px-0 py-10">
                    <div class="w-full max-w-[380px] mx-auto">
                        
                        {{-- Header Register --}}
                        <div class="flex flex-col items-start space-y-2 mb-10">
                            <h3 class="text-3xl font-extralight text-[#2F3526] tracking-tight leading-tight uppercase">
                                Register
                            </h3>
                            <p class="text-[9px] font-bold tracking-[0.3em] uppercase text-gray-400">Join Farhana Official</p>
                        </div>

                        <form method="POST" action="{{ route('register') }}" class="space-y-8">
                            @csrf

                            {{-- Name --}}
                            <div class="border-b border-gray-100 pb-2 focus-within:border-[#2F3526] transition-colors duration-500">
                                <label class="block text-[9px] font-bold tracking-[0.2em] uppercase text-gray-400 mb-1">Full Name</label>
                                <input type="text" name="name" :value="old('name')" required autofocus 
                                       class="w-full border-none bg-transparent p-0 focus:ring-0 text-[11px] tracking-wide text-gray-800 placeholder-gray-200 placeholder:text-[10px]"
                                       placeholder="YOUR FULL NAME">
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            {{-- Email --}}
                            <div class="border-b border-gray-100 pb-2 focus-within:border-[#2F3526] transition-colors duration-500">
                                <label class="block text-[9px] font-bold tracking-[0.2em] uppercase text-gray-400 mb-1">Email Address</label>
                                <input type="email" name="email" :value="old('email')" required 
                                       class="w-full border-none bg-transparent p-0 focus:ring-0 text-sm tracking-wide text-gray-800 placeholder-gray-200 placeholder:text-[10px]"
                                       placeholder="USERNAME@GMAIL.COM">
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>

                            {{-- Password dengan Show/Hide --}}
                            <div class="border-b border-gray-100 pb-2 focus-within:border-[#2F3526] transition-colors duration-500" x-data="{ show: false }">
                                <label class="block text-[9px] font-bold tracking-[0.2em] uppercase text-gray-400 mb-1">Password</label>
                                <div class="relative">
                                    <input :type="show ? 'text' : 'password'" name="password" required 
                                           class="w-full border-none bg-transparent p-0 pr-10 focus:ring-0 text-sm tracking-wide text-gray-800 placeholder-gray-200"
                                           placeholder="••••••••">
                                    <button type="button" @click="show = !show" class="absolute right-0 top-1/2 -translate-y-1/2 text-gray-300 hover:text-[#2F3526] transition-colors focus:outline-none">
                                        <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <svg x-show="show" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                                        </svg>
                                    </button>
                                </div>
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            </div>

                            {{-- Confirm Password dengan Show/Hide --}}
                            <div class="border-b border-gray-100 pb-2 focus-within:border-[#2F3526] transition-colors duration-500" x-data="{ show: false }">
                                <label class="block text-[9px] font-bold tracking-[0.2em] uppercase text-gray-400 mb-1">Confirm Password</label>
                                <div class="relative">
                                    <input :type="show ? 'text' : 'password'" name="password_confirmation" required 
                                           class="w-full border-none bg-transparent p-0 pr-10 focus:ring-0 text-sm tracking-wide text-gray-800 placeholder-gray-200"
                                           placeholder="••••••••">
                                    <button type="button" @click="show = !show" class="absolute right-0 top-1/2 -translate-y-1/2 text-gray-300 hover:text-[#2F3526] transition-colors focus:outline-none">
                                        <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <svg x-show="show" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                                        </svg>
                                    </button>
                                </div>
                                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                            </div>

                            {{-- Button Register --}}
                            <button type="submit" class="w-full py-5 bg-[#2F3526] text-white text-[10px] font-bold tracking-[0.5em] uppercase hover:bg-[#1a1f16] transition-all shadow-lg active:scale-[0.98]">
                                Create Account
                            </button>

                            {{-- Link Login (Padding bottom dikurangi) --}}
                            <p class="text-left text-[10px] tracking-[0.2em] text-gray-400 uppercase mt-2 pb-6">
                                Already have an account? <a href="{{ route('login') }}" class="text-[#2F3526] font-bold border-b border-[#2F3526]/20 ml-1">Log In</a>
                            </p>
                        </form>
                    </div>
                </div> {{-- End Scrollable Container --}}

            </div>
        </div>
    </div>
</x-guest-layout>