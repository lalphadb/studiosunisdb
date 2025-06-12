<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Pages légales
Route::get('/politique-confidentialite', [App\Http\Controllers\LegalController::class, 'privacy'])->name('privacy');
  Route::get('/conditions-utilisation', [App\Http\Controllers\LegalController::class, 'terms'])->name('terms');
  Route::get('/contact', [App\Http\Controllers\LegalController::class, 'contact'])->name('contact');
  Route::post('/contact', [App\Http\Controllers\LegalController::class, 'sendContact'])->name('contact.send');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
