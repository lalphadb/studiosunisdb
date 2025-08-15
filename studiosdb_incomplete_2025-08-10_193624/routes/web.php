<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\MemberController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Dashboard', [
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware(['auth','verified','scope.school'])->group(function(){

    Route::get('/dashboard', function(){
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::middleware(['role:superadmin|admin_ecole'])->group(function () {
        Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');
        Route::post('/admin/users', [UserController::class, 'store'])->name('admin.users.store');
        Route::put('/admin/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
        Route::delete('/admin/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');
    });

    Route::get('/members', [MemberController::class, 'index'])->name('members.index');
    Route::post('/members', [MemberController::class, 'store'])->middleware('role:superadmin|admin_ecole')->name('members.store');
    Route::put('/members/{member}', [MemberController::class, 'update'])->middleware('role:superadmin|admin_ecole')->name('members.update');
    Route::delete('/members/{member}', [MemberController::class, 'destroy'])->middleware('role:superadmin|admin_ecole')->name('members.destroy');
});

require __DIR__.'/auth.php';
