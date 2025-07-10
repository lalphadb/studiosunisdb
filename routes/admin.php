
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\EcoleController;
use App\Http\Controllers\Admin\AdminEcoleController;
use App\Http\Controllers\Admin\CoursController;
use App\Http\Controllers\Admin\CeintureController;
use App\Http\Controllers\Admin\PresenceController;
use App\Http\Controllers\Admin\PaiementController;
use App\Http\Controllers\Admin\SeminaireController;
use App\Http\Controllers\Admin\SessionCoursController;
use App\Http\Controllers\Admin\CoursHoraireController;
use App\Http\Controllers\Admin\InscriptionSeminaireController;
use App\Http\Controllers\Admin\FamilleController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\ExportController;
use App\Http\Controllers\Admin\LogController;
use App\Http\Controllers\Admin\TelescopeController;

/*
|--------------------------------------------------------------------------
| Routes Admin StudiosDB Enterprise v4.1.10.2
|--------------------------------------------------------------------------
| ✅ CORRECTION : Suppression du double groupage admin
| Les middlewares, prefix et name sont déjà définis dans web.php
*/

/*
|--------------------------------------------------------------------------
| DASHBOARD & STATS
|--------------------------------------------------------------------------
*/
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
Route::get('/stats', [DashboardController::class, 'stats'])->name('stats');
Route::get('/health', function() {
    return response()->json(['status' => 'healthy', 'timestamp' => now()]);
})->name('health');

/*
|--------------------------------------------------------------------------
| GESTION UTILISATEURS
|--------------------------------------------------------------------------
*/
Route::prefix('users')->name('users.')->group(function () {
    Route::get('/', [AdminUserController::class, 'index'])->name('index');
    Route::get('/create', [AdminUserController::class, 'create'])->name('create');
    Route::post('/', [AdminUserController::class, 'store'])->name('store');
    Route::get('/{user}', [AdminUserController::class, 'show'])->name('show');
    Route::get('/{user}/edit', [AdminUserController::class, 'edit'])->name('edit');
    Route::put('/{user}', [AdminUserController::class, 'update'])->name('update');
    Route::delete('/{user}', [AdminUserController::class, 'destroy'])->name('destroy');
    
    // Actions spéciales
    Route::post('/{user}/toggle-status', [AdminUserController::class, 'toggleStatus'])->name('toggle-status');
    Route::post('/{user}/reset-password', [AdminUserController::class, 'resetPassword'])->name('reset-password');
    Route::get('/export/all', [AdminUserController::class, 'export'])->name('export');
});

/*
|--------------------------------------------------------------------------
| GESTION ÉCOLES
|--------------------------------------------------------------------------
*/
Route::prefix('ecoles')->name('ecoles.')->group(function () {
    Route::get('/', [EcoleController::class, 'index'])->name('index');
    Route::get('/create', [EcoleController::class, 'create'])->name('create');
    Route::post('/', [EcoleController::class, 'store'])->name('store');
    Route::get('/{ecole}', [EcoleController::class, 'show'])->name('show');
    Route::get('/{ecole}/edit', [EcoleController::class, 'edit'])->name('edit');
    Route::put('/{ecole}', [EcoleController::class, 'update'])->name('update');
    Route::delete('/{ecole}', [EcoleController::class, 'destroy'])->name('destroy');
});

/*
|--------------------------------------------------------------------------
| GESTION COURS & FORMATION
|--------------------------------------------------------------------------
*/
Route::prefix('cours')->name('cours.')->group(function () {
    Route::get('/', [CoursController::class, 'index'])->name('index');
    Route::get('/create', [CoursController::class, 'create'])->name('create');
    Route::post('/', [CoursController::class, 'store'])->name('store');
    Route::get('/{cours}', [CoursController::class, 'show'])->name('show');
    Route::get('/{cours}/edit', [CoursController::class, 'edit'])->name('edit');
    Route::put('/{cours}', [CoursController::class, 'update'])->name('update');
    Route::delete('/{cours}', [CoursController::class, 'destroy'])->name('destroy');
});

