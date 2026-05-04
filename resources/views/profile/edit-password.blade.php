<x-layouts.admin :title="'Ubah Kata Sandi'" :pageTitle="'Ubah Kata Sandi'" :showComments="false">
    <x-slot:toolbarActions>
        <button type="submit" form="edit-password-form" class="btn-compact btn-primary">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            Perbarui Kata Sandi
        </button>
        <a href="{{ route('profile.show') }}" class="btn-compact btn-secondary">Batal</a>
    </x-slot:toolbarActions>

    <div class="max-w-2xl mx-auto">
        <div class="card-compact card-pad-compact">
            <form id="edit-password-form" action="{{ route('profile.updatePassword') }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <!-- Current Password Field -->
                <div>
                    <label for="current_password" class="label-compact">Kata Sandi Saat Ini</label>
                        <x-forms.input type="password" id="current_password" name="current_password"
                            error="current_password" placeholder="Masukkan kata sandi saat ini" required />
                    @error('current_password')
                        <span class="block text-xs text-red-600 mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- New Password Field -->
                <div>
                    <label for="password" class="label-compact">Kata Sandi Baru</label>
                        <x-forms.input type="password" id="password" name="password"
                            error="password" placeholder="Masukkan kata sandi baru (minimal 8 karakter)" required />
                    @error('password')
                        <span class="block text-xs text-red-600 mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Confirm Password Field -->
                <div>
                    <label for="password_confirmation" class="label-compact">Konfirmasi Kata Sandi Baru</label>
                        <x-forms.input type="password" id="password_confirmation" name="password_confirmation"
                            error="password_confirmation" placeholder="Konfirmasi kata sandi baru" required />
                    @error('password_confirmation')
                        <span class="block text-xs text-red-600 mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Security Tips -->
                <div class="p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <p class="text-xs text-yellow-800 font-medium mb-2">💡 Tips Keamanan:</p>
                    <ul class="text-xs text-yellow-700 space-y-1 list-disc list-inside">
                        <li>Gunakan kata sandi yang kuat dengan kombinasi huruf, angka, dan simbol</li>
                        <li>Jangan gunakan tanggal lahir atau nama dalam kata sandi</li>
                        <li>Ubah kata sandi secara berkala untuk keamanan maksimal</li>
                    </ul>
                </div>
            </form>
        </div>
    </div>
</x-layouts.admin>
