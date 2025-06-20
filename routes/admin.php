<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EcoleController;
use App\Http\Controllers\Admin\MembreController;
use App\Http\Controllers\Admin\CoursController;
use App\Http\Controllers\Admin\PresenceController;
use App\Http\Controllers\Admin\CeintureController;
use App\Http\Controllers\Admin\SeminaireController;
use App\Http\Controllers\Admin\PaiementController;
use App\Http\Controllers\Admin\LogController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
    // Modules CRUD
    Route::resource('ecoles', EcoleController::class);
    Route::resource('membres', MembreController::class);
    Route::resource('cours', CoursController::class);
    Route::resource('presences', PresenceController::class);
    Route::resource('ceintures', CeintureController::class);
    Route::resource('seminaires', SeminaireController::class);
    Route::resource('paiements', PaiementController::class);
    
    // Routes spécialisées
    Route::get('membres/export', [MembreController::class, 'export']);
    Route::get('membres/{membre}/qrcode', [MembreController::class, 'qrcode']);
    Route::get('presences/scan', [PresenceController::class, 'scan']);
    Route::post('presences/scan-qr', [PresenceController::class, 'scanQr']);
    Route::get('ceintures/{id}/certificat', [CeintureController::class, 'certificat']);
    Route::post('paiements/{paiement}/valider', [PaiementController::class, 'valider']);
    Route::get('paiements/export', [PaiementController::class, 'export'])->name('paiements.export');
    
    // Nouvelle route Logs
    Route::get('logs', [LogController::class, 'index'])->name('logs.index');
});

// Routes pour la gestion complète des ceintures
Route::get('ceintures/types', function() {
    return view('admin.ceintures.types');
})->name('ceintures.types');

Route::get('ceintures/attribuer', function() {
    return view('admin.ceintures.attribuer');
})->name('ceintures.attribuer');

Route::post('ceintures/attribuer', function(\Illuminate\Http\Request $request) {
    $validated = $request->validate([
        'membre_id' => 'required|exists:membres,id',
        'ceinture_id' => 'required|exists:ceintures,id',
        'date_obtention' => 'required|date',
        'examinateur' => 'nullable|string',
        'valide' => 'boolean'
    ]);
    
    $validated['valide'] = $request->has('valide');
    
    \App\Models\MembreCeinture::create($validated);
    
    return redirect()->back()->with('success', 'Ceinture attribuée avec succès!');
})->name('ceintures.attribuer.store');

// Route Telescope Stats manquante
Route::get('telescope/stats', function() {
    try {
        $since = now()->subDay();
        return response()->json([
            'success' => true,
            'exceptions_count' => \DB::table('telescope_entries')->where('type', 'exception')->where('created_at', '>=', $since)->count(),
            'logs_count' => \DB::table('telescope_entries')->where('type', 'log')->whereRaw("JSON_EXTRACT(content, '$.level') = 'error'")->where('created_at', '>=', $since)->count(),
            'slow_queries' => \DB::table('telescope_entries')->where('type', 'query')->whereRaw("CAST(JSON_EXTRACT(content, '$.time') AS DECIMAL) > 100")->where('created_at', '>=', $since)->count(),
            'failed_requests' => \DB::table('telescope_entries')->where('type', 'request')->whereRaw("CAST(JSON_EXTRACT(content, '$.response_status') AS UNSIGNED) >= 400")->where('created_at', '>=', $since)->count(),
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'exceptions_count' => 0,
            'logs_count' => 0,
            'slow_queries' => 0,
            'failed_requests' => 0,
        ]);
    }
})->name('telescope.stats');
