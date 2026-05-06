<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - {{ config('base.app_name', 'AWALAN') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-stone-50 text-slate-900 antialiased">
    @php
        $appName = config('base.app_name', 'AWALAN');
        $companyName = company_name();
        $companyLogo = company_logo();
        $illustrationPath = asset('images/modern-office-desk-with-flat-design.png');
    @endphp

    <div class="min-h-screen lg:grid lg:grid-cols-[minmax(0,480px)_1fr]">
        <section class="flex min-h-screen items-center px-6 py-10 sm:px-10 lg:px-12 xl:px-16">
            <div class="mx-auto w-full max-w-md">
                <div class="mb-10">
                    <div class="mb-6 flex items-center gap-4">
                        @if ($companyLogo)
                            <div class="flex h-14 w-14 items-center justify-center overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
                                <img src="{{ $companyLogo }}" alt="{{ $appName }}" class="h-full w-full object-cover">
                            </div>
                        @else
                            <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-emerald-500 text-white shadow-sm">
                                <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 4h6v6h-6z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M14 4h6v6h-6z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 14h6v6h-6z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M14 14h6v6h-6z" />
                                </svg>
                            </div>
                        @endif
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-500">AWALAN boilerplate</p>
                            <h1 class="text-3xl font-extrabold tracking-tight text-slate-950">{{ $appName }}</h1>
                        </div>
                    </div>
                </div>

                <form action="{{ route('login.submit') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label for="email" class="label-compact">Email atau Username</label>
                        <x-forms.input
                            id="email"
                            name="email"
                            type="text"
                            required
                            :value="old('email')"
                            error="email"
                            placeholder="Masukkan email atau username Anda" />
                        @error('email')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="label-compact">Password</label>
                        <div class="relative">
                            <x-forms.input
                                id="password"
                                name="password"
                                type="password"
                                required
                                class="pr-10"
                                error="password"
                                placeholder="••••••••" />
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

                    <div>
                        <button 
                            type="submit" 
                            class="btn-compact btn-primary w-full">
                            Login
                        </button>
                    </div>
                </form>

                <div class="mt-6 px-1 text-sm text-slate-500">
                    <p>Gunakan akun yang sudah terdaftar untuk masuk ke dashboard AWALAN.</p>
                </div>
            </div>
        </section>

        <aside class="hidden border-l border-slate-200 bg-white lg:flex lg:min-h-screen lg:flex-col lg:justify-between">
            <div class="flex flex-1 items-center justify-center px-10 py-12 xl:px-16">
                <div class="w-full max-w-2xl">
                    <img src="{{ $illustrationPath }}" alt="Ilustrasi menulis untuk halaman login AWALAN" class="mx-auto w-full max-w-xl object-contain">
                </div>
            </div>
            <div class="px-8 pb-8 xl:px-12">
                <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-[11px] leading-5 text-slate-500">
                    Illustration credit: <a href="https://www.magnific.com/free-vector/modern-office-desk-with-flat-design_2848408.htm#fromView=search&page=1&position=45&uuid=1bfd5288-f3e7-4d07-b884-e7afdcc97c25&query=office?log-in=google" target="_blank" rel="noopener noreferrer" class="font-semibold text-slate-700 underline decoration-slate-300 underline-offset-2 hover:text-slate-900">Image by freepik</a>
                </div>
            </div>
        </aside>
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
