<?php
require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔭 Export Telescope Logs - StudiosUnisDB\n";
echo "========================================\n\n";

try {
    // Dernières 24h d'activité
    $since = now()->subDay();
    
    // 1. Exceptions
    echo "⚠️ EXCEPTIONS (dernières 24h):\n";
    echo str_repeat("-", 50) . "\n";
    
    $exceptions = DB::table('telescope_entries')
        ->where('type', 'exception')
        ->where('created_at', '>=', $since)
        ->orderBy('created_at', 'desc')
        ->limit(20)
        ->get();
    
    foreach ($exceptions as $exception) {
        $content = json_decode($exception->content, true);
        echo "📅 " . $exception->created_at . "\n";
        echo "🚨 " . ($content['class'] ?? 'Unknown') . "\n";
        echo "💬 " . substr($content['message'] ?? 'No message', 0, 200) . "\n";
        echo "📁 " . ($content['file'] ?? 'Unknown') . ":" . ($content['line'] ?? '?') . "\n\n";
    }
    
    // 2. Logs d'erreur
    echo "\n📋 LOGS ERREURS (dernières 24h):\n";
    echo str_repeat("-", 50) . "\n";
    
    $logs = DB::table('telescope_entries')
        ->where('type', 'log')
        ->whereRaw("JSON_EXTRACT(content, '$.level') = 'error'")
        ->where('created_at', '>=', $since)
        ->orderBy('created_at', 'desc')
        ->limit(20)
        ->get();
    
    foreach ($logs as $log) {
        $content = json_decode($log->content, true);
        echo "📅 " . $log->created_at . "\n";
        echo "🔥 " . ($content['level'] ?? 'error') . "\n";
        echo "💬 " . substr($content['message'] ?? 'No message', 0, 200) . "\n\n";
    }
    
    // 3. Requêtes lentes
    echo "\n🐌 REQUÊTES LENTES (>100ms, dernières 24h):\n";
    echo str_repeat("-", 50) . "\n";
    
    $slowQueries = DB::table('telescope_entries')
        ->where('type', 'query')
        ->whereRaw("CAST(JSON_EXTRACT(content, '$.time') AS DECIMAL) > 100")
        ->where('created_at', '>=', $since)
        ->orderByRaw("CAST(JSON_EXTRACT(content, '$.time') AS DECIMAL) DESC")
        ->limit(10)
        ->get();
    
    foreach ($slowQueries as $query) {
        $content = json_decode($query->content, true);
        echo "📅 " . $query->created_at . "\n";
        echo "⏱️ " . ($content['time'] ?? '?') . "ms\n";
        echo "🗄️ " . substr($content['sql'] ?? 'No SQL', 0, 150) . "\n\n";
    }
    
    // 4. Requêtes HTTP échouées
    echo "\n❌ REQUÊTES HTTP ÉCHOUÉES (4xx/5xx, dernières 24h):\n";
    echo str_repeat("-", 50) . "\n";
    
    $failedRequests = DB::table('telescope_entries')
        ->where('type', 'request')
        ->whereRaw("CAST(JSON_EXTRACT(content, '$.response_status') AS UNSIGNED) >= 400")
        ->where('created_at', '>=', $since)
        ->orderBy('created_at', 'desc')
        ->limit(10)
        ->get();
    
    foreach ($failedRequests as $request) {
        $content = json_decode($request->content, true);
        echo "📅 " . $request->created_at . "\n";
        echo "🌐 " . ($content['method'] ?? '?') . " " . ($content['uri'] ?? '?') . "\n";
        echo "📊 HTTP " . ($content['response_status'] ?? '?') . "\n";
        echo "👤 IP: " . ($content['ip_address'] ?? '?') . "\n\n";
    }
    
    echo "\n✅ Export terminé !\n";
    
} catch (Exception $e) {
    echo "❌ Erreur lors de l'export: " . $e->getMessage() . "\n";
}
