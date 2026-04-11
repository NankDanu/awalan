# AWALAN Project - Development Guide

## Getting Started

Selamat datang di project AWALAN. Ikuti langkah-langkah berikut untuk memulai development.

### Quick Start

```bash
# 1. Install dependencies
composer install
npm install

# 2. Setup environment
copy .env.example .env
php artisan key:generate

# 3. Setup database (edit .env first!)
php artisan migrate --seed

# 4. Build assets
npm run dev

# 5. Start server
php artisan serve
```

Default login:
- Admin: `admin@awalan.local` / `password`
- User: `user@awalan.local` / `password`

## Struktur Project

```
app/
├── Http/
│   ├── Controllers/     # Thin controllers (request/response only)
│   └── Requests/        # Form validation
├── Models/              # Eloquent models with table prefix
└── Services/            # Business logic here!

resources/
├── views/
│   ├── layouts/         # App & Guest layouts
│   ├── auth/            # Login, register, etc
│   └── components/      # Reusable Blade components
├── css/app.css          # Tailwind CSS
└── js/app.js

database/
├── migrations/          # Use table prefixes: mt_, tx_, cf_, sy_
└── seeders/
```

## Development Rules

### 1. Table Naming (WAJIB!)

Gunakan prefix untuk semua tabel:
- `mt_` = Master Data (contoh: `mt_users`, `mt_products`)
- `tx_` = Transaksi (contoh: `tx_orders`)
- `cf_` = Konfigurasi (contoh: `cf_settings`)
- `sy_` = Sistem (contoh: `sy_sessions`)

### 2. Controller Pattern

Controller HARUS TIPIS - hanya handle request/response:

```php
class ProductController extends Controller
{
    public function __construct(
        private ProductService $productService
    ) {}
    
    public function store(StoreProductRequest $request)
    {
        $product = $this->productService->create($request->validated());
        
        return redirect()
            ->route('products.index')
            ->with('success', 'Produk berhasil ditambahkan');
    }
}
```

### 3. Service Layer

Logic bisnis HARUS di Service:

```php
// app/Services/ProductService.php
class ProductService
{
    public function create(array $data): Product
    {
        DB::beginTransaction();
        try {
            $product = Product::create($data);
            // Logic lainnya...
            DB::commit();
            return $product;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
```

### 4. Form Request

Validasi SELALU gunakan Form Request:

```php
// app/Http/Requests/StoreProductRequest.php
class StoreProductRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ];
    }
    
    public function messages(): array
    {
        return [
            'name.required' => 'Nama produk wajib diisi',
            'price.required' => 'Harga wajib diisi',
        ];
    }
}
```

### 5. Model dengan Table Prefix

```php
class Product extends Model
{
    protected $table = 'mt_products';  // WAJIB!
    
    protected $fillable = [
        'name', 'description', 'price',
    ];
    
    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }
}
```

## Perintah Umum

### Generate Files

```bash
# Controller
php artisan make:controller ProductController

# Model dengan migration
php artisan make:model Product -m

# Form Request
php artisan make:request StoreProductRequest

# Service (manual) - buat di app/Services/
```

### Database

```bash
# Jalankan migration
php artisan migrate

# Reset & seed ulang
php artisan migrate:fresh --seed

# Rollback
php artisan migrate:rollback
```

### Frontend

```bash
# Development (hot reload)
npm run dev

# Production build
npm run build
```

### Testing

```bash
# Run all tests
php artisan test

# Run specific test
php artisan test --filter=ProductTest
```

### Cache

```bash
# Clear semua cache
php artisan optimize:clear

# Cache untuk production
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Tailwind CSS

### Gunakan Utility Classes

```blade
<button class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700">
    Simpan
</button>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <!-- Cards -->
</div>
```

### Blade Components

```blade
<!-- Gunakan component yang sudah ada -->
<x-layouts.app>
    <x-slot name="title">Halaman Produk</x-slot>
    
    <div class="bg-white p-6 rounded-lg shadow">
        <!-- Content -->
    </div>
</x-layouts.app>
```

## Authentication & Authorization

### Check Permission

```php
// Di controller
if (! auth()->user()->can('edit-products')) {
    abort(403);
}

// Di blade
@can('edit-products')
    <button>Edit</button>
@endcan
```

### Middleware

```php
// routes/web.php
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('products', ProductController::class);
});
```

## Best Practices

1. ✅ **Gunakan type hints**
   ```php
   public function create(array $data): Product
   ```

2. ✅ **Gunakan transactions untuk write operations**
   ```php
   DB::beginTransaction();
   try {
       // ...
       DB::commit();
   } catch (\Exception $e) {
       DB::rollBack();
   }
   ```

3. ✅ **Index foreign keys**
   ```php
   $table->foreign('user_id')->references('id')->on('mt_users');
   $table->index('user_id');
   ```

4. ✅ **Gunakan config(), jangan hardcode**
   ```php
   // Good ✅
   config('app.name')
   
   // Bad ❌
    'AWALAN'
   ```

5. ✅ **Error handling yang baik**
   ```php
   try {
       $result = $service->process();
   } catch (ModelNotFoundException $e) {
       return back()->with('error', 'Data tidak ditemukan');
   }
   ```

## Common Issues

### Migration Error
```bash
php artisan migrate:fresh
```

### CSS Tidak Muncul
```bash
npm run build
# atau
npm run dev
```

### Permission Error
Jalankan terminal sebagai Administrator

### Database Connection Error
Check `.env` file, pastikan DB_* sudah benar

## Resources

- 📚 [Laravel Docs](https://laravel.com/docs/11.x)
- 🎨 [Tailwind CSS](https://tailwindcss.com/docs)
- 🔐 [Spatie Permission](https://spatie.be/docs/laravel-permission)
- 📖 [Copilot Instructions](.github/copilot-instructions.md)

## Tips untuk GitHub Copilot

Saat meminta Copilot generate code, selalu sebutkan:
- "gunakan table prefix mt_ untuk master data"
- "buat controller yang thin dengan service layer"
- "gunakan Form Request untuk validasi"
- "ikuti PSR-12 code style"

---

Have fun coding! 🚀
