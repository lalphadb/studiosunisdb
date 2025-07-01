<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\EcoleController;
use App\Http\Controllers\Admin\CoursController;
use App\Http\Controllers\Admin\SessionCoursController;
use App\Http\Controllers\Admin\CoursHoraireController;
use App\Http\Controllers\Admin\CeintureController;
use App\Http\Controllers\Admin\SeminaireController;
use App\Http\Controllers\Admin\PaiementController;
use App\Http\Controllers\Admin\PresenceController;
use App\Http\Controllers\Admin\InscriptionSeminaireController;
use App\Http\Controllers\Admin\LogController;
use App\Http\Controllers\Admin\ExportController;

Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard principal
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    
    // Gestion des utilisateurs (membres)
    Route::resource('users', UserController::class);
    Route::get('users/{user}/qrcode', [UserController::class, 'qrcode'])->name('users.qrcode');
    Route::get('users/export', [UserController::class, 'export'])->name('users.export');
    
    // Gestion des écoles
    Route::resource('ecoles', EcoleController::class);
    
    // COURS - Routes spécialisées EN PREMIER
    Route::get('cours/{cour}/clone-form', [CoursController::class, 'showCloneForm'])->name('cours.clone.form');
    Route::post('cours/{cour}/clone', [CoursController::class, 'clone'])->name('cours.clone');
    Route::post('cours/{cour}/dupliquer-vers-session', [CoursController::class, 'dupliquerVersSession'])->name('cours.dupliquer-vers-session');
    
    // Gestion des cours - RESOURCE APRÈS
    Route::resource('cours', CoursController::class);
    
    // === SESSIONS ET HORAIRES DE COURS ===
    // Sessions de cours (périodes saisonnières)
    Route::resource('sessions', SessionCoursController::class);
    Route::post('sessions/{session}/toggle-actif', [SessionCoursController::class, 'toggleActif'])->name('sessions.toggle-actif');
    Route::post('sessions/{session}/dupliquer-horaires', [SessionCoursController::class, 'dupliquerHoraires'])->name('sessions.dupliquer-horaires');

    // Horaires de cours spécifiques
    Route::resource('cours-horaires', CoursHoraireController::class, ['parameters' => ['cours-horaires' => 'coursHoraire']]);
    Route::post('cours-horaires/{coursHoraire}/dupliquer', [CoursHoraireController::class, 'dupliquer'])->name('cours-horaires.dupliquer');
    
    // Gestion des ceintures
    Route::get('ceintures/attribution-masse', [CeintureController::class, 'createMasse'])->name('ceintures.create-masse');
    Route::post('ceintures/attribution-masse', [CeintureController::class, 'storeMasse'])->name('ceintures.store-masse');
    Route::resource('ceintures', CeintureController::class);
    
    // Gestion des séminaires
    Route::resource('seminaires', SeminaireController::class);
    Route::match(['get', 'post'], 'seminaires/{seminaire}/inscrire', [SeminaireController::class, 'inscrire'])->name('seminaires.inscrire');
    
    // Gestion des paiements
    Route::resource('paiements', PaiementController::class);
    Route::post('paiements/bulk-validate', [PaiementController::class, 'bulkValidate'])->name('paiements.bulk-validate');
    Route::patch('paiements/{paiement}/quick-validate', [PaiementController::class, 'quickValidate'])->name('paiements.quick-validate');
    
    // Gestion des présences
    Route::resource('presences', PresenceController::class);
    
    // Gestion des inscriptions aux séminaires
    Route::resource('inscriptions-seminaires', InscriptionSeminaireController::class)->only(['index', 'destroy']);
    
    // Logs et monitoring
    Route::get('logs', [LogController::class, 'index'])->name('logs.index');
    Route::post('logs/clear', [LogController::class, 'clear'])->name('logs.clear');
    
    // ROUTES EXPORTS & LOGS (LOI 25)
    Route::prefix('exports')->name('exports.')->group(function () {
        Route::get('/', [ExportController::class, 'index'])->name('index');
        Route::get('/logs', [ExportController::class, 'exportLogs'])->name('logs');
    });
    
    // Route pour validation rapide groupée (optimisation AJAX)
    Route::post('paiements/quick-bulk-validate', [PaiementController::class, 'quickBulkValidate'])->name('paiements.quick-bulk-validate');

    // Routes spéciales pour séminaires avec inscriptions
    Route::get('seminaires/{seminaire}/inscriptions', [SeminaireController::class, 'inscriptions'])->name('seminaires.inscriptions');
    Route::post('seminaires/{seminaire}/bulk-validate-inscriptions', [SeminaireController::class, 'bulkValidateInscriptions'])->name('seminaires.bulk-validate-inscriptions');
});

// Route de test pour la nouvelle interface moderne
Route::get('/users/modern', function() {
    return view('admin.users.index-modern', [
        'users' => collect([
            (object)[
                'id' => 1,
                'name' => 'Louis Alpha',
                'email' => 'lalpha@4lb.ca',
                'created_at' => now()->subDays(2),
                'ecole' => (object)['nom' => 'École Test Montréal']
            ],
            (object)[
                'id' => 2, 
                'name' => 'Admin École',
                'email' => 'admin@ecole.ca',
                'created_at' => now()->subWeek(),
                'ecole' => (object)['nom' => 'École Test Québec']
            ]
        ])
    ]);
})->name('admin.users.modern');

// Attribution ceintures masse
Route::get('/ceintures/attribution', [CeintureController::class, 'attribution'])->name('ceintures.attribution');
Route::post('/ceintures/attribution', [CeintureController::class, 'storeAttribution'])->name('ceintures.attribution.store');

