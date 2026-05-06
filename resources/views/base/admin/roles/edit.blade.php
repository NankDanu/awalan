<x-layouts.admin :title="'Edit Role: ' . $role->name" :pageTitle="'Edit Role'" :showComments="false">
    <x-slot:toolbarActions>
        <button type="submit" form="edit-role-form" class="btn-compact btn-primary">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            Perbarui
        </button>
        <a href="{{ route('roles.index') }}" class="btn-compact btn-secondary">Batal</a>
    </x-slot:toolbarActions>

    <div class="max-w-3xl mx-auto">
        <div class="card-compact card-pad-compact">
            <form id="edit-role-form" action="{{ route('roles.update', $role->id) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label for="name" class="label-compact">Nama Role</label>
                        <x-forms.input type="text" id="name" name="name" :value="old('name', $role->name)"
                            error="name" placeholder="Masukkan nama role" required />
                    @error('name')
                        <span class="block text-xs text-red-600 mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="label-compact">Permission</label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2">
                        @foreach ($permissions as $permission)
                            <label class="flex items-center gap-2 text-xs text-gray-700">
                                <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                       @checked(in_array($permission->name, old('permissions', $rolePermissions->toArray()), true))
                                       class="h-4 w-4 rounded border-gray-300">
                                <span>{{ $permission->name }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('permissions')
                        <span class="block text-xs text-red-600 mt-1">{{ $message }}</span>
                    @enderror
                </div>
            </form>
        </div>
    </div>
</x-layouts.admin>
