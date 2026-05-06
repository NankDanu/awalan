<?php

use Illuminate\Support\Facades\Route;
use Org\Base\Http\Controllers\Admin\Auth\AuthController;
use Org\Base\Http\Controllers\Admin\CompanySettingController;
use Org\Base\Http\Controllers\Admin\DashboardController;
use Org\Base\Http\Controllers\Admin\PermissionController;
use Org\Base\Http\Controllers\Admin\ProfileController;
use Org\Base\Http\Controllers\Admin\RoleController;
use Org\Base\Http\Controllers\Admin\UserController;

// Route::get('/', [HomeController::class, 'index']);

// Authentication Routes (Admin)
Route::redirect('/', '/login');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
});

Route::prefix('admin')->middleware(config('base.auth.middleware', ['auth']))->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/edit-password', [ProfileController::class, 'editPassword'])->name('profile.editPassword');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.updatePassword');
    
    // User Management Routes
    Route::get('users/datatable', [UserController::class, 'datatable'])
        ->name('users.datatable')
        ->middleware('permission:view-users');
    Route::resource('users', UserController::class)->except(['show']);

    // Role & Permission Management Routes
    Route::get('roles/datatable', [RoleController::class, 'datatable'])
        ->name('roles.datatable')
        ->middleware('permission:view-roles');
    Route::resource('roles', RoleController::class)->except(['show']);

    Route::get('permissions/datatable', [PermissionController::class, 'datatable'])
        ->name('permissions.datatable')
        ->middleware('permission:view-permissions');
    Route::resource('permissions', PermissionController::class)->except(['show']);

    // Company Settings Routes (Single Page)
    Route::get('/company-settings', [CompanySettingController::class, 'index'])->name('company-settings.index');
    Route::post('/company-settings', [CompanySettingController::class, 'store'])->name('company-settings.store');
    Route::put('/company-settings', [CompanySettingController::class, 'update'])->name('company-settings.update');
});




