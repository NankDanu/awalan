<?php

use App\Http\Controllers\Admin\Auth\AuthController;
use App\Http\Controllers\Admin\CompanySettingController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Catat\ArchiveController as CatatArchiveController;
use App\Http\Controllers\Catat\ClientController as CatatClientController;
use App\Http\Controllers\Catat\LeadController as CatatLeadController;
use App\Http\Controllers\Catat\NodeController as CatatNodeController;
use App\Http\Controllers\Catat\WorkspaceController as CatatWorkspaceController;
use Illuminate\Support\Facades\Route;

// Route::get('/', [HomeController::class, 'index']);

// Authentication Routes (Admin)
Route::redirect('/', '/login');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
});

Route::prefix('admin')->middleware('auth')->group(function () {
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
    Route::resource('users', UserController::class)->except(['show'])
        ->middleware('permission:view-users');

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

Route::prefix('catat')->name('catat.')->middleware(['auth'])->group(function () {
    Route::resource('clients', CatatClientController::class)->whereUuid('client');

    Route::get('workspaces/{workspace}/empty', [CatatWorkspaceController::class, 'empty'])
        ->name('workspaces.empty')
        ->whereUuid('workspace');
    Route::resource('workspaces', CatatWorkspaceController::class)
        ->whereUuid('workspace');

    Route::prefix('archive')->name('archive.')->group(function () {
        Route::get('/', [CatatArchiveController::class, 'index'])->name('index');
        Route::get('{workspace}', [CatatArchiveController::class, 'show'])
            ->name('show')
            ->whereUuid('workspace');
        Route::post('{workspace}/restore', [CatatArchiveController::class, 'restore'])
            ->name('restore')
            ->whereUuid('workspace');
    });

    Route::prefix('leads')->name('leads.')->group(function () {
        Route::get('/', [CatatLeadController::class, 'index'])->name('index');
    });

    Route::prefix('workspaces/{workspace}')->name('workspaces.')->whereUuid('workspace')->group(function () {
        Route::get('links', [CatatWorkspaceController::class, 'links'])
            ->name('links');
        Route::get('nodes/{node}', [CatatNodeController::class, 'show'])
            ->name('nodes.show')
            ->whereUuid('node');
        Route::post('nodes', [CatatNodeController::class, 'store'])
            ->name('nodes.store');
        Route::put('nodes/{node}/link', [CatatNodeController::class, 'updateLink'])
            ->name('nodes.update_link')
            ->whereUuid('node');
        Route::put('nodes/{node}', [CatatNodeController::class, 'update'])
            ->name('nodes.update')
            ->whereUuid('node');
        Route::delete('nodes/{node}', [CatatNodeController::class, 'destroy'])
            ->name('nodes.destroy')
            ->whereUuid('node');
        Route::post('nodes/{node}/move', [CatatNodeController::class, 'move'])
            ->name('nodes.move')
            ->whereUuid('node');
    });
});

