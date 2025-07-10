<?php
// filepath: verify_database.php

use App\Models\Ceinture;
use App\Models\Ecole;
use App\Models\User;

echo "=== VÉRIFICATION BASE DE DONNÉES STUDIOSDB ===\n\n";

// Test connexion
try {
    DB::connection()->getPdo();
    echo "✅ Connexion base de données: OK\n\n";
} catch (\Exception $e) {
    echo "❌ Erreur connexion: " . $e->getMessage() . "\n";
    exit;
}

// Liste des Ceintures
echo "=== CEINTURES DISPONIBLES ===\n";
$ceintures = Ceinture::orderBy('ordre')->get(['id', 'nom', 'couleur', 'ordre']);
echo "Total ceintures: " . $ceintures->count() . "\n\n";

foreach ($ceintures as $ceinture) {
    echo sprintf("ID: %2d | %-20s | Couleur: %-15s | Ordre: %d\n", 
        $ceinture->id, 
        $ceinture->nom, 
        $ceinture->couleur, 
        $ceinture->ordre
    );
}

echo "\n=== ÉCOLES ENREGISTRÉES ===\n";
$ecoles = Ecole::with('users')->get(['id', 'nom', 'adresse', 'telephone', 'email']);
echo "Total écoles: " . $ecoles->count() . "\n\n";

foreach ($ecoles as $ecole) {
    echo sprintf("ID: %2d | %-30s\n", $ecole->id, $ecole->nom);
    echo sprintf("       | Adresse: %s\n", $ecole->adresse ?? 'Non définie');
    echo sprintf("       | Tél: %s | Email: %s\n", 
        $ecole->telephone ?? 'N/A', 
        $ecole->email ?? 'N/A'
    );
    echo sprintf("       | Utilisateurs: %d\n", $ecole->users->count());
    echo "       " . str_repeat("-", 50) . "\n";
}

// Statistiques globales
echo "\n=== STATISTIQUES GLOBALES ===\n";
echo "Ceintures: " . Ceinture::count() . "\n";
echo "Écoles: " . Ecole::count() . "\n";
echo "Utilisateurs: " . User::count() . "\n";
echo "Cours: " . (class_exists('App\Models\Cours') ? App\Models\Cours::count() : 'Modèle non trouvé') . "\n";
