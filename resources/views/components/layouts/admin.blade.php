<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name', 'AWALAN') }}</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-gray-100">
    <div class="min-h-screen flex" x-data="{ sidebarCollapsed: false }">
        <!-- Sidebar -->
        <aside id="sidebar" class="fixed inset-y-0 left-0 z-30 w-64 h-screen transition-all duration-300 transform lg:translate-x-0 lg:static lg:inset-auto lg:h-auto" 
               :class="sidebarCollapsed ? 'lg:w-20' : 'lg:w-64'"
               :style="sidebarCollapsed ? 'width: 80px' : 'width: 256px'">
            <div class="flex min-h-0 flex-col h-full bg-white border-r border-gray-200">
                <!-- Logo -->
                <div class="flex items-center justify-between h-14 px-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
                    <a href="{{ route('dashboard') }}" class="flex items-center" :class="sidebarCollapsed ? 'justify-center w-full' : ''">
                        <span class="text-xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent" x-show="!sidebarCollapsed">AWALAN</span>
                        <span class="text-lg font-bold text-blue-600" x-show="sidebarCollapsed">A</span>
                    </a>
                    <button @click="sidebarCollapsed = !sidebarCollapsed" type="button" class="hidden lg:inline-flex p-1 rounded-md text-gray-600 hover:bg-gray-100 focus:outline-none">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="!sidebarCollapsed">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="sidebarCollapsed">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 min-h-0 px-3 py-4 space-y-1 overflow-y-auto lg:overflow-visible">
                    @forelse ($menus as $menu)
                        @php
                            $hasChildren = $menu->children->isNotEmpty();
                        @endphp

                        @if ($hasChildren)
                            <div class="pt-2" :title="sidebarCollapsed ? '{{ $menu->name }}' : ''">
                                <div class="px-3 py-2">
                                    <div class="text-[11px] font-semibold text-gray-500 uppercase tracking-wider"
                                         x-cloak
                                         x-show="!sidebarCollapsed">
                                        {{ $menu->name }}
                                    </div>
                                    <div class="flex justify-center" x-cloak x-show="sidebarCollapsed">
                                        <span class="h-0.5 w-6 rounded-full bg-gray-300"></span>
                                    </div>
                                </div>
                                <div class="space-y-1 pl-2">
                                    @foreach ($menu->children as $child)
                                        @php
                                            $childHref = $child->route_name ? route($child->route_name) : ($child->url ?? '#');
                                            $childActive = $child->route_name ? request()->routeIs($child->route_name . '*') : false;
                                        @endphp
                                        <x-admin.sidebar-link
                                            href="{{ $childHref }}"
                                            :active="$childActive"
                                            icon="{{ $child->icon ?? 'default' }}">
                                            {{ $child->name }}
                                        </x-admin.sidebar-link>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            @php
                                $href = $menu->route_name ? route($menu->route_name) : ($menu->url ?? '#');
                                $isActive = $menu->route_name ? request()->routeIs($menu->route_name . '*') : false;
                            @endphp
                            <x-admin.sidebar-link
                                href="{{ $href }}"
                                :active="$isActive"
                                icon="{{ $menu->icon ?? 'default' }}">
                                {{ $menu->name }}
                            </x-admin.sidebar-link>
                        @endif
                    @empty
                        <div class="px-3 py-2 text-xs text-gray-500">Menu belum tersedia.</div>
                    @endforelse
                </nav>
                
            </div>
        </aside>

        <!-- Mobile sidebar overlay -->
        <div id="sidebar-overlay" class="fixed inset-0 z-20 bg-black bg-opacity-50 lg:hidden hidden"></div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-w-0">
            <!-- Top Navbar -->
            <header class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-10">
                <div class="flex items-center justify-between h-14 px-4 sm:px-6 lg:px-8 gap-4">
                    <!-- Mobile menu button -->
                    <button id="mobile-menu-button" type="button" class="lg:hidden p-1.5 rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-100 focus:outline-none">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    <!-- Page Title -->
                    <div class="flex-1 min-w-0">
                        <h1 class="text-base font-semibold text-gray-900 truncate">
                            {{ $pageTitle ?? 'Dashboard' }}
                        </h1>
                    </div>

                    <!-- Right side items -->
                    <div class="flex items-center gap-2">
                        <!-- Notifications -->
                        <button type="button" class="p-1.5 rounded-full text-gray-600 hover:text-gray-900 hover:bg-gray-100 focus:outline-none">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                        </button>

                        <!-- User dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" type="button" class="flex items-center gap-2 p-1.5 rounded-lg hover:bg-gray-100 focus:outline-none">
                                <div class="w-7 h-7 rounded-full bg-blue-600 flex items-center justify-center">
                                    <span class="text-white font-semibold text-xs">
                                        {{ substr(auth()->user()->name, 0, 2) }}
                                    </span>
                                </div>
                                <svg class="h-4 w-4 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <!-- Dropdown menu -->
                            <div x-show="open" 
                                 @click.away="open = false"
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 class="absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 divide-y divide-gray-100"
                                 style="display: none;">
                                <div class="px-4 py-3">
                                    <p class="text-xs font-medium text-gray-900">{{ auth()->user()->name }}</p>
                                    <p class="text-[11px] text-gray-500 truncate">{{ auth()->user()->email }}</p>
                                </div>
                                <div class="py-1">
                                    <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-100">
                                        Profil Saya
                                    </a>
                                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-100">
                                        Edit Profil
                                    </a>
                                    <a href="{{ route('profile.editPassword') }}" class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-100">
                                        Ubah Kata Sandi
                                    </a>
                                </div>
                                <div class="py-1">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-xs text-red-600 hover:bg-gray-100">
                                            Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-3 sm:p-4 lg:p-6">
                <!-- Flash Messages -->
                @if (session('success'))
                    <div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center" role="alert">
                        <svg class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg flex items-center" role="alert">
                        <svg class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                @if (session('warning'))
                    <div class="mb-4 bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded-lg flex items-center" role="alert">
                        <svg class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <span>{{ session('warning') }}</span>
                    </div>
                @endif

                <!-- Page Content -->
                {{ $slot }}
            </main>
        </div>
    </div>

    <!-- Alpine.js for dropdown -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Mobile Menu Toggle Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            const menuButton = document.getElementById('mobile-menu-button');

            menuButton.addEventListener('click', function() {
                sidebar.classList.toggle('-translate-x-full');
                overlay.classList.toggle('hidden');
            });

            overlay.addEventListener('click', function() {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            });
        });
    </script>
</body>
</html>