// Sessions de cours
Route::prefix('sessions')->name('sessions.')->group(function () {
    Route::get('/', [SessionCoursController::class, 'index'])->name('index');
    Route::get('/create', [SessionCoursController::class, 'create'])->name('create');
    Route::post('/', [SessionCoursController::class, 'store'])->name('store');
    Route::get('/{session}', [SessionCoursController::class, 'show'])->name('show');
    Route::get('/{session}/edit', [SessionCoursController::class, 'edit'])->name('edit');
    Route::put('/{session}', [SessionCoursController::class, 'update'])->name('update');
    Route::delete('/{session}', [SessionCoursController::class, 'destroy'])->name('destroy');
});

// Horaires de cours
Route::prefix('cours-horaires')->name('cours-horaires.')->group(function () {
    Route::get('/', [CoursHoraireController::class, 'index'])->name('index');
    Route::get('/create', [CoursHoraireController::class, 'create'])->name('create');
    Route::post('/', [CoursHoraireController::class, 'store'])->name('store');
    Route::get('/{coursHoraire}', [CoursHoraireController::class, 'show'])->name('show');
    Route::get('/{coursHoraire}/edit', [CoursHoraireController::class, 'edit'])->name('edit');
    Route::put('/{coursHoraire}', [CoursHoraireController::class, 'update'])->name('update');
    Route::delete('/{coursHoraire}', [CoursHoraireController::class, 'destroy'])->name('destroy');
    Route::post('/{coursHoraire}/dupliquer', [CoursHoraireController::class, 'dupliquer'])->name('dupliquer');
});

/*
|--------------------------------------------------------------------------
| GESTION CEINTURES
|--------------------------------------------------------------------------
*/
Route::prefix('ceintures')->name('ceintures.')->group(function () {
    Route::get('/', [CeintureController::class, 'index'])->name('index');
    Route::get('/create', [CeintureController::class, 'create'])->name('create');
    Route::post('/', [CeintureController::class, 'store'])->name('store');
    Route::get('/create-masse', [CeintureController::class, 'createMasse'])->name('create-masse');
    Route::post('/store-masse', [CeintureController::class, 'storeMasse'])->name('store-masse');
});

/*
|--------------------------------------------------------------------------
| GESTION PRÉSENCES
|--------------------------------------------------------------------------
*/
Route::prefix('presences')->name('presences.')->group(function () {
    Route::get('/', [PresenceController::class, 'index'])->name('index');
    Route::get('/create', [PresenceController::class, 'create'])->name('create');
    Route::post('/', [PresenceController::class, 'store'])->name('store');
    Route::delete('/{presence}', [PresenceController::class, 'destroy'])->name('destroy');
});

/*
|--------------------------------------------------------------------------
| GESTION PAIEMENTS
|--------------------------------------------------------------------------
*/
Route::prefix('paiements')->name('paiements.')->group(function () {
    Route::get('/', [PaiementController::class, 'index'])->name('index');
    Route::get('/create', [PaiementController::class, 'create'])->name('create');
    Route::post('/', [PaiementController::class, 'store'])->name('store');
    Route::get('/{paiement}', [PaiementController::class, 'show'])->name('show');
    Route::delete('/{paiement}', [PaiementController::class, 'destroy'])->name('destroy');
});

/*
|--------------------------------------------------------------------------
| GESTION SÉMINAIRES
|--------------------------------------------------------------------------
*/
Route::prefix('seminaires')->name('seminaires.')->group(function () {
    Route::get('/', [SeminaireController::class, 'index'])->name('index');
    Route::get('/create', [SeminaireController::class, 'create'])->name('create');
    Route::post('/', [SeminaireController::class, 'store'])->name('store');
    Route::get('/{seminaire}', [SeminaireController::class, 'show'])->name('show');
    Route::get('/{seminaire}/edit', [SeminaireController::class, 'edit'])->name('edit');
    Route::put('/{seminaire}', [SeminaireController::class, 'update'])->name('update');
    Route::delete('/{seminaire}', [SeminaireController::class, 'destroy'])->name('destroy');
    Route::get('/{seminaire}/inscriptions', [SeminaireController::class, 'inscriptions'])->name('inscriptions');
});

