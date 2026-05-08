<x-layouts.admin :title="'Edit Permission: ' . $permission->name" :pageTitle="'Edit Permission'" :showWidget="false">
    <x-slot:toolbarActions>
        <button type="submit" form="edit-permission-form" class="btn-compact btn-primary">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            Perbarui
        </button>
        <a href="{{ route('permissions.index') }}" class="btn-compact btn-secondary">Batal</a>
    </x-slot:toolbarActions>

    <div class="max-w-2xl mx-auto">
        <div class="card-compact card-pad-compact">
            <form id="edit-permission-form" action="{{ route('permissions.update', $permission->id) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label for="name" class="label-compact">Nama Permission</label>
                        <x-forms.input type="text" id="name" name="name" :value="old('name', $permission->name)"
                            error="name" placeholder="Contoh: view-users" required />
                    @error('name')
                        <span class="block text-xs text-red-600 mt-1">{{ $message }}</span>
                    @enderror
                </div>
            </form>
        </div>
    </div>
</x-layouts.admin>
