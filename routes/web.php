<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\BeltController;
use App\Http\Controllers\FamilyController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/api/dashboard/metrics', [DashboardController::class, 'metricsRealtime'])->name('dashboard.metrics');
    
    // Members - Resource complet avec routes supplémentaires
    Route::resource('members', MemberController::class);
    Route::post('/members/{member}/change-belt', [MemberController::class, 'changeBelt'])->name('members.change-belt');
    Route::post('/members/bulk-update', [MemberController::class, 'bulkUpdate'])->name('members.bulk-update');
    Route::get('/members/export', [MemberController::class, 'export'])->name('members.export');
    
    // Courses
    Route::resource('courses', CourseController::class);
    Route::post('/courses/{course}/duplicate', [CourseController::class, 'duplicate'])->name('courses.duplicate');
    Route::get('/planning', [CourseController::class, 'planning'])->name('courses.planning');
    
    // Attendances
    Route::resource('attendances', AttendanceController::class);
    Route::get('/attendances/tablet', [AttendanceController::class, 'tablet'])->name('attendances.tablet');
    Route::post('/attendances/mark', [AttendanceController::class, 'mark'])->name('attendances.mark');
    Route::get('/attendances/reports', [AttendanceController::class, 'reports'])->name('attendances.reports');
    
    // Payments
    Route::resource('payments', PaymentController::class);
    Route::patch('/payments/{payment}/confirm', [PaymentController::class, 'confirm'])->name('payments.confirm');
    Route::get('/payments-dashboard', [PaymentController::class, 'dashboard'])->name('payments.dashboard');
    Route::post('/payments/generate-invoices', [PaymentController::class, 'generateInvoices'])->name('payments.generate-invoices');
    
    // Belts
    Route::resource('belts', BeltController::class);
    Route::get('/belts-exams', [BeltController::class, 'exams'])->name('belts.exams');
    Route::post('/belts/schedule-exam', [BeltController::class, 'scheduleExam'])->name('belts.schedule-exam');
    Route::patch('/belts/exams/{exam}/result', [BeltController::class, 'recordResult'])->name('belts.record-result');
    
    // Families
    Route::resource('families', FamilyController::class);
    
    // Statistics & Reports
    Route::get('/statistics', [StatisticsController::class, 'index'])->name('statistics.index');
    Route::get('/statistics/export', [StatisticsController::class, 'export'])->name('statistics.export');
    
    // Administration
    Route::middleware(['role:admin,super-admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('index');
        Route::get('/configuration', [AdminController::class, 'configuration'])->name('configuration');
        Route::post('/configuration', [AdminController::class, 'updateConfiguration'])->name('configuration.update');
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::get('/logs', [AdminController::class, 'logs'])->name('logs');
        Route::get('/backup', [AdminController::class, 'backup'])->name('backup');
        Route::post('/backup/run', [AdminController::class, 'runBackup'])->name('backup.run');
    });
    
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
