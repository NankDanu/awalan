<x-layouts.admin :title="'Profil Saya'" :pageTitle="'Profil Saya'" :showComments="false">
    <x-slot:toolbarActions>
        <a href="{{ route('profile.edit') }}" class="btn-compact btn-primary">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
            Edit Profil
        </a>
        <a href="{{ route('profile.editPassword') }}" class="btn-compact btn-secondary text-xs">
            Ubah Kata Sandi
        </a>
    </x-slot:toolbarActions>

    <div class="max-w-3xl mx-auto">
        <!-- Profile Header Card -->
        <div class="card-compact mb-4">
            <div class="card-pad-compact">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-lg">
                            <span class="text-white font-bold text-2xl">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </span>
                        </div>
                        <div>
                            <h2 class="text-xl font-semibold text-gray-900">{{ auth()->user()->name }}</h2>
                            <p class="text-sm text-gray-600">{{ auth()->user()->email }}</p>
                            <div class="flex gap-2 mt-2">
                                @foreach(auth()->user()->roles as $role)
                                    <span class="chip-compact bg-blue-100 text-blue-800">
                                        {{ ucfirst($role->name) }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Information Card -->
        <div class="card-compact mb-4">
            <div class="card-pad-compact border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Informasi Pribadi</h3>
            </div>
            <div class="card-pad-compact">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- Name -->
                    <div>
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Nama Lengkap</label>
                        <p class="mt-1 text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Email</label>
                        <p class="mt-1 text-sm font-medium text-gray-900">{{ auth()->user()->email }}</p>
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Status</label>
                        <div class="mt-1">
                            @if(auth()->user()->is_active)
                                <span class="chip-compact bg-green-100 text-green-800">Aktif</span>
                            @else
                                <span class="chip-compact bg-red-100 text-red-800">Tidak Aktif</span>
                            @endif
                        </div>
                    </div>

                    <!-- Member Since -->
                    <div>
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Bergabung Sejak</label>
                        <p class="mt-1 text-sm font-medium text-gray-900">
                            {{ auth()->user()->created_at->translatedFormat('d F Y') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Roles & Permissions Card -->
        <div class="card-compact mb-4">
            <div class="card-pad-compact border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Peran & Wewenang</h3>
            </div>
            <div class="card-pad-compact">
                <div class="space-y-4">
                    <!-- Roles -->
                    <div>
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide block mb-2">Peran (Role)</label>
                        <div class="flex flex-wrap gap-2">
                            @forelse(auth()->user()->roles as $role)
                                <span class="chip-compact bg-blue-100 text-blue-800">
                                    {{ ucfirst($role->name) }}
                                </span>
                            @empty
                                <span class="text-sm text-gray-500">Tidak ada peran</span>
                            @endforelse
                        </div>
                    </div>

                    <!-- Permissions -->
                    <div>
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide block mb-2">Wewenang (Permission)</label>
                        <div class="flex flex-wrap gap-2">
                            @forelse(auth()->user()->permissions as $permission)
                                <span class="chip-compact bg-purple-100 text-purple-800">
                                    {{ $permission->name }}
                                </span>
                            @empty
                                <span class="text-sm text-gray-500">Tidak ada wewenang direct</span>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Security Card -->
        <div class="card-compact">
            <div class="card-pad-compact border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Keamanan</h3>
            </div>
            <div class="card-pad-compact space-y-3">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-900">Kata Sandi</p>
                        <p class="text-xs text-gray-600">Ubah kata sandi akun Anda secara berkala</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>
