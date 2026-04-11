# 🚀 Company Settings - Quick Reference Guide

**Last Updated:** February 6, 2026

---

## 📌 Quick Access

### Get Active Company Setting
```php
// Method 1: Using Helper
$setting = company_setting();  // Returns full CompanySetting model

// Method 2: Using Helper Class
use App\Helpers\CompanySettingHelper;
$setting = CompanySettingHelper::getActive();

// Method 3: Direct eloquent
$setting = \App\Models\CompanySetting::where('is_active', true)->first();
```

### Get Specific Field
```php
// Using global functions (RECOMMENDED - easiest)
$name = company_name();
$email = company_email();
$phone = company_phone();
$color = primary_color();

// Using helper class
$website = CompanySettingHelper::get('website');
$address = CompanySettingHelper::get('address');

// Using company_setting helper
$field = company_setting('field_name', 'default_value');
```

---

## 🖼️ File Access

### Get File URLs
```php
// Logo
$logoUrl = company_logo();          // Returns full URL
$logoPath = company_logo(false);    // Returns path only
<img src="{{ company_logo() }}" alt="Logo">

// Favicon
$faviconUrl = company_favicon();    // Returns full URL
<link rel="icon" href="{{ company_favicon() }}">

// Login Background
$bgUrl = company_login_background();
<div style="background-image: url('{{ $bgUrl }}')">...</div>

// Using Storage facade
<img src="{{ Storage::url(company_setting('logo')) }}">
```

---

## 🎨 Theme Colors

### Get Colors
```php
// Global functions
$primary = primary_color();      // e.g., "#3B82F6"
$secondary = secondary_color();  // e.g., "#10B981"

// In Blade
<style>
    :root {
        --primary-color: {{ primary_color() }};
        --secondary-color: {{ secondary_color() }};
    }
</style>
```

### Apply to Elements
```blade
<!-- Direct style -->
<button style="background-color: {{ primary_color() }}">
    Click Me
</button>

<!-- Using CSS variables -->
<style>
    .btn-primary {
        background-color: var(--primary-color);
    }
</style>

<!-- Tailwind dynamic (if using dynamic colors) -->
<div class="bg-[{{ primary_color() }}]">Content</div>
```

---

## 📝 Full Model Usage

```php
// Get full model
$company = company_setting();

// Access properties
echo $company->company_name;
echo $company->address;
echo $company->phone;
echo $company->email;
echo $company->website;
echo $company->description;
echo $company->logo;
echo $company->favicon;
echo $company->login_background;
echo $company->primary_color;
echo $company->secondary_color;
echo $company->is_active ? 'Active' : 'Inactive';

// Timestamps
echo $company->created_at->format('d-m-Y H:i');
echo $company->updated_at->format('d-m-Y H:i');
```

---

## 🍃 Blade Template Examples

### Header/Navbar
```blade
@if ($company = company_setting())
    <nav class="navbar bg-white shadow">
        <div class="navbar-brand">
            @if ($company->logo)
                <img src="{{ Storage::url($company->logo) }}" 
                     alt="{{ $company->company_name }}"
                     class="h-10">
            @else
                <span class="font-bold text-xl">
                    {{ $company->company_name }}
                </span>
            @endif
        </div>
    </nav>
@endif
```

### Footer
```blade
@php
    $company = company_setting();
@endphp

<footer class="bg-gray-900 text-white py-8">
    <div class="max-w-7xl mx-auto">
        <div class="grid grid-cols-3 gap-8">
            <div>
                <h3 class="font-bold text-lg mb-4">
                    {{ $company->company_name }}
                </h3>
                <p class="text-gray-400">
                    {{ $company->description }}
                </p>
            </div>
            <div>
                <h4 class="font-bold mb-4">Contact</h4>
                <p>Email: {{ $company->email }}</p>
                <p>Phone: {{ $company->phone }}</p>
            </div>
            <div>
                <h4 class="font-bold mb-4">Follow Us</h4>
                <a href="{{ $company->website }}" class="text-blue-400">
                    Visit Website
                </a>
            </div>
        </div>
    </div>
</footer>
```

### Login Page with Background
```blade
@php
    $company = company_setting();
    $bgUrl = $company?->login_background 
        ? Storage::url($company->login_background) 
        : 'default-background.jpg';
@endphp

<div class="login-container h-screen bg-cover bg-center"
     style="background-image: url('{{ $bgUrl }}')">
    
    <div class="flex items-center justify-center h-full">
        <div class="bg-white rounded-lg shadow-lg p-8 w-96">
            @if ($company?->logo)
                <img src="{{ Storage::url($company->logo) }}" 
                     alt="{{ $company->company_name }}"
                     class="h-16 mx-auto mb-6">
            @endif
            
            <h1 class="text-center text-2xl font-bold mb-6">
                {{ $company->company_name ?? 'AWALAN' }}
            </h1>
            
            <!-- Login Form -->
        </div>
    </div>
</div>
```

### Theme Color Application
```blade
@php
    $primary = primary_color();
    $secondary = secondary_color();
@endphp

<style>
    :root {
        --primary: {{ $primary }};
        --secondary: {{ $secondary }};
    }
    
    .btn-primary {
        background-color: var(--primary);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 0.375rem;
    }
    
    .btn-secondary {
        background-color: var(--secondary);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 0.375rem;
    }
    
    .badge-primary {
        background-color: var(--primary);
        color: white;
    }
</style>

<!-- Usage -->
<button class="btn-primary">Save</button>
<button class="btn-secondary">Cancel</button>
<span class="badge-primary">Active</span>
```

---

## 💻 In Controllers/Services

```php
namespace App\Http\Controllers;

use App\Helpers\CompanySettingHelper;

class MyController extends Controller
{
    public function example()
    {
        // Get company setting
        $company = CompanySettingHelper::getActive();
        
        // Check if active
        if ($company && $company->is_active) {
            $name = $company->company_name;
            $email = $company->email;
        }
        
        // Or use global functions
        $theme = [
            'primary' => primary_color(),
            'secondary' => secondary_color(),
        ];
        
        return view('my-view', compact('company', 'theme'));
    }
    
    public function sendEmail()
    {
        $company = company_setting();
        
        // Send from company email
        $fromEmail = $company->email;
        $fromName = $company->company_name;
        
        // ... send email
    }
}
```

---

## 📧 Email Templates

```blade
<!-- In mail template -->
@php
    $company = company_setting();
@endphp

<table width="100%" cellpadding="0" cellspacing="0">
    <tr>
        <td align="center">
            @if ($company->logo)
                <img src="{{ Storage::url($company->logo) }}" 
                     alt="{{ $company->company_name }}"
                     style="height: 60px; display: block;">
            @endif
            <h2 style="color: {{ primary_color() }};">
                {{ $company->company_name }}
            </h2>
        </td>
    </tr>
    <tr>
        <td>
            <!-- Email content -->
        </td>
    </tr>
    <tr>
        <td style="color: #666; font-size: 12px; border-top: 1px solid #ddd; padding-top: 20px;">
            <p>
                <strong>{{ $company->company_name }}</strong><br>
                {{ $company->address }}<br>
                Phone: {{ $company->phone }}<br>
                Email: <a href="mailto:{{ $company->email }}">{{ $company->email }}</a><br>
                Website: <a href="{{ $company->website }}">{{ $company->website }}</a>
            </p>
        </td>
    </tr>
</table>
```

---

## 🔧 API Response Example

```php
use App\Http\Controllers\Controller;

class ApiController extends Controller
{
    public function appConfig()
    {
        $company = company_setting();
        
        return response()->json([
            'app_name' => $company->company_name,
            'logo_url' => company_logo(),
            'favicon_url' => company_favicon(),
            'theme' => [
                'primary' => primary_color(),
                'secondary' => secondary_color(),
            ],
            'contact' => [
                'email' => company_email(),
                'phone' => company_phone(),
                'address' => $company->address,
            ],
        ]);
    }
}
```

---

## 🐛 Troubleshooting

### Cache Issues
```php
// Clear cache manually
use App\Helpers\CompanySettingHelper;
CompanySettingHelper::clearCache();

// Or via artisan
php artisan cache:clear
```

### File Not Found
```php
// Check if file exists
if ($company->logo && Storage::disk('public')->exists($company->logo)) {
    $url = Storage::url($company->logo);
}

// Recreate storage link
php artisan storage:link
```

### Helper Functions Not Available
```php
// Reload composer autoloader
composer dump-autoload

// Clear Laravel cache
php artisan config:clear
php artisan cache:clear
```

---

## ⚡ Performance Tips

1. **Use Global Functions** - They already have caching built-in
   ```php
   // Good ✅
   {{ company_name() }}
   
   // Less efficient ❌
   {{ company_setting()->company_name }}
   ```

2. **Cache File URLs** - Don't regenerate in loops
   ```php
   // Good ✅
   @php $logo = company_logo(); @endphp
   @foreach($items as $item)
       <img src="{{ $logo }}" alt="">
   @endforeach
   
   // Less efficient ❌
   @foreach($items as $item)
       <img src="{{ company_logo() }}" alt="">
   @endforeach
   ```

3. **Leverage Observer** - It handles cache invalidation automatically
   ```php
   // Cache is auto-cleared when data changes
   // No manual cache management needed
   ```

---

## 🎯 Common Use Cases

### 1. Dynamic Favicon
```blade
<link rel="icon" href="{{ company_favicon() }}">
```

### 2. Branded Email Footer
```blade
<!-- components/email-footer.blade.php -->
<footer>
    <p>&copy; {{ date('Y') }} {{ company_name() }}</p>
    <p>{{ company_email() }} | {{ company_phone() }}</p>
</footer>
```

### 3. Dynamic Page Title
```php
// In controller
$pageTitle = company_name() . ' - Dashboard';

// Or in view
<title>{{ company_name() }} - @yield('title')</title>
```

### 4. Status Badge Component
```blade
<!-- components/status-badge.blade.php -->
@props(['status'])

<span class="px-3 py-1 rounded text-white"
      style="background-color: {{ $status ? primary_color() : '#999' }}">
    {{ $status ? 'Active' : 'Inactive' }}
</span>
```

### 5. Company Info Component
```blade
<!-- components/company-card.blade.php -->
@php
    $company = company_setting();
@endphp

<div class="bg-white rounded-lg shadow p-6">
    @if ($company->logo)
        <img src="{{ Storage::url($company->logo) }}" 
             alt="{{ $company->company_name }}"
             class="h-20 mb-4">
    @endif
    
    <h3 class="text-xl font-bold">{{ $company->company_name }}</h3>
    <p class="text-gray-600">{{ $company->description }}</p>
    
    <div class="mt-4 text-sm text-gray-500">
        <p>📧 {{ $company->email }}</p>
        <p>📱 {{ $company->phone }}</p>
        <p>🏢 {{ $company->address }}</p>
    </div>
</div>
```

---

## 📚 Related Documentation

- Full Guide: `FULL_DOCUMENTATION.md`
- Quick Start: `QUICK_START.md`

---

**Happy Coding! 🎉**

For more details, refer to the full documentation files.
