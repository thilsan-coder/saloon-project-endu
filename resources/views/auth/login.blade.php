<x-guest-layout>
    <div class="w-full max-w-md">
        <!-- Main Card Container (Salon Card Theme) -->
        <div class="bg-white border border-stone-200/90 rounded-2xl sm:rounded-3xl p-8 sm:p-10 shadow-xl shadow-stone-200/60 relative overflow-hidden transition-all hover:border-rose-200">
            
            <!-- Top Accent Bar -->
            <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-rose-500 via-amber-400 to-rose-600"></div>

            <!-- Header / Branding -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-gradient-to-tr from-rose-500 to-amber-400 text-white shadow-lg shadow-rose-500/25 mb-3.5 transform hover:scale-105 transition-transform duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="6" cy="6" r="3"/>
                        <path d="M8.12 8.12 12 12"/>
                        <circle cx="6" cy="18" r="3"/>
                        <path d="M14.8 14.8 20 20"/>
                        <path d="M14.8 9.2 20 4"/>
                        <path d="M8.12 15.88 12 12"/>
                    </svg>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-stone-900 tracking-tight">Velvet & Co.</h1>
                <p class="text-xs sm:text-sm text-stone-500 mt-1.5 font-medium">Sign in to your account</p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-6" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-stone-600 mb-2">
                        Email Address
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-stone-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect width="20" height="16" x="2" y="4" rx="2"/>
                                <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
                            </svg>
                        </div>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                            placeholder="name@example.com"
                            class="w-full pl-11 pr-4 py-3 bg-white border border-stone-300 rounded-xl text-stone-900 placeholder-stone-400 text-sm focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-600 transition-all duration-200">
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs text-rose-600" />
                </div>

                <!-- Password -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-stone-600">
                            Password
                        </label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-xs font-semibold text-rose-600 hover:text-rose-700 transition-colors">
                                Forgot password?
                            </a>
                        @endif
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-stone-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect width="18" height="11" x="3" y="11" rx="2" ry="2"/>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                            </svg>
                        </div>
                        <input id="password" type="password" name="password" required autocomplete="current-password"
                            placeholder="••••••••"
                            class="w-full pl-11 pr-11 py-3 bg-white border border-stone-300 rounded-xl text-stone-900 placeholder-stone-400 text-sm focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-600 transition-all duration-200">
                        <button type="button" onclick="togglePasswordVisibility()" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-stone-400 hover:text-stone-600 transition-colors" tabIndex="-1">
                            <svg id="eye-icon" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                            <svg id="eye-off-icon" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/>
                                <path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/>
                                <path d="M6.61 6.61A13.52 13.52 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/>
                                <line x1="2" x2="22" y1="2" y2="22"/>
                            </svg>
                        </button>
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-xs text-rose-600" />
                </div>

                <!-- Remember Me -->
                <div class="flex items-center">
                    <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                        <input id="remember_me" type="checkbox" name="remember" 
                            class="w-4 h-4 rounded border-stone-300 text-rose-600 focus:ring-rose-500/30 transition-all">
                        <span class="ms-2.5 text-xs text-stone-600 font-medium group-hover:text-stone-900 transition-colors">Remember me</span>
                    </label>
                </div>

                <!-- Submit Button (btn-salon-primary styling) -->
                <div class="pt-2">
                    <button type="submit" 
                        class="w-full py-3.5 px-4 rounded-xl bg-gradient-to-r from-rose-600 to-rose-700 hover:from-rose-700 hover:to-rose-800 text-white font-bold text-sm shadow-md shadow-rose-600/30 hover:shadow-lg hover:shadow-rose-600/40 hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200">
                        Login
                    </button>
                </div>
            </form>
        </div>

        <!-- Footer Notice -->
        <p class="text-center text-xs text-stone-500 mt-6 font-medium">
            &copy; {{ date('Y') }} Velvet & Co. Salon Management. All rights reserved.
        </p>
    </div>

    <script>
        function togglePasswordVisibility() {
            const input = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');
            const eyeOffIcon = document.getElementById('eye-off-icon');

            if (input.type === 'password') {
                input.type = 'text';
                eyeIcon.classList.add('hidden');
                eyeOffIcon.classList.remove('hidden');
            } else {
                input.type = 'password';
                eyeIcon.classList.remove('hidden');
                eyeOffIcon.classList.add('hidden');
            }
        }
    </script>
</x-guest-layout>
