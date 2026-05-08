<x-layouts.admin :title="'Pengaturan Perusahaan'" :pageTitle="'Pengaturan Perusahaan'" :showWidget="false">
    <x-slot:toolbarActions>
        <button type="submit" form="company-settings-index-form" class="btn-compact btn-primary">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            {{ $isNew ? 'Simpan Pengaturan' : 'Perbarui Pengaturan' }}
        </button>
    </x-slot:toolbarActions>

    <div class="space-y-4">
        <!-- Alert Messages -->
        @if (session('success'))
            <div class="p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                <h3 class="font-bold">Terjadi Kesalahan:</h3>
                <ul class="list-disc ml-5 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form -->
          <form id="company-settings-index-form" method="POST" 
              action="{{ $isNew ? route('company-settings.store') : route('company-settings.update') }}" 
              enctype="multipart/form-data" 
              class="bg-white shadow rounded-lg p-6">
            @csrf
            @if (!$isNew)
                @method('PUT')
            @endif

            <!-- Informasi Dasar -->
            <div class="mb-8">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Dasar</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nama Perusahaan -->
                    <div>
                        <label for="company_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Perusahaan <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="company_name"
                            name="company_name"
                            value="{{ old('company_name', $setting->company_name) }}"
                            required
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                            style="border-color: {{ $errors->has('company_name') ? '#ef4444' : '#d1d5db' }}"
                            placeholder="Masukkan nama perusahaan"
                        >
                        @error('company_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Website -->
                    <div>
                        <label for="website" class="block text-sm font-medium text-gray-700 mb-2">
                            Website
                        </label>
                        <input
                            type="url"
                            id="website"
                            name="website"
                            value="{{ old('website', $setting->website) }}"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                            style="border-color: {{ $errors->has('website') ? '#ef4444' : '#d1d5db' }}"
                            placeholder="https://example.com"
                        >
                        @error('website')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email
                        </label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email', $setting->email) }}"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                            style="border-color: {{ $errors->has('email') ? '#ef4444' : '#d1d5db' }}"
                            placeholder="email@perusahaan.com"
                        >
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Telepon -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                            No. Telepon
                        </label>
                        <input
                            type="tel"
                            id="phone"
                            name="phone"
                            value="{{ old('phone', $setting->phone) }}"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                            style="border-color: {{ $errors->has('phone') ? '#ef4444' : '#d1d5db' }}"
                            placeholder="+62 XXX XXX XXXX"
                        >
                        @error('phone')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Alamat -->
                <div class="mt-6">
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                        Alamat
                    </label>
                    <textarea
                        id="address"
                        name="address"
                        rows="3"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                        style="border-color: {{ $errors->has('address') ? '#ef4444' : '#d1d5db' }}"
                        placeholder="Masukkan alamat perusahaan"
                    >{{ old('address', $setting->address) }}</textarea>
                    @error('address')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Deskripsi -->
                <div class="mt-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Deskripsi
                    </label>
                    <textarea
                        id="description"
                        name="description"
                        rows="4"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                        style="border-color: {{ $errors->has('description') ? '#ef4444' : '#d1d5db' }}"
                        placeholder="Deskripsi singkat tentang perusahaan"
                    >{{ old('description', $setting->description) }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- File Upload -->
            <div class="mb-8">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">File & Media</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Logo -->
                    <div>
                        <label for="logo" class="block text-sm font-medium text-gray-700 mb-2">
                            Logo Perusahaan
                        </label>
                        @if ($setting->logo)
                            <div class="mb-3 p-3 bg-gray-50 border border-gray-200 rounded-lg">
                                <img src="{{ Storage::url($setting->logo) }}" alt="Logo" class="h-16 object-contain">
                                <p class="text-xs text-gray-600 mt-2">{{ basename($setting->logo) }}</p>
                            </div>
                        @endif
                        <input
                            type="file"
                            id="logo"
                            name="logo"
                            accept="image/*"
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100 @error('logo') border border-red-500 @enderror"
                        >
                        <p class="text-gray-500 text-xs mt-1">Max 2MB (JPEG, PNG, GIF, SVG)</p>
                        @error('logo')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Favicon -->
                    <div>
                        <label for="favicon" class="block text-sm font-medium text-gray-700 mb-2">
                            Favicon
                        </label>
                        @if ($setting->favicon)
                            <div class="mb-3 p-3 bg-gray-50 border border-gray-200 rounded-lg">
                                <img src="{{ Storage::url($setting->favicon) }}" alt="Favicon" class="h-12 w-12 object-contain">
                                <p class="text-xs text-gray-600 mt-2">{{ basename($setting->favicon) }}</p>
                            </div>
                        @endif
                        <input
                            type="file"
                            id="favicon"
                            name="favicon"
                            accept="image/*"
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100 @error('favicon') border border-red-500 @enderror"
                        >
                        <p class="text-gray-500 text-xs mt-1">Max 512KB (JPEG, PNG, ICO, SVG)</p>
                        @error('favicon')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Background Login -->
                    <div class="md:col-span-2">
                        <label for="login_background" class="block text-sm font-medium text-gray-700 mb-2">
                            Background Halaman Login
                        </label>
                        @if ($setting->login_background)
                            <div class="mb-3 p-3 bg-gray-50 border border-gray-200 rounded-lg">
                                <img src="{{ Storage::url($setting->login_background) }}" alt="Background Login" class="h-32 w-full object-cover rounded">
                                <p class="text-xs text-gray-600 mt-2">{{ basename($setting->login_background) }}</p>
                            </div>
                        @endif
                        <input
                            type="file"
                            id="login_background"
                            name="login_background"
                            accept="image/*"
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100 @error('login_background') border border-red-500 @enderror"
                        >
                        <p class="text-gray-500 text-xs mt-1">Max 5MB (JPEG, PNG, GIF, SVG)</p>
                        @error('login_background')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Warna Tema -->
            <div class="mb-8">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Warna Tema</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Warna Primer -->
                    <div>
                        <label for="primary_color" class="block text-sm font-medium text-gray-700 mb-2">
                            Warna Primer
                        </label>
                        <div class="flex items-center gap-4">
                            <input
                                type="color"
                                id="primary_color"
                                value="{{ old('primary_color', $setting->primary_color ?? '#3B82F6') }}"
                                class="h-12 w-20 border border-gray-300 rounded-lg cursor-pointer"
                            >
                            <input
                                type="text"
                                id="primary_color_text"
                                name="primary_color"
                                value="{{ old('primary_color', $setting->primary_color ?? '#3B82F6') }}"
                                class="flex-1 px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                                style="border-color: {{ $errors->has('primary_color') ? '#ef4444' : '#d1d5db' }}"
                                placeholder="#3B82F6"
                                pattern="^#[0-9A-Fa-f]{6}$"
                            >
                        </div>
                        @error('primary_color')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Warna Sekunder -->
                    <div>
                        <label for="secondary_color" class="block text-sm font-medium text-gray-700 mb-2">
                            Warna Sekunder
                        </label>
                        <div class="flex items-center gap-4">
                            <input
                                type="color"
                                id="secondary_color"
                                value="{{ old('secondary_color', $setting->secondary_color ?? '#10B981') }}"
                                class="h-12 w-20 border border-gray-300 rounded-lg cursor-pointer"
                            >
                            <input
                                type="text"
                                id="secondary_color_text"
                                name="secondary_color"
                                value="{{ old('secondary_color', $setting->secondary_color ?? '#10B981') }}"
                                class="flex-1 px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                                style="border-color: {{ $errors->has('secondary_color') ? '#ef4444' : '#d1d5db' }}"
                                placeholder="#10B981"
                                pattern="^#[0-9A-Fa-f]{6}$"
                            >
                        </div>
                        @error('secondary_color')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Status -->
            <div class="mb-8">
                <div class="flex items-center">
                    <input
                        type="checkbox"
                        id="is_active"
                        name="is_active"
                        value="1"
                        {{ old('is_active', $setting->is_active ?? true) ? 'checked' : '' }}
                        class="h-4 w-4 text-primary-600 border-gray-300 rounded focus:ring-2 focus:ring-primary-500"
                    >
                    <label for="is_active" class="ml-2 block text-sm text-gray-700">
                        Aktifkan Pengaturan Ini
                    </label>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        // Sync color input and picker
        const primaryColorInput = document.getElementById('primary_color');
        const primaryColorText = document.getElementById('primary_color_text');
        const secondaryColorInput = document.getElementById('secondary_color');
        const secondaryColorText = document.getElementById('secondary_color_text');

        primaryColorInput.addEventListener('input', (e) => {
            primaryColorText.value = e.target.value.toUpperCase();
        });

        primaryColorText.addEventListener('input', (e) => {
            const value = e.target.value;
            if (/^#[0-9A-Fa-f]{6}$/.test(value)) {
                primaryColorInput.value = value;
            }
        });

        secondaryColorInput.addEventListener('input', (e) => {
            secondaryColorText.value = e.target.value.toUpperCase();
        });

        secondaryColorText.addEventListener('input', (e) => {
            const value = e.target.value;
            if (/^#[0-9A-Fa-f]{6}$/.test(value)) {
                secondaryColorInput.value = value;
            }
        });
    </script>
    @endpush
</x-layouts.admin>
