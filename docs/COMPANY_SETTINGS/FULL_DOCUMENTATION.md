# Company Settings CRUD Documentation

## Deskripsi

Module **Company Settings** adalah fitur untuk mengelola konfigurasi perusahaan yang digunakan di seluruh aplikasi AWALAN. Fitur ini memungkinkan administrator untuk mengatur informasi perusahaan, logo, favicon, background login, dan warna tema aplikasi.

## Fields yang Tersedia

| Field | Tipe | Deskripsi | Validasi |
|-------|------|-----------|----------|
| `company_name` | String | Nama perusahaan | Required, Unique, Max 255 |
| `address` | Text | Alamat perusahaan | Nullable, Max 500 |
| `phone` | String | Nomor telepon | Nullable, Max 20 |
| `email` | String | Email perusahaan | Nullable, Email |
| `website` | String | Website perusahaan | Nullable, URL |
| `description` | Text | Deskripsi perusahaan | Nullable |
| `logo` | File | Logo perusahaan | Nullable, Image, Max 2MB |
| `favicon` | File | Favicon website | Nullable, Image, Max 512KB |
| `login_background` | File | Background halaman login | Nullable, Image, Max 5MB |
| `primary_color` | String | Warna primer tema | Hex format (#RRGGBB) |
| `secondary_color` | String | Warna sekunder tema | Hex format (#RRGGBB) |
| `is_active` | Boolean | Status aktif | Default: false |

## Struktur Database

```sql
CREATE TABLE cf_company_settings (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    company_name VARCHAR(255) UNIQUE,
    address TEXT,
    phone VARCHAR(20),
    email VARCHAR(255),
    website VARCHAR(255),
    description TEXT,
    logo VARCHAR(255),
    favicon VARCHAR(255),
    login_background VARCHAR(255),
    primary_color VARCHAR(7) DEFAULT '#3B82F6',
    secondary_color VARCHAR(7) DEFAULT '#10B981',
    is_active BOOLEAN DEFAULT false,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    deleted_at TIMESTAMP
);
```

## Fitur CRUD

### 1. Index (List) - GET `/company-settings`
Menampilkan daftar semua pengaturan perusahaan dengan pagination.

**Response:**
- Table dengan columns: Nama, Email, Telepon, Status, Aksi
- Pagination controls
- Action buttons: Edit dan Delete

### 2. Create (Form) - GET `/company-settings/create`
Menampilkan form untuk membuat pengaturan perusahaan baru.

**Form Fields:**
- Informasi Dasar: Nama, Website, Email, Telepon, Alamat, Deskripsi
- File & Media: Logo, Favicon, Background Login
- Warna Tema: Warna Primer, Warna Sekunder
- Status: Checkbox untuk mengaktifkan

### 3. Store - POST `/company-settings`
Menyimpan pengaturan perusahaan baru ke database.

**Request Validation:**
```php
[
    'company_name' => 'required|string|max:255|unique:cf_company_settings,company_name',
    'address' => 'nullable|string|max:500',
    'phone' => 'nullable|string|max:20',
    'email' => 'nullable|email|max:255',
    'website' => 'nullable|url|max:255',
    'description' => 'nullable|string',
    'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    'favicon' => 'nullable|image|mimes:jpeg,png,jpg,gif,ico,svg|max:512',
    'login_background' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
    'primary_color' => 'nullable|string|regex:/^#[0-9A-F]{6}$/i',
    'secondary_color' => 'nullable|string|regex:/^#[0-9A-F]{6}$/i',
    'is_active' => 'boolean',
]
```

### 4. Edit (Form) - GET `/company-settings/{id}/edit`
Menampilkan form untuk mengedit pengaturan perusahaan yang ada.

**Fitur Tambahan:**
- Preview file yang sudah diupload
- Option untuk mengganti file

### 5. Update - PUT `/company-settings/{id}`
Memperbarui pengaturan perusahaan di database.

**Validasi:**
- Sama seperti Store, dengan exception: `company_name` allows current ID

### 6. Delete - DELETE `/company-settings/{id}`
Menghapus pengaturan perusahaan (soft delete).

## Cara Menggunakan

### A. Di Controllers dan Services

```php
use App\Helpers\CompanySettingHelper;

// Get active company setting
$setting = CompanySettingHelper::getActive();

// Get specific field
$companyName = CompanySettingHelper::getCompanyName();
$email = CompanySettingHelper::getCompanyEmail();
$phone = CompanySettingHelper::getCompanyPhone();

// Get all fields
$logo = CompanySettingHelper::getLogo();
$favicon = CompanySettingHelper::getFavicon();
$loginBg = CompanySettingHelper::getLoginBackground();
$primaryColor = CompanySettingHelper::getPrimaryColor();
$secondaryColor = CompanySettingHelper::getSecondaryColor();

// Get custom field
$website = CompanySettingHelper::get('website');

// Clear cache after update
CompanySettingHelper::clearCache();
```

### B. Di Blade Templates

```blade
<!-- Display company info -->
<h1>{{ CompanySettingHelper::getCompanyName() }}</h1>
<p>{{ CompanySettingHelper::get('description') }}</p>

<!-- Display logo -->
@if (CompanySettingHelper::getLogo())
    <img src="{{ Storage::url(CompanySettingHelper::getLogo()) }}" alt="Logo">
@endif

<!-- Use theme colors -->
<style>
    :root {
        --primary-color: {{ CompanySettingHelper::getPrimaryColor() }};
        --secondary-color: {{ CompanySettingHelper::getSecondaryColor() }};
    }
</style>

<!-- Apply background login -->
@if (CompanySettingHelper::getLoginBackground())
    <div style="background-image: url('{{ Storage::url(CompanySettingHelper::getLoginBackground()) }}')">
        ...
    </div>
@endif
```

### C. Dalam JavaScript

Buat global variable di layout head:
```blade
<script>
    window.appConfig = {
        companyName: '{{ CompanySettingHelper::getCompanyName() }}',
        primaryColor: '{{ CompanySettingHelper::getPrimaryColor() }}',
        secondaryColor: '{{ CompanySettingHelper::getSecondaryColor() }}',
        logoUrl: '{{ Storage::url(CompanySettingHelper::getLogo() ?? "") }}'
    };
</script>
```

## File Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   └── CompanySettingController.php
│   └── Requests/
│       ├── StoreCompanySettingRequest.php
│       └── UpdateCompanySettingRequest.php
├── Models/
│   └── CompanySetting.php
├── Services/
│   └── CompanySettingService.php
├── Helpers/
│   └── CompanySettingHelper.php
└── Observers/
    └── CompanySettingObserver.php

database/
├── migrations/
│   └── 2026_02_06_000006_create_cf_company_settings_table.php
├── factories/
│   └── CompanySettingFactory.php
└── seeders/
    └── CompanySettingSeeder.php

resources/views/settings/company/
├── index.blade.php
├── create.blade.php
└── edit.blade.php

routes/
└── web.php (updated with company-settings routes)
```

## Routes

```php
// Company Settings Routes
Route::resource('company-settings', CompanySettingController::class);

// Generated routes:
// GET    /company-settings              - company-settings.index
// GET    /company-settings/create       - company-settings.create
// POST   /company-settings              - company-settings.store
// GET    /company-settings/{id}/edit    - company-settings.edit
// PUT    /company-settings/{id}         - company-settings.update
// DELETE /company-settings/{id}         - company-settings.destroy
```

## How to Migrate and Seed

### 1. Run Migration
```bash
php artisan migrate
```

### 2. Seed Default Company Settings
```bash
php artisan db:seed --class=CompanySettingSeeder
```

### 3. Or use Factory in Tinker
```bash
php artisan tinker
CompanySetting::factory()->create();
```

## Caching

Module ini menggunakan Laravel Cache untuk optimasi performa:
- Cache duration: 3600 detik (1 jam)
- Cache key: `company_setting_active`
- Cache otomatis clear ketika ada perubahan data (via Observer)

## Permissions (Future Implementation)

Untuk menambahkan permission control:

```php
// Add permissions di DatabaseSeeder
$permissions = [
    'view-company-settings',
    'create-company-settings',
    'edit-company-settings',
    'delete-company-settings',
];

// Add middleware di controller
Route::resource('company-settings', CompanySettingController::class)
    ->middleware('permission:view-company-settings');
```

## Best Practices

1. **Selalu gunakan Helper** - Gunakan `CompanySettingHelper` untuk mengakses company settings di seluruh aplikasi
2. **Cache aware** - Helper sudah handle caching, tidak perlu manual cache
3. **Soft deletes** - Company settings menggunakan soft delete, dapat di-restore jika diperlukan
4. **File storage** - File disimpan di `storage/app/public/company/` dan diakses via `Storage::url()`
5. **Validation** - Semua input divalidasi di Form Request, bukan di controller

## Development Notes

- Helper class otomatis clear cache saat ada perubahan data
- Observer mendengarkan semua event: created, updated, deleted, restored
- Service layer handle semua business logic dan file management
- Controller tetap thin (hanya handle request/response)
- Support soft deletes untuk data recovery

## Contoh Use Case

### 1. Tampilkan logo di navbar
```blade
<!-- In navbar component -->
@if ($company = CompanySettingHelper::getActive())
    @if ($company->logo)
        <img src="{{ Storage::url($company->logo) }}" alt="{{ $company->company_name }}" class="h-8">
    @else
        <span class="text-lg font-bold">{{ $company->company_name }}</span>
    @endif
@endif
```

### 2. Styling dengan theme colors
```blade
<!-- In layout blade -->
<style>
    :root {
        --primary: {{ CompanySettingHelper::getPrimaryColor() }};
        --secondary: {{ CompanySettingHelper::getSecondaryColor() }};
    }

    .btn-primary {
        background-color: var(--primary);
    }

    .btn-secondary {
        background-color: var(--secondary);
    }
</style>
```

### 3. Custom login page dengan background
```blade
<!-- In login blade -->
<div class="login-container"
     @if ($bg = CompanySettingHelper::getLoginBackground())
         style="background-image: url('{{ Storage::url($bg) }}')"
     @endif
>
    <!-- Form -->
</div>
```

---

**Created:** February 6, 2026  
**Version:** 1.1 (aligned with AWALAN Laravel 12 stack)  
**Status:** Production Ready
