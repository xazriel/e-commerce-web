<x-guest-layout>
    <div class="min-h-screen flex flex-col justify-center items-center bg-white px-4">
        
        <div class="mb-12">
            <a href="/" class="text-3xl font-light tracking-[0.5em] uppercase text-gray-900">
                Farhana
            </a>
        </div>

        <div class="w-full max-w-sm">
            <div class="text-center mb-10">
                <h2 class="text-[10px] font-bold tracking-[0.3em] uppercase text-gray-400 mb-2">Create Account</h2>
                <h1 class="text-xl font-light tracking-[0.2em] uppercase text-gray-900">Join Farhana Official</h1>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-6">
                @csrf

                <div>
                    <label for="name" class="block text-[10px] font-bold tracking-[0.2em] uppercase text-gray-700 mb-2">Full Name</label>
                    <input id="name" type="text" name="name" :value="old('name')" required autofocus autocomplete="name"
                        class="w-full border-b border-gray-300 border-t-0 border-x-0 bg-transparent px-0 py-2 focus:ring-0 focus:border-black transition-all duration-300 placeholder-gray-300 text-sm tracking-widest uppercase"
                        placeholder="YOUR FULL NAME">
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <label for="email" class="block text-[10px] font-bold tracking-[0.2em] uppercase text-gray-700 mb-2">Email Address</label>
                    <input id="email" type="email" name="email" :value="old('email')" required autocomplete="username"
                        class="w-full border-b border-gray-300 border-t-0 border-x-0 bg-transparent px-0 py-2 focus:ring-0 focus:border-black transition-all duration-300 placeholder-gray-300 text-sm tracking-widest"
                        placeholder="EMAIL@EXAMPLE.COM">
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <label for="password" class="block text-[10px] font-bold tracking-[0.2em] uppercase text-gray-700 mb-2">Password</label>
                    <input id="password" type="password" name="password" required autocomplete="new-password"
                        class="w-full border-b border-gray-300 border-t-0 border-x-0 bg-transparent px-0 py-2 focus:ring-0 focus:border-black transition-all duration-300 placeholder-gray-300 text-sm tracking-widest"
                        placeholder="••••••••">
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <label for="password_confirmation" class="block text-[10px] font-bold tracking-[0.2em] uppercase text-gray-700 mb-2">Confirm Password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                        class="w-full border-b border-gray-300 border-t-0 border-x-0 bg-transparent px-0 py-2 focus:ring-0 focus:border-black transition-all duration-300 placeholder-gray-300 text-sm tracking-widest"
                        placeholder="••••••••">
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <div class="pt-6">
                    <button type="submit" class="w-full py-4 bg-black text-white text-[10px] tracking-[0.4em] uppercase hover:bg-gray-800 transition duration-500 italic">
                        Register
                    </button>
                </div>

                <div class="text-center mt-8">
                    <p class="text-[10px] tracking-widest text-gray-400 uppercase">
                        Already have an account? 
                        <a href="{{ route('login') }}" class="text-black font-bold border-b border-black pb-1 ml-1 hover:text-gray-600 hover:border-gray-600 transition">Log In</a>
                    </p>
                </div>
            </form>
        </div>
        
        <div class="mt-20 text-[9px] text-gray-300 uppercase tracking-[0.4em]">
            &copy; 2026 Farhana Official. All Rights Reserved.
        </div>
    </div>
</x-guest-layout>