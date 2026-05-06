@props([
    'showComments' => true,
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('base.app_name', 'AWALAN') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/apps_dasbor.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/apps_dasbor.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=IBM+Plex+Mono:wght@400;500&display=swap">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="catat_body antialiased" x-data="{ sidebarCollapsed: false, mobileOpen: false, profileOpen: false }">
    <div class="catat_shell">
        <aside id="sidebar"
            class="catat_sidebar transition-all duration-300"
            :class="[
                mobileOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0',
                sidebarCollapsed ? 'lg:w-20' : 'lg:w-[280px]'
            ]">
            <div class="catat_brand">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2" :class="sidebarCollapsed ? 'justify-center w-full' : ''">
                    <span class="catat_brand_icon" aria-hidden="true">
                        <i class="ti ti-apps"></i>
                    </span>
                    <span class="catat_logo" x-show="!sidebarCollapsed" x-cloak>AWALAN</span>
                </a>
                <button @click="sidebarCollapsed = !sidebarCollapsed" type="button" class="hidden lg:inline-flex p-1.5 rounded-md text-slate-500 hover:bg-slate-100">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="!sidebarCollapsed">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="sidebarCollapsed" x-cloak>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
            </div>

            <nav class="flex-1 overflow-y-auto px-2 pt-3 pb-4 space-y-2">
                @forelse ($menus as $menu)
                    @php
                        $hasChildren = $menu->children->isNotEmpty();
                    @endphp

                    @if ($hasChildren)
                        @php
                            $groupActive = $menu->children->contains(function ($child) {
                                return $child->route_name ? request()->routeIs($child->route_name . '*') : false;
                            });
                        @endphp
                        <div class="catat_group" x-data="{ open: {{ $groupActive ? 'true' : 'false' }} }" :title="sidebarCollapsed ? '{{ $menu->name }}' : ''">
                            <button type="button" class="catat_group_toggle" x-show="!sidebarCollapsed" x-cloak @click="open = !open">
                                <span class="catat_group_title !pb-0 !px-0">{{ $menu->name }}</span>
                                <svg class="h-4 w-4 text-slate-400 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div class="space-y-1" x-show="sidebarCollapsed || open" x-transition>
                                @foreach ($menu->children as $child)
                                    @php
                                        $childHref = $child->route_name ? route($child->route_name) : ($child->url ?? '#');
                                        $childActive = $child->route_name ? request()->routeIs($child->route_name . '*') : false;
                                    @endphp
                                    <x-admin.sidebar-link href="{{ $childHref }}" :active="$childActive" icon="{{ $child->icon ?? 'default' }}">
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
                        <x-admin.sidebar-link href="{{ $href }}" :active="$isActive" icon="{{ $menu->icon ?? 'default' }}">
                            {{ $menu->name }}
                        </x-admin.sidebar-link>
                    @endif
                @empty
                    <div class="px-3 py-2 text-xs text-slate-500">Menu belum tersedia.</div>
                @endforelse
            </nav>
        </aside>

        <div x-show="mobileOpen" @click="mobileOpen = false" class="fixed inset-0 z-20 bg-slate-900/40 lg:hidden" x-cloak></div>

        <div class="catat_main">
            <header class="catat_topbar">
                <div class="flex items-center gap-2 min-w-0">
                    <button @click="mobileOpen = !mobileOpen" type="button" class="lg:hidden p-1.5 rounded-md text-slate-500 hover:bg-slate-100">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <div class="min-w-0">
                        <p class="text-xs text-slate-500">Workspace</p>
                        <h1 class="catat_title truncate">{{ $pageTitle ?? 'Dashboard' }}</h1>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    @php
                        $hasToolbarActionsSlot = isset($toolbarActions) && trim((string) $toolbarActions) !== '';
                    @endphp

                    @if ($hasToolbarActionsSlot)
                        {{ $toolbarActions }}
                    @else
                        @foreach ($docToolbarActions as $action)
                            <button class="catat_action_btn {{ $action['style'] === 'active' ? 'catat_action_btn_active' : '' }}">{{ $action['label'] }}</button>
                        @endforeach
                    @endif
                    <div class="relative">
                        <button @click="profileOpen = !profileOpen" type="button" class="catat_avatar_btn">
                            <span class="text-[10px] font-bold text-white">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</span>
                        </button>

                        <div x-show="profileOpen"
                            @click.away="profileOpen = false"
                            class="absolute right-0 mt-2 w-52 rounded-xl border border-slate-200 bg-white shadow-lg"
                            style="display: none;">
                            <div class="px-3 py-3 border-b border-slate-100">
                                <p class="text-xs font-semibold text-slate-900">{{ auth()->user()->name }}</p>
                                <p class="text-[11px] text-slate-500 truncate">{{ auth()->user()->email }}</p>
                            </div>
                            <a href="{{ route('profile.show') }}" class="catat_menu_item">Profil Saya</a>
                            <a href="{{ route('profile.edit') }}" class="catat_menu_item">Edit Profil</a>
                            <a href="{{ route('profile.editPassword') }}" class="catat_menu_item">Ubah Kata Sandi</a>
                            <form method="POST" action="{{ route('logout') }}" class="border-t border-slate-100">
                                @csrf
                                <button type="submit" class="catat_menu_item text-rose-600">Logout</button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <div @class([
                'catat_content_wrap' => $showComments,
                'flex flex-1 min-h-0 flex-col' => ! $showComments,
            ])>
                <main @class([
                    'catat_content',
                    'w-full flex-1 min-h-0' => ! $showComments,
                ])>
                    @if (session('success'))
                        <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800" role="alert">{{ session('success') }}</div>
                    @endif
                    @if (session('error'))
                        <div class="mb-4 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800" role="alert">{{ session('error') }}</div>
                    @endif
                    @if (session('warning'))
                        <div class="mb-4 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800" role="alert">{{ session('warning') }}</div>
                    @endif

                    @if (isset($breadcrumbs) && $breadcrumbs->isNotEmpty())
                        <div class="mb-4">
                            <x-admin.breadcrumb :items="$breadcrumbs" />
                        </div>
                    @endif

                    <div>
                        {{ $slot }}
                    </div>
                </main>

                @if ($showComments)
                    <aside class="catat_comments hidden xl:flex">
                        <div class="catat_comments_head">Comments</div>
                        @forelse ($docComments as $comment)
                            <div class="catat_comment_card">
                                <div class="flex items-center gap-2 mb-2">
                                    <div class="h-7 w-7 rounded-full bg-emerald-200 text-emerald-900 text-[10px] font-bold flex items-center justify-center">{{ strtoupper(substr($comment['author'], 0, 2)) }}</div>
                                    <div>
                                        <p class="text-xs font-semibold text-slate-900">{{ $comment['author'] }}</p>
                                        <p class="text-[11px] text-slate-500">{{ $comment['time'] }}</p>
                                    </div>
                                </div>
                                <div class="rounded-md bg-slate-100 px-2 py-1 text-xs text-slate-700">{{ $comment['message'] }}</div>
                            </div>
                        @empty
                            <div class="catat_comment_card text-xs text-slate-500">Belum ada komentar.</div>
                        @endforelse
                        <div class="mt-auto pt-2">
                            <input type="text" class="w-full rounded-md border border-slate-200 px-3 py-2 text-xs" placeholder="Reply..." disabled>
                        </div>
                    </aside>
                @endif
            </div>
        </div>
    </div>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>
