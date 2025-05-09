<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::middleware('auth')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');

    // users
    Route::resource('/users', UserController::class);
    Route::post('/users/{user}/activate', [UserController::class, 'activate'])->name('users.activate');
    Route::post('/users/{user}/deactivate', [UserController::class, 'deactivate'])->name('users.deactivate');

    // Roles
    Route::resource('/roles', RoleController::class);
    Route::get('/roles/{role}/permissions/edit', [RoleController::class, 'editPermissions'])->name('roles.edit-permissions');
    Route::put('/roles/{role}/permissions', [RoleController::class, 'updatePermissions'])->name('roles.update-permissions');
    Route::get('/roles/{role}/activity-logs', [RoleController::class, 'activityLogs'])->name('roles.activity-logs');
    Route::delete('/roles/{role}/users/{user}', [RoleController::class, 'deleteUser'])->name('roles.delete-user');
    
});
