<x-layouts.admin :title="'Pengaturan Perusahaan'" :pageTitle="'Pengaturan Perusahaan'" :showComments="false">
    <x-slot:toolbarActions>
        <a href="{{ route('company-settings.index') }}" class="kt-btn kt-btn-outline">Batal</a>
        <button type="submit" form="company-settings-edit-form" class="kt-btn">Perbarui Pengaturan</button>
    </x-slot:toolbarActions>

    <div class="grid w-full space-y-5">
        <div>
            <h1 class="text-lg font-semibold text-foreground">Edit Pengaturan Perusahaan</h1>
            <p class="text-sm text-muted-foreground">Perbarui informasi perusahaan Anda</p>
        </div>

        @if ($errors->any())
            <div class="kt-alert kt-alert-destructive kt-alert-sm">
                <div class="kt-alert-content">
                    <div class="kt-alert-title">Terjadi Kesalahan</div>
                    <div class="kt-alert-description">
                        <ul class="list-disc ps-5 text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <form id="company-settings-edit-form" method="POST" action="{{ route('company-settings.update', $companySetting) }}" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')

            <div class="kt-card">
                <div class="kt-card-header">
                    <h2 class="kt-card-title">Informasi Dasar</h2>
                </div>
                <div class="kt-card-content">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="company_name" class="kt-label text-sm">
                                Nama Perusahaan <span class="text-destructive">*</span>
                            </label>
                            <input
                                type="text"
                                id="company_name"
                                name="company_name"
                                value="{{ old('company_name', $companySetting->company_name) }}"
                                class="kt-input kt-input-sm"
                                placeholder="Masukkan nama perusahaan"
                                @error('company_name') aria-invalid="true" @enderror
                            >
                            @error('company_name')
                                <p class="text-xs text-destructive mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="website" class="kt-label text-sm">Website</label>
                            <input
                                type="url"
                                id="website"
                                name="website"
                                value="{{ old('website', $companySetting->website) }}"
                                class="kt-input kt-input-sm"
                                placeholder="https://example.com"
                                @error('website') aria-invalid="true" @enderror
                            >
                            @error('website')
                                <p class="text-xs text-destructive mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="kt-label text-sm">Email</label>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                value="{{ old('email', $companySetting->email) }}"
                                class="kt-input kt-input-sm"
                                placeholder="email@perusahaan.com"
                                @error('email') aria-invalid="true" @enderror
                            >
                            @error('email')
                                <p class="text-xs text-destructive mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="phone" class="kt-label text-sm">No. Telepon</label>
                            <input
                                type="tel"
                                id="phone"
                                name="phone"
                                value="{{ old('phone', $companySetting->phone) }}"
                                class="kt-input kt-input-sm"
                                placeholder="+62 XXX XXX XXXX"
                                @error('phone') aria-invalid="true" @enderror
                            >
                            @error('phone')
                                <p class="text-xs text-destructive mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-4">
                        <label for="address" class="kt-label text-sm">Alamat</label>
                        <textarea
                            id="address"
                            name="address"
                            rows="3"
                            class="kt-textarea kt-textarea-sm"
                            placeholder="Masukkan alamat perusahaan"
                            @error('address') aria-invalid="true" @enderror
                        >{{ old('address', $companySetting->address) }}</textarea>
                        @error('address')
                            <p class="text-xs text-destructive mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mt-4">
                        <label for="description" class="kt-label text-sm">Deskripsi</label>
                        <textarea
                            id="description"
                            name="description"
                            rows="4"
                            class="kt-textarea kt-textarea-sm"
                            placeholder="Deskripsi singkat tentang perusahaan"
                            @error('description') aria-invalid="true" @enderror
                        >{{ old('description', $companySetting->description) }}</textarea>
                        @error('description')
                            <p class="text-xs text-destructive mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="kt-card">
                <div class="kt-card-header">
                    <h2 class="kt-card-title">File & Media</h2>
                </div>
                <div class="kt-card-content">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="logo" class="kt-label text-sm">Logo Perusahaan</label>
                            @if ($companySetting->logo)
                                <div class="mb-3 rounded-md border border-border bg-muted p-3">
                                    <img src="{{ Storage::url($companySetting->logo) }}" alt="Logo" class="h-16 object-contain">
                                    <p class="text-xs text-muted-foreground mt-2">{{ basename($companySetting->logo) }}</p>
                                </div>
                            @endif
                            <input
                                type="file"
                                id="logo"
                                name="logo"
                                accept="image/*"
                                class="kt-input kt-input-sm"
                                @error('logo') aria-invalid="true" @enderror
                            >
                            <p class="text-xs text-muted-foreground mt-1">Max 2MB (JPEG, PNG, GIF, SVG)</p>
                            @error('logo')
                                <p class="text-xs text-destructive mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="favicon" class="kt-label text-sm">Favicon</label>
                            @if ($companySetting->favicon)
                                <div class="mb-3 rounded-md border border-border bg-muted p-3">
                                    <img src="{{ Storage::url($companySetting->favicon) }}" alt="Favicon" class="h-12 w-12 object-contain">
                                    <p class="text-xs text-muted-foreground mt-2">{{ basename($companySetting->favicon) }}</p>
                                </div>
                            @endif
                            <input
                                type="file"
                                id="favicon"
                                name="favicon"
                                accept="image/*"
                                class="kt-input kt-input-sm"
                                @error('favicon') aria-invalid="true" @enderror
                            >
                            <p class="text-xs text-muted-foreground mt-1">Max 512KB (JPEG, PNG, ICO, SVG)</p>
                            @error('favicon')
                                <p class="text-xs text-destructive mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="login_background" class="kt-label text-sm">Background Login</label>
                            @if ($companySetting->login_background)
                                <div class="mb-3 rounded-md border border-border bg-muted p-3">
                                    <img src="{{ Storage::url($companySetting->login_background) }}" alt="Background Login" class="h-24 object-cover rounded">
                                    <p class="text-xs text-muted-foreground mt-2">{{ basename($companySetting->login_background) }}</p>
                                </div>
                            @endif
                            <input
                                type="file"
                                id="login_background"
                                name="login_background"
                                accept="image/*"
                                class="kt-input kt-input-sm"
                                @error('login_background') aria-invalid="true" @enderror
                            >
                            <p class="text-xs text-muted-foreground mt-1">Max 5MB (JPEG, PNG, GIF, SVG)</p>
                            @error('login_background')
                                <p class="text-xs text-destructive mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="kt-card">
                <div class="kt-card-header">
                    <h2 class="kt-card-title">Warna Tema</h2>
                </div>
                <div class="kt-card-content">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="primary_color" class="kt-label text-sm">Warna Primer</label>
                            <div class="flex items-center gap-3">
                                <input
                                    type="color"
                                    id="primary_color"
                                    name="primary_color"
                                    value="{{ old('primary_color', $companySetting->primary_color) }}"
                                    class="h-9 w-16 rounded-md border border-border cursor-pointer"
                                >
                                <input
                                    type="text"
                                    id="primary_color_text"
                                    name="primary_color_text"
                                    value="{{ old('primary_color', $companySetting->primary_color) }}"
                                    class="kt-input kt-input-sm flex-1"
                                    placeholder="#3B82F6"
                                    @error('primary_color') aria-invalid="true" @enderror
                                >
                            </div>
                            @error('primary_color')
                                <p class="text-xs text-destructive mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="secondary_color" class="kt-label text-sm">Warna Sekunder</label>
                            <div class="flex items-center gap-3">
                                <input
                                    type="color"
                                    id="secondary_color"
                                    name="secondary_color"
                                    value="{{ old('secondary_color', $companySetting->secondary_color) }}"
                                    class="h-9 w-16 rounded-md border border-border cursor-pointer"
                                >
                                <input
                                    type="text"
                                    id="secondary_color_text"
                                    name="secondary_color_text"
                                    value="{{ old('secondary_color', $companySetting->secondary_color) }}"
                                    class="kt-input kt-input-sm flex-1"
                                    placeholder="#10B981"
                                    @error('secondary_color') aria-invalid="true" @enderror
                                >
                            </div>
                            @error('secondary_color')
                                <p class="text-xs text-destructive mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="kt-card">
                <div class="kt-card-content">
                    <label class="flex items-center gap-2">
                        <input
                            type="checkbox"
                            id="is_active"
                            name="is_active"
                            value="1"
                            class="kt-checkbox kt-checkbox-sm"
                            {{ old('is_active', $companySetting->is_active) ? 'checked' : '' }}
                        >
                        <span class="text-sm text-foreground">Aktifkan Pengaturan Ini</span>
                    </label>
                </div>
            </div>
        </form>
    </div>

    <script>
        const primaryColorInput = document.getElementById('primary_color');
        const primaryColorText = document.getElementById('primary_color_text');
        const secondaryColorInput = document.getElementById('secondary_color');
        const secondaryColorText = document.getElementById('secondary_color_text');

        primaryColorInput.addEventListener('input', (e) => {
            primaryColorText.value = e.target.value;
        });

        primaryColorText.addEventListener('input', (e) => {
            primaryColorInput.value = e.target.value;
        });

        secondaryColorInput.addEventListener('input', (e) => {
            secondaryColorText.value = e.target.value;
        });

        secondaryColorText.addEventListener('input', (e) => {
            secondaryColorInput.value = e.target.value;
        });
    </script>
</x-layouts.admin>
