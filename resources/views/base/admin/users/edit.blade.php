<x-layouts.admin :title="'Edit Pengguna: ' . $user->name" :pageTitle="'Edit Pengguna'" :showWidget="false">
    <x-slot:toolbarActions>
        <button type="submit" form="edit-user-form" class="btn-compact btn-primary">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            Perbarui
        </button>
        <a href="{{ route('users.index') }}" class="btn-compact btn-secondary">Batal</a>
    </x-slot:toolbarActions>

    <div class="max-w-2xl">
        <div class="card-compact card-pad-compact">
            <form id="edit-user-form" action="{{ route('users.update', $user->id) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <!-- Name Field -->
                <div>
                    <label for="name" class="label-compact">Nama Pengguna</label>
                        <x-forms.input type="text" id="name" name="name" :value="old('name', $user->name)"
                            error="name" placeholder="Masukkan nama pengguna" required />
                    @error('name')
                        <span class="block text-xs text-red-600 mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Email Field -->
                <div>
                    <label for="email" class="label-compact">Email</label>
                        <x-forms.input type="email" id="email" name="email" :value="old('email', $user->email)"
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
                            <option value="{{ $role->name }}" 
                                    @selected(old('role') ? old('role') == $role->name : $userRoles->contains($role->name))>
                                {{ ucfirst($role->name) }}
                            </option>
                        @endforeach
                    </select>
                    @error('role')
                        <span class="block text-xs text-red-600 mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password Field (Optional on Edit) -->
                <div>
                    <label for="password" class="label-compact">Kata Sandi (Kosongkan jika tidak ingin mengubah)</label>
                        <x-forms.input type="password" id="password" name="password"
                            error="password" placeholder="Masukkan kata sandi baru (opsional)" />
                    @error('password')
                        <span class="block text-xs text-red-600 mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password Confirmation Field -->
                <div>
                    <label for="password_confirmation" class="label-compact">Konfirmasi Kata Sandi</label>
                        <x-forms.input type="password" id="password_confirmation" name="password_confirmation"
                            error="password_confirmation" placeholder="Konfirmasi kata sandi baru (opsional)" />
                    @error('password_confirmation')
                        <span class="block text-xs text-red-600 mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Is Active Checkbox -->
                <div class="flex items-center gap-2">
                    <input type="checkbox" id="is_active" name="is_active" value="1" 
                           @checked(old('is_active', $user->is_active)) class="h-4 w-4 rounded border-gray-300">
                    <label for="is_active" class="text-xs font-medium text-gray-600">Aktifkan Pengguna</label>
                </div>
            </form>
        </div>
    </div>
</x-layouts.admin>