// Inscriptions séminaires
Route::prefix('inscriptions-seminaires')->name('inscriptions-seminaires.')->group(function () {
    Route::get('/', [InscriptionSeminaireController::class, 'index'])->name('index');
    Route::delete('/{inscription}', [InscriptionSeminaireController::class, 'destroy'])->name('destroy');
});

/*
|--------------------------------------------------------------------------
| ADMINISTRATION SYSTÈME (SuperAdmin uniquement)
|--------------------------------------------------------------------------
*/
// Gestion des rôles
Route::prefix('roles')->name('roles.')->middleware('can:manage-roles')->group(function () {
    Route::get('/', [RoleController::class, 'index'])->name('index');
    Route::get('/{role}/edit', [RoleController::class, 'edit'])->name('edit');
    Route::put('/{role}', [RoleController::class, 'update'])->name('update');
});

// Exports
Route::prefix('exports')->name('exports.')->group(function () {
    Route::get('/', [ExportController::class, 'index'])->name('index');
    Route::get('/logs', [ExportController::class, 'exportLogs'])->name('logs');
});

// Logs système (SuperAdmin uniquement)
Route::prefix('logs')->name('logs.')->middleware('can:viewAny,App\Policies\LogPolicy')->group(function () {
    Route::get('/', [LogController::class, 'index'])->name('index');
    Route::delete('/clear', [LogController::class, 'clear'])->name('clear');
});

// Telescope (si installé)
Route::get('/telescope', function() {
    return redirect('/telescope');
})->name('telescope')->middleware('can:view-telescope');

/*
|--------------------------------------------------------------------------
| FAMILLES (Placeholder pour développement futur)
|--------------------------------------------------------------------------
*/
Route::prefix('familles')->name('familles.')->group(function () {
    Route::get('/', [FamilleController::class, 'index'])->name('index');
});

/*
|--------------------------------------------------------------------------
| API ROUTES POUR AJAX
|--------------------------------------------------------------------------
*/
Route::prefix('api')->name('api.')->group(function () {
    Route::get('/users/search', [AdminUserController::class, 'search'])->name('users.search');
    Route::get('/ecoles/list', [EcoleController::class, 'list'])->name('ecoles.list');
    Route::get('/cours/list', [CoursController::class, 'list'])->name('cours.list');
    Route::get('/dashboard/quick-stats', [DashboardController::class, 'quickStats'])->name('dashboard.quick-stats');
});

/*
|--------------------------------------------------------------------------
| ROUTES DE TEST (Développement uniquement)
|--------------------------------------------------------------------------
*/
if (config('app.debug')) {
    Route::prefix('test')->name('test.')->group(function () {
        Route::get('/permissions', function() {
            return response()->json([
                'user' => auth()->user()->load('roles', 'permissions'),
                'can_view_users' => auth()->user()->can('view_users'),
                'roles' => auth()->user()->roles->pluck('name'),
                'permissions' => auth()->user()->getAllPermissions()->pluck('name')
            ]);
        })->name('permissions');
        
        Route::get('/routes', function() {
            return response()->json([
                'admin_routes' => collect(Route::getRoutes())->filter(function($route) {
                    return str_starts_with($route->getName() ?? '', 'admin.');
                })->map(function($route) {
                    return [
                        'name' => $route->getName(),
                        'uri' => $route->uri(),
                        'methods' => $route->methods()
                    ];
                })->values()
            ]);
        })->name('routes');
    });
}