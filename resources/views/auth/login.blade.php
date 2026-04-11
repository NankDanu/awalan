<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - {{ company_name() ?? config('app.name', 'AWALAN') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-gray-50">
    <div class="min-h-screen flex">
        <!-- Left Side - Background Image -->
        @php
            $companyName = company_name() ?? config('app.name', 'AWALAN');
            $appName = config('app.name', 'AWALAN');
            $appVersion = config('app.version', '1.0.0');
            $loginBackground = company_login_background();
            $defaultLoginBackground = asset('images/background/afif-ramdhasuma-KvMw3jKXNZE-unsplash.jpg');
            $backgroundImage = $loginBackground ?: $defaultLoginBackground;
            $usesDefaultLoginBackground = blank($loginBackground);
            $companyLogo = company_logo();
        @endphp
        <div class="hidden lg:flex lg:w-3/5 relative overflow-hidden"
             style="background-image: url('{{ $backgroundImage }}'); background-size: cover; background-position: center;">
            <!-- Logo aplikasi di kiri atas -->
            <div class="absolute top-6 left-6 z-10">
                <div class="flex items-center space-x-3">
                    @if ($companyLogo)
                        <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-lg overflow-hidden">
                            <img src="{{ $companyLogo }}" alt="{{ $companyName }}" class="w-full h-full object-cover">
                        </div>
                    @else
                        <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                            </svg>
                        </div>
                    @endif
                    <div class="text-white">
                        <div class="text-xl font-bold tracking-wide">{{ $appName }} <small>v{{ $appVersion }}</small></div>
                        <div class="text-xs opacity-90">Laravel Boilerplate</div>
                    </div>
                </div>
            </div>

            <!-- Background Pattern -->
            <div class="absolute inset-0 opacity-10">
                <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'1\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
            </div>

            @if ($usesDefaultLoginBackground)
                <div class="absolute bottom-4 left-6 right-6 z-10">
                    <p class="inline-block rounded bg-black/40 px-3 py-1.5 text-[10px] leading-relaxed text-white/80 backdrop-blur-sm">
                        Photo by <a href="https://unsplash.com/@javaistan?utm_source=unsplash&utm_medium=referral&utm_content=creditCopyText" target="_blank" rel="noopener noreferrer" class="underline hover:text-white">Afif Ramdhasuma</a> on <a href="https://unsplash.com/photos/an-aerial-view-of-a-city-at-night-KvMw3jKXNZE?utm_source=unsplash&utm_medium=referral&utm_content=creditCopyText" target="_blank" rel="noopener noreferrer" class="underline hover:text-white">Unsplash</a>
                    </p>
                </div>
            @endif

            <!-- Center Content -->
            <div class="relative z-10 flex items-center justify-center w-full p-10">
                <div class="text-center text-white">
                    <!-- Building Placeholder -->
                    {{-- <div class="mb-6 max-w-lg mx-auto">
                        <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 border border-white/20">
                            <svg class="w-40 h-40 mx-auto text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                    </div>
                    <h1 class="text-3xl font-semibold mb-3">Selamat Datang</h1>
                    <p class="text-base text-blue-100">{{ $companyName }}</p> --}}
                </div>
            </div>
        </div>

        <!-- Right Side - Login Form -->
        <div class="flex-1 flex items-center justify-center p-6 lg:w-2/5">
            <div class="w-full max-w-sm">
                <!-- Logo Area -->
                <div class="text-center mb-6">
                    @if ($companyLogo)
                        <div class="inline-flex items-center justify-center w-20 h-20 mb-4 rounded-full overflow-hidden bg-white shadow-sm">
                            <img src="{{ $companyLogo }}" alt="{{ $companyName }}" class="w-full h-full object-cover">
                        </div>
                        <h2 class="text-2xl font-semibold text-gray-900 mb-1">{{ $companyName }}</h2>
                    @else
                        <h2 class="text-2xl font-semibold text-gray-900 mb-1">{{ $companyName }}</h2>
                    @endif

                    <p class="text-xs text-gray-600">Portal Login</p>
                </div>

                <!-- Login Form -->
                <form action="{{ route('login') }}" method="POST" class="space-y-4">
                    @csrf

                    <!-- Email/Username Field -->
                    <div>
                        <label for="email" class="label-compact">
                            Email atau Username
                        </label>
                        <input 
                            id="email" 
                            name="email" 
                            type="text" 
                            required 
                            value="{{ old('email') }}"
                            class="input-compact @error('email') border-red-500 @enderror"
                            placeholder="Masukkan email atau username Anda">
                        @error('email')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Field -->
                    <div>
                        <label for="password" class="label-compact">
                            Password
                        </label>
                        <div class="relative">
                            <input 
                                id="password" 
                                name="password" 
                                type="password" 
                                required
                                class="input-compact pr-10 @error('password') border-red-500 @enderror"
                                placeholder="••••••••">
                            <button 
                                type="button" 
                                onclick="togglePassword()"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                                <svg id="eye-icon" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember & Forgot Password -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input 
                                id="remember" 
                                name="remember" 
                                type="checkbox" 
                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="remember" class="ml-2 block text-xs text-gray-700">
                                Ingat Saya
                            </label>
                        </div>

                        <div class="text-xs">
                            <a href="#" class="font-medium text-blue-600 hover:text-blue-500">
                                Lupa Password?
                            </a>
                        </div>
                    </div>

                    <!-- Login Button -->
                    <div>
                        <button 
                            type="submit" 
                            class="btn-compact btn-primary w-full">
                            Login
                        </button>
                    </div>
                </form>

                <!-- Footer -->
                <div class="mt-6 text-center text-xs text-gray-600">
                    <p>
                        Diracik santai oleh <a href="https://github.com/NankDanu" class="font-medium text-blue-600 hover:text-blue-500">Nank</a>, bersama AI dan kopi <span class="text-amber-700">☕</span>
                    </p>
                    <p class="mt-1">Dari Cikarang, dengan ❤️</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                `;
            } else {
                passwordInput.type = 'password';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                `;
            }
        }
    </script>
</body>
</html>
