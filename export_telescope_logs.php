<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔭 EXPORT TELESCOPE LOGS StudiosUnisDB\n";
echo "=====================================\n";

try {
    // Vérifier la connexion DB
    $connection = DB::connection()->getPdo();
    echo "✅ Connexion base de données OK\n";
    
    // Export des entrées Telescope récentes
    $filename = 'telescope_export_' . date('Ymd_His') . '.json';
    
    echo "📊 Export des entrées Telescope...\n";
    
    // Requête des entrées récentes (dernières 24h)
    $entries = DB::table('telescope_entries')
        ->where('created_at', '>=', now()->subDay())
        ->orderBy('created_at', 'desc')
        ->limit(1000)
        ->get();
    
    echo "📋 Entrées trouvées: " . $entries->count() . "\n";
    
    if ($entries->count() > 0) {
        // Convertir en array et encoder en JSON
        $data = [
            'export_date' => now()->toISOString(),
            'total_entries' => $entries->count(),
            'entries' => $entries->toArray()
        ];
        
        file_put_contents($filename, json_encode($data, JSON_PRETTY_PRINT));
        
        echo "✅ Export terminé: $filename\n";
        echo "📁 Taille: " . number_format(filesize($filename) / 1024, 2) . " KB\n";
        
        // Statistiques par type
        echo "\n📊 STATISTIQUES:\n";
        $types = DB::table('telescope_entries')
            ->where('created_at', '>=', now()->subDay())
            ->select('type', DB::raw('count(*) as count'))
            ->groupBy('type')
            ->get();
            
        foreach ($types as $type) {
            echo "   - {$type->type}: {$type->count}\n";
        }
        
    } else {
        echo "⚠️ Aucune entrée Telescope trouvée\n";
    }
    
    // Export des erreurs uniquement
    echo "\n❌ Export des erreurs...\n";
    $errors = DB::table('telescope_entries')
        ->where('type', 'exception')
        ->where('created_at', '>=', now()->subWeek())
        ->orderBy('created_at', 'desc')
        ->get();
        
    if ($errors->count() > 0) {
        $errorFile = 'telescope_errors_' . date('Ymd_His') . '.json';
        file_put_contents($errorFile, json_encode($errors->toArray(), JSON_PRETTY_PRINT));
        echo "✅ Erreurs exportées: $errorFile ({$errors->count()} entrées)\n";
    } else {
        echo "✅ Aucune erreur trouvée\n";
    }
    
} catch (Exception $e) {
    echo "❌ ERREUR: " . $e->getMessage() . "\n";
    exit(1);
}

echo "\n🎯 Export Telescope terminé !\n";
