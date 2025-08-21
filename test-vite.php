<?php
// Test si Vite est accessible
$url = 'http://127.0.0.1:5173';
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_NOBODY, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 2);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode > 0) {
    echo "✅ Vite est déjà en cours d'exécution sur le port 5173 (HTTP $httpCode)\n";
    echo "Le serveur de développement est actif!\n";
    echo "\nPour arrêter le serveur existant, utilisez:\n";
    echo "kill 252655  # Pour tuer le processus npm run dev\n";
    echo "kill 252667  # Pour tuer le processus node\n";
} else {
    echo "❌ Vite n'est pas accessible sur le port 5173\n";
}

// Vérifier aussi avec file_get_contents
$context = stream_context_create([
    'http' => ['timeout' => 2]
]);
$test = @file_get_contents($url, false, $context);
if ($test !== false || !empty($http_response_header)) {
    echo "\n✅ Confirmation: Le serveur Vite répond!\n";
    if (!empty($http_response_header)) {
        echo "Headers reçus:\n";
        foreach ($http_response_header as $header) {
            echo "  - $header\n";
        }
    }
}
