<x-layouts.admin :title="'Edit Profil'" :pageTitle="'Edit Profil'" :showWidget="false">
    <x-slot:toolbarActions>
        <button type="submit" form="edit-profile-form" class="btn-compact btn-primary">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            Simpan Perubahan
        </button>
        <a href="{{ route('profile.show') }}" class="btn-compact btn-secondary">Batal</a>
    </x-slot:toolbarActions>

    <div class="max-w-2xl mx-auto">
        <div class="card-compact card-pad-compact">
            <form id="edit-profile-form" action="{{ route('profile.update') }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <!-- Name Field -->
                <div>
                    <label for="name" class="label-compact">Nama Lengkap</label>
                        <x-forms.input type="text" id="name" name="name" :value="old('name', auth()->user()->name)"
                            error="name" placeholder="Masukkan nama lengkap" required />
                    @error('name')
                        <span class="block text-xs text-red-600 mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Email Field -->
                <div>
                    <label for="email" class="label-compact">Email</label>
                        <x-forms.input type="email" id="email" name="email" :value="old('email', auth()->user()->email)"
                            error="email" placeholder="Masukkan email" required />
                    @error('email')
                        <span class="block text-xs text-red-600 mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Info Message -->
                <div class="p-3 bg-blue-50 border border-blue-200 rounded-lg">
                    <p class="text-xs text-blue-800">
                        <strong>Tips:</strong> Untuk mengubah kata sandi, gunakan halaman "Ubah Kata Sandi" di menu Keamanan.
                    </p>
                </div>
            </form>
        </div>
    </div>
</x-layouts.admin>
