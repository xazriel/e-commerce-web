<x-guest-layout>
    <style>
        /* CSS untuk menghilangkan scrollbar di berbagai browser */
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>

    {{-- Container utama --}}
    <div class="fixed inset-0 min-h-screen w-full flex flex-col lg:flex-row bg-[#2F3526] lg:bg-[#FDFDFB] overflow-hidden" style="min-width: 100vw;">
        
        {{-- SISI KIRI (Desktop) / HEADER VISUAL (Mobile) --}}
        <div class="w-full lg:w-1/2 h-[25vh] lg:h-full bg-[#2F3526] flex flex-col justify-between pt-8 lg:pt-10 px-10 lg:px-16 pb-6 lg:pb-16 relative overflow-hidden">
            
            {{-- ELEMEN GEOMETRIS --}}
            <div class="absolute top-[35%] -right-20 w-80 h-80 border border-white/[0.03] rounded-full pointer-events-none"></div>
            <div class="absolute bottom-20 right-12 w-32 h-32 border border-white/[0.05] rounded-full pointer-events-none"></div>

            {{-- LOGO & TAHUN --}}
            <div class="relative z-10">
                <a href="{{ route('home') }}" class="block">
                    <img src="{{ Storage::url('LOGO-FARHANA-NEW-TRANSPARENT.png') }}"
                         alt="Farhana"
                         class="h-[120px] lg:h-40 w-auto object-contain brightness-0 invert opacity-95 -ml-8 lg:-ml-12 -mt-2">
                </a>
               <p class="text-[#6B705C] text-[9px] lg:text-[10px] tracking-[0.4em] uppercase -mt-5 ml-1">Est. MMXXVI</p>
            </div>

            {{-- QUOTE (Desktop Only) --}}
            <div class="hidden lg:block relative z-10">
                <p class="text-white/30 text-[9px] uppercase tracking-[0.4em] leading-loose max-w-xs ml-1">
                    "Simplicity is the ultimate sophistication."
                </p>
            </div>

            {{-- Background Curve --}}
            <div class="absolute inset-0 opacity-5 pointer-events-none">
                <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                    <path d="M0 100 C 30 0 70 0 100 100" stroke="white" fill="transparent" stroke-width="0.1" />
                </svg>
            </div>
        </div>

        {{-- SISI KANAN (Desktop) / FORM CARD (Mobile) --}}
        <div class="w-full lg:w-1/2 h-[75vh] lg:h-full flex flex-col items-center justify-start lg:justify-center p-0 lg:p-8 bg-[#2F3526] lg:bg-white relative">
            
            {{-- CARD PUTIH - Menambahkan class 'no-scrollbar' untuk menghilangkan garis abu di kanan --}}
            <div class="w-full h-full lg:h-auto max-w-none lg:max-w-[380px] py-10 px-10 lg:px-0 bg-white rounded-t-[40px] lg:rounded-none overflow-y-auto no-scrollbar shadow-[0_-10px_40px_rgba(0,0,0,0.1)] lg:shadow-none">
                
                {{-- Header Login --}}
                <div class="flex flex-col items-start space-y-2 mb-10 lg:mb-12">
                    <h3 class="text-2xl lg:text-3xl font-extralight text-[#2F3526] tracking-tight leading-tight uppercase">
                        Login
                    </h3>
                    <p class="text-[9px] font-bold tracking-[0.3em] uppercase text-gray-400">Access your account</p>
                </div>

                <form method="POST" action="{{ route('login') }}" class="space-y-8 lg:space-y-10">
                    @csrf

                    {{-- Input Email --}}
                    <div class="border-b border-gray-100 pb-2 focus-within:border-[#2F3526] transition-colors duration-500">
                        <label class="block text-[9px] font-bold tracking-[0.2em] uppercase text-gray-400 mb-1">Email Address</label>
                        <input type="email" name="email" :value="old('email')" required autofocus 
                            class="w-full border-none bg-transparent p-0 focus:ring-0 text-sm tracking-wide text-gray-800 placeholder-gray-200"
                            placeholder="username@gmail.com">
                    </div>

                    {{-- Input Password --}}
                    <div class="border-b border-gray-100 pb-2 focus-within:border-[#2F3526] transition-colors duration-500" 
                        x-data="{ show: false }">
                        <div class="flex justify-between items-center mb-1">
                            <label class="text-[9px] font-bold tracking-[0.2em] uppercase text-gray-400">Password</label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-[9px] tracking-widest text-gray-300 hover:text-[#2F3526]">Forgot?</a>
                            @endif
                        </div>
                        <div class="relative">
                            <input :type="show ? 'text' : 'password'" 
                                name="password" 
                                required
                                class="w-full border-none bg-transparent p-0 pr-10 focus:ring-0 text-sm tracking-wide text-gray-800 placeholder-gray-200"
                                placeholder="••••••••">
                            
                            <button type="button" 
                                    @click="show = !show" 
                                    class="absolute right-0 top-1/2 -translate-y-1/2 text-gray-300 hover:text-[#2F3526] transition-colors focus:outline-none">
                                
                                <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>

                                <svg x-show="show" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- ERROR NOTIFICATION --}}
                    @if ($errors->any())
                        <div class="animate-in fade-in slide-in-from-top-1 duration-300 text-center lg:text-left">
                            <p class="text-[10px] tracking-[0.2em] uppercase text-red-500 font-bold">
                                Incorrect email or password.
                            </p>
                        </div>
                    @endif

                    {{-- Submit Button --}}
                    <div class="pt-2">
                        <button type="submit" class="w-full py-5 bg-[#2F3526] text-white text-[10px] font-bold tracking-[0.5em] uppercase hover:bg-[#1a1f16] transition-all shadow-lg active:scale-[0.98] rounded-0 lg:rounded-none">
                            Login
                        </button>
                    </div>

                    {{-- Link Register --}}
                    <p class="text-center lg:text-left text-[10px] tracking-[0.2em] text-gray-400 uppercase pt-2 pb-10">
                        New here? <a href="{{ route('register') }}" class="text-[#2F3526] font-bold border-b border-[#2F3526]/20 ml-1">Join Us</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>