# AWALAN Project Setup Guide

## Prerequisites Installation

### 1. Install PHP 8.2+
Download and install PHP from [php.net](https://www.php.net/downloads.php)

Verify installation:
```bash
php -v
```

### 2. Install Composer
Download from [getcomposer.org](https://getcomposer.org/download/)

Verify installation:
```bash
composer -v
```

### 3. Install Node.js & NPM
Download from [nodejs.org](https://nodejs.org/)

Verify installation:
```bash
node -v
npm -v
```

### 4. Install MySQL 8.0+
Download from [mysql.com](https://dev.mysql.com/downloads/)

## Project Setup

### Step 1: Install Dependencies

```bash
# Navigate to project directory
cd c:\MDEV\awalan

# Install PHP dependencies
composer install

# Install Node dependencies
npm install
```

### Step 2: Environment Configuration

```bash
# Copy environment file
copy .env.example .env

# Generate application key
php artisan key:generate
```

### Step 3: Configure .env File

Edit `.env` file with your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=awalan_db
DB_USERNAME=root
DB_PASSWORD=your_password_here
```

### Step 4: Create Database

Open MySQL and create database:

```sql
CREATE DATABASE awalan_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### Step 5: Run Migrations

```bash
# Run all migrations
php artisan migrate

# (Optional) Seed database with sample data
php artisan db:seed
```

This will create:
- Admin user: `admin@awalan.local` / `password`
- Regular user: `user@awalan.local` / `password`

### Step 6: Build Frontend Assets

```bash
# For development (with hot reload)
npm run dev

# OR for production
npm run build
```

### Step 7: Create Storage Link

```bash
php artisan storage:link
```

### Step 8: Set Permissions (if needed)

For Windows, usually not needed. For Linux/Mac:

```bash
chmod -R 775 storage bootstrap/cache
```

### Step 9: Start Development Server

```bash
php artisan serve
```

Visit: http://localhost:8000

## Verification

### Test the Installation

1. **Home Page**: http://localhost:8000
2. **Login Page**: http://localhost:8000/login
3. **Dashboard**: http://localhost:8000/dashboard (after login)

### Default Login Credentials

**Administrator:**
- Email: `admin@awalan.local`
- Password: `password`

**Regular User:**
- Email: `user@awalan.local`
- Password: `password`

## Development Workflow

### Running in Development Mode

Terminal 1 - Backend:
```bash
php artisan serve
```

Terminal 2 - Frontend (with hot reload):
```bash
npm run dev
```

### Code Quality Tools

```bash
# Run Laravel Pint (code formatter)
./vendor/bin/pint

# Run tests
php artisan test

# Run specific test
php artisan test --filter=ExampleTest
```

## Common Commands

### Database

```bash
# Fresh migration (drop all tables and re-migrate)
php artisan migrate:fresh

# Fresh migration with seeding
php artisan migrate:fresh --seed

# Rollback last migration
php artisan migrate:rollback

# Show migration status
php artisan migrate:status
```

### Cache Management

```bash
# Clear all caches
php artisan optimize:clear

# Clear specific caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Cache for production
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Generate Files

```bash
# Generate controller
php artisan make:controller ProductController

# Generate model
php artisan make:model Product

# Generate migration
php artisan make:migration create_mt_products_table

# Generate request
php artisan make:request StoreProductRequest

# Generate middleware
php artisan make:middleware CheckAge
```

### Queue Management

```bash
# Run queue worker
php artisan queue:work

# Process failed jobs
php artisan queue:retry all
```

## Troubleshooting

### Issue: "Permission denied" errors

**Windows:**
Run VS Code or terminal as Administrator

**Linux/Mac:**
```bash
sudo chmod -R 775 storage bootstrap/cache
sudo chown -R $USER:www-data storage bootstrap/cache
```

### Issue: "Key not found" error

```bash
php artisan key:generate
```

### Issue: Migration errors

```bash
# Drop all tables and re-migrate
php artisan migrate:fresh

# If database doesn't exist, create it first in MySQL
```

### Issue: Node/NPM errors

```bash
# Delete node_modules and reinstall
rm -rf node_modules
npm install

# OR
npm ci
```

### Issue: Composer errors

```bash
# Update composer
composer self-update

# Clear composer cache
composer clear-cache

# Reinstall dependencies
composer install
```

### Issue: CSS not loading

```bash
# Rebuild assets
npm run build

# OR run dev server
npm run dev
```

## Production Deployment

### Step 1: Environment Setup

```bash
# Edit .env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com
```

### Step 2: Optimize Application

```bash
# Install dependencies (production only)
composer install --optimize-autoloader --no-dev

# Cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Build assets
npm run build
```

### Step 3: Set Proper Permissions

```bash
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### Step 4: Configure Web Server

Use the provided `nginx.conf.example` or configure Apache accordingly.

## Additional Resources

- [Laravel Documentation](https://laravel.com/docs/11.x)
- [Tailwind CSS Documentation](https://tailwindcss.com/docs)
- [Spatie Permission Documentation](https://spatie.be/docs/laravel-permission)
- [Laravel Sanctum Documentation](https://laravel.com/docs/11.x/sanctum)

## Support

For issues or questions, refer to:
- Project documentation in `.github/copilot-instructions.md`
- Laravel community forums
- Stack Overflow

---

**Happy Coding! 🚀**
