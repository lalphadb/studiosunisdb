<?php

// Vérifier le contenu actuel du fichier routes/admin.php
$routesFile = 'routes/admin.php';
$content = file_get_contents($routesFile);

// Vérifier si les routes ceintures existent déjà
if (!strpos($content, 'ceintures.types')) {
    echo "Ajout des routes ceintures manquantes...\n";
    
    // Ajouter les routes complètes
    $newRoutes = "

// ===== ROUTES CEINTURES COMPLÈTES =====
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    
    // Route principale ceintures (historique)
    Route::resource('ceintures', App\Http\Controllers\Admin\CeintureController::class);
    
    // Routes supplémentaires pour les ceintures
    Route::get('ceintures-management/types', function() {
        return view('admin.ceintures.types');
    })->name('ceintures.types');
    
    Route::get('ceintures-management/attribuer', function() {
        return view('admin.ceintures.attribuer');
    })->name('ceintures.attribuer');
    
    Route::post('ceintures-management/attribuer', function(\Illuminate\Http\Request \$request) {
        try {
            \$validated = \$request->validate([
                'membre_id' => 'required|exists:membres,id',
                'ceinture_id' => 'required|exists:ceintures,id',
                'date_obtention' => 'required|date',
                'examinateur' => 'nullable|string',
                'valide' => 'boolean'
            ]);
            
            \$validated['valide'] = \$request->has('valide');
            
            \App\Models\MembreCeinture::create(\$validated);
            
            return redirect()->back()->with('success', 'Ceinture attribuée avec succès!');
        } catch (\Exception \$e) {
            return redirect()->back()->withErrors(['error' => 'Erreur: ' . \$e->getMessage()]);
        }
    })->name('ceintures.attribuer.store');
});
";
    
    file_put_contents($routesFile, $content . $newRoutes);
    echo "✅ Routes ceintures ajoutées\n";
} else {
    echo "✅ Routes ceintures déjà présentes\n";
}
