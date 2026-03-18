<x-guest-layout>
    <div class="min-h-screen flex flex-col justify-center items-center bg-white px-4">
        
        <div class="mb-12">
            <a href="/" class="text-3xl font-light tracking-[0.5em] uppercase text-gray-900">
                Farhana
            </a>
        </div>

        <div class="w-full max-w-sm">
            <div class="text-center mb-10">
                <h2 class="text-[10px] font-bold tracking-[0.3em] uppercase text-gray-400 mb-2">Welcome Back</h2>
                <h1 class="text-xl font-light tracking-[0.2em] uppercase text-gray-900">Login to Your Account</h1>
            </div>

            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <div>
                    <label for="email" class="block text-[10px] font-bold tracking-[0.2em] uppercase text-gray-700 mb-2">Email Address</label>
                    <input id="email" type="email" name="email" :value="old('email')" required autofocus 
                        class="w-full border-b border-gray-300 border-t-0 border-x-0 bg-transparent px-0 py-2 focus:ring-0 focus:border-black transition-all duration-300 placeholder-gray-300 text-sm tracking-widest"
                        placeholder="EMAIL@EXAMPLE.COM">
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <label for="password" class="block text-[10px] font-bold tracking-[0.2em] uppercase text-gray-700 mb-2">Password</label>
                    <input id="password" type="password" name="password" required autocomplete="current-password"
                        class="w-full border-b border-gray-300 border-t-0 border-x-0 bg-transparent px-0 py-2 focus:ring-0 focus:border-black transition-all duration-300 placeholder-gray-300 text-sm tracking-widest"
                        placeholder="••••••••">
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div class="flex items-center justify-between mt-4">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-black shadow-sm focus:ring-black" name="remember">
                        <span class="ml-2 text-[10px] tracking-widest uppercase text-gray-500">{{ __('Remember me') }}</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a class="text-[10px] tracking-widest uppercase text-gray-400 hover:text-black transition" href="{{ route('password.request') }}">
                            {{ __('Forgot password?') }}
                        </a>
                    @endif
                </div>

                <div class="pt-6">
                    <button type="submit" class="w-full py-4 bg-black text-white text-[10px] tracking-[0.4em] uppercase hover:bg-gray-800 transition duration-500 italic">
                        Sign In
                    </button>
                </div>

                <div class="text-center mt-8">
                    <p class="text-[10px] tracking-widest text-gray-400 uppercase">
                        Don't have an account? 
                        <a href="{{ route('register') }}" class="text-black font-bold border-b border-black pb-1 ml-1 hover:text-gray-600 hover:border-gray-600 transition">Register Now</a>
                    </p>
                </div>
            </form>
        </div>
        
        <div class="mt-20 text-[9px] text-gray-300 uppercase tracking-[0.4em]">
            &copy; 2026 Farhana Official.
        </div>
    </div>
</x-guest-layout>