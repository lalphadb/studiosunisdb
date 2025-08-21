#!/usr/bin/env php
<?php
/**
 * Vérification que le Dashboard fonctionne
 */

echo "🔍 Vérification du Dashboard StudiosDB\n";
echo "======================================\n\n";

// 1. Vérifier la syntaxe de tous les fichiers Request
echo "1️⃣ Vérification syntaxe PHP des Requests:\n";
$requestFiles = glob('app/Http/Requests/**/*.php');
$errors = 0;

foreach ($requestFiles as $file) {
    $output = [];
    $return = 0;
    exec("php -l $file 2>&1", $output, $return);
    
    if ($return !== 0) {
        echo "❌ Erreur dans: $file\n";
        echo "   " . implode("\n   ", $output) . "\n";
        $errors++;
    }
}

if ($errors === 0) {
    echo "✅ Tous les fichiers Request sont valides (" . count($requestFiles) . " fichiers)\n";
} else {
    echo "❌ $errors fichiers avec erreurs de syntaxe\n";
}

// 2. Tester l'accès au dashboard
echo "\n2️⃣ Test d'accès au Dashboard:\n";
$dashboardUrl = 'http://127.0.0.1:8000/dashboard';
$ch = curl_init($dashboardUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode === 200) {
    echo "✅ Dashboard accessible (HTTP 200)\n";
    
    // Vérifier qu'il n'y a pas d'erreur PHP dans la réponse
    if (stripos($response, 'ParseError') !== false || stripos($response, 'syntax error') !== false) {
        echo "⚠️  ATTENTION: Une erreur PHP est présente dans la page!\n";
    } else {
        echo "✅ Pas d'erreur PHP détectée dans la réponse\n";
    }
} elseif ($httpCode === 302) {
    echo "⚠️  Redirection détectée (HTTP 302) - Probablement non authentifié\n";
} elseif ($httpCode === 500) {
    echo "❌ Erreur serveur (HTTP 500) - Vérifiez les logs Laravel\n";
} else {
    echo "❌ Dashboard non accessible (HTTP $httpCode)\n";
}

// 3. Vérifier les logs d'erreur
echo "\n3️⃣ Dernières erreurs dans les logs:\n";
$logFile = 'storage/logs/laravel.log';
if (file_exists($logFile)) {
    $lines = file($logFile);
    $lastLines = array_slice($lines, -20);
    $errorFound = false;
    
    foreach ($lastLines as $line) {
        if (stripos($line, 'ERROR') !== false || stripos($line, 'ParseError') !== false) {
            echo "⚠️  " . trim($line) . "\n";
            $errorFound = true;
        }
    }
    
    if (!$errorFound) {
        echo "✅ Pas d'erreur récente dans les logs\n";
    }
} else {
    echo "ℹ️  Fichier de log non trouvé\n";
}

// 4. Statut des services
echo "\n4️⃣ Statut des services:\n";

// Vite
$viteCheck = @file_get_contents('http://127.0.0.1:5173/@vite/client');
if ($viteCheck !== false) {
    echo "✅ Vite est actif (port 5173)\n";
} else {
    echo "⚠️  Vite n'est pas actif - Lancez: php vite-manager.php start\n";
}

// Laravel
$laravelCheck = @file_get_contents('http://127.0.0.1:8000');
if ($laravelCheck !== false) {
    echo "✅ Laravel est actif (port 8000)\n";
} else {
    echo "⚠️  Laravel n'est pas actif - Lancez: php artisan serve\n";
}

echo "\n======================================\n";
echo "✨ Vérification terminée\n\n";

if ($errors === 0 && $httpCode === 200) {
    echo "🎉 Tout fonctionne correctement!\n";
    echo "Accédez au dashboard: http://127.0.0.1:8000/dashboard\n";
} else {
    echo "⚠️  Des problèmes ont été détectés.\n";
    echo "Consultez les messages ci-dessus pour les résoudre.\n";
}
