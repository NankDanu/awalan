<x-layouts.admin :title="'Tambah Permission'" :pageTitle="'Tambah Permission'" :showComments="false">
    <x-slot:toolbarActions>
        <button type="submit" form="create-permission-form" class="btn-compact btn-primary">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Simpan
        </button>
        <a href="{{ route('permissions.index') }}" class="btn-compact btn-secondary">Batal</a>
    </x-slot:toolbarActions>

    <div class="max-w-2xl mx_auto">
        <div class="card-compact card-pad-compact">
            <form id="create-permission-form" action="{{ route('permissions.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="name" class="label-compact">Nama Permission</label>
                        <x-forms.input type="text" id="name" name="name" :value="old('name')"
                            error="name" placeholder="Contoh: view-users" required />
                    @error('name')
                        <span class="block text-xs text-red-600 mt-1">{{ $message }}</span>
                    @enderror
                </div>
            </form>
        </div>
    </div>
</x-layouts.admin>
