<x-layouts.admin :title="'Tambah Pengguna'" :pageTitle="'Tambah Pengguna'" :showWidget="false">
    <x-slot:toolbarActions>
        <button type="submit" form="create-user-form" class="btn-compact btn-primary">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Simpan
        </button>
        <a href="{{ route('users.index') }}" class="btn-compact btn-secondary">Batal</a>
    </x-slot:toolbarActions>

    <div class="max-w-2xl mx-auto">
        <div class="card-compact card-pad-compact">
            <form id="create-user-form" action="{{ route('users.store') }}" method="POST" class="space-y-4">
                @csrf

                <!-- Name Field -->
                <div>
                    <label for="name" class="label-compact">Nama Pengguna</label>
                        <x-forms.input type="text" id="name" name="name" :value="old('name')"
                            error="name" placeholder="Masukkan nama pengguna" required />
                    @error('name')
                        <span class="block text-xs text-red-600 mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Email Field -->
                <div>
                    <label for="email" class="label-compact">Email</label>
                        <x-forms.input type="email" id="email" name="email" :value="old('email')"
                            error="email" placeholder="Masukkan email" required />
                    @error('email')
                        <span class="block text-xs text-red-600 mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Role Field -->
                <div>
                    <label for="role" class="label-compact">Peran</label>
                        <select id="role" name="role" 
                            @class([
                            'input-compact',
                            'border-red-500' => $errors->has('role'),
                            ]) required>
                        <option value="">-- Pilih Peran --</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}" @selected(old('role') == $role->name)>
                                {{ ucfirst($role->name) }}
                            </option>
                        @endforeach
                    </select>
                    @error('role')
                        <span class="block text-xs text-red-600 mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password Field -->
                <div>
                    <label for="password" class="label-compact">Kata Sandi</label>
                        <x-forms.input type="password" id="password" name="password"
                            error="password" placeholder="Masukkan kata sandi" required />
                    @error('password')
                        <span class="block text-xs text-red-600 mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password Confirmation Field -->
                <div>
                    <label for="password_confirmation" class="label-compact">Konfirmasi Kata Sandi</label>
                        <x-forms.input type="password" id="password_confirmation" name="password_confirmation"
                            error="password_confirmation" placeholder="Konfirmasi kata sandi" required />
                    @error('password_confirmation')
                        <span class="block text-xs text-red-600 mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Is Active Checkbox -->
                <div class="flex items-center gap-2">
                    <input type="checkbox" id="is_active" name="is_active" value="1" 
                           @checked(old('is_active', true)) class="h-4 w-4 rounded border-gray-300">
                    <label for="is_active" class="text-xs font-medium text-gray-600">Aktifkan Pengguna</label>
                </div>
            </form>
        </div>
    </div>
</x-layouts.admin>
