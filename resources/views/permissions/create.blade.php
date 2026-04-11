<x-layouts.admin :title="'Tambah Permission'" :pageTitle="'Tambah Permission'">
    <div class="max-w-2xl">
        <div class="card-compact card-pad-compact">
            <form action="{{ route('permissions.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="name" class="label-compact">Nama Permission</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}"
                           class="input-compact @error('name') border-red-500 @enderror"
                           placeholder="Contoh: view-users" required>
                    @error('name')
                        <span class="block text-xs text-red-600 mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex gap-3 border-t border-gray-200 pt-4">
                    <button type="submit" class="btn-compact btn-primary">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Simpan
                    </button>
                    <a href="{{ route('permissions.index') }}" class="btn-compact btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.admin>
