#!/usr/bin/env php
<?php
/**
 * Vérification complète de l'intégration Vite + Laravel
 */

echo "🔍 Diagnostic complet Vite + Laravel\n";
echo "=====================================\n\n";

// 1. Vérifier que Vite est actif
echo "1️⃣ Serveur Vite:\n";
$viteUrl = 'http://127.0.0.1:5173';
$ch = curl_init($viteUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_NOBODY, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 2);
curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode > 0) {
    echo "✅ Vite est actif sur $viteUrl (HTTP $httpCode)\n";
} else {
    echo "❌ Vite n'est pas accessible\n";
    echo "   Lancez: php vite-manager.php start\n";
    exit(1);
}

// 2. Vérifier le fichier @vite/client
echo "\n2️⃣ Module @vite/client:\n";
$viteClientUrl = 'http://127.0.0.1:5173/@vite/client';
$ch = curl_init($viteClientUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 2);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode === 200) {
    echo "✅ @vite/client accessible\n";
} else {
    echo "❌ @vite/client non accessible (HTTP $httpCode)\n";
}

// 3. Vérifier les assets principaux
echo "\n3️⃣ Assets principaux:\n";
$assets = [
    'resources/css/app.css' => 'http://127.0.0.1:5173/resources/css/app.css',
    'resources/js/app.js' => 'http://127.0.0.1:5173/resources/js/app.js'
];

foreach ($assets as $name => $url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 2);
    curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode === 200) {
        echo "✅ $name accessible\n";
    } else {
        echo "❌ $name non accessible (HTTP $httpCode)\n";
    }
}

// 4. Vérifier la configuration Laravel
echo "\n4️⃣ Configuration Laravel:\n";
$envFile = __DIR__ . '/.env';
if (file_exists($envFile)) {
    $env = parse_ini_file($envFile);
    
    if (isset($env['APP_ENV'])) {
        echo "  APP_ENV: {$env['APP_ENV']}\n";
        if ($env['APP_ENV'] === 'local') {
            echo "  ✅ Mode développement actif\n";
        }
    }
    
    if (isset($env['APP_DEBUG'])) {
        echo "  APP_DEBUG: {$env['APP_DEBUG']}\n";
    }
    
    if (isset($env['APP_URL'])) {
        echo "  APP_URL: {$env['APP_URL']}\n";
    }
}

// 5. Vérifier que Laravel est accessible
echo "\n5️⃣ Serveur Laravel:\n";
$laravelUrl = 'http://127.0.0.1:8000';
$ch = curl_init($laravelUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_NOBODY, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 2);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode > 0) {
    echo "✅ Laravel est actif sur $laravelUrl (HTTP $httpCode)\n";
} else {
    echo "⚠️  Laravel n'est pas accessible\n";
    echo "   Lancez: php artisan serve\n";
}

// 6. Instructions finales
echo "\n=====================================\n";
echo "📝 RÉSUMÉ:\n";
echo "=====================================\n";

if ($httpCode > 0) {
    echo "✅ Tout est opérationnel!\n\n";
    echo "🌐 Accédez à votre application:\n";
    echo "   http://127.0.0.1:8000/dashboard\n\n";
    echo "⚡ Hot Module Replacement (HMR) actif\n";
    echo "   Les modifications seront appliquées automatiquement\n\n";
    echo "🛑 Pour arrêter Vite:\n";
    echo "   php vite-manager.php stop\n";
} else {
    echo "⚠️  Configuration incomplète\n\n";
    echo "📋 Actions requises:\n";
    echo "1. Assurez-vous que Vite est actif:\n";
    echo "   php vite-manager.php status\n";
    echo "2. Démarrez Laravel:\n";
    echo "   php artisan serve\n";
    echo "3. Accédez à:\n";
    echo "   http://127.0.0.1:8000\n";
}

echo "\n";
