#!/usr/bin/env php
<?php

/**
 * Script de démarrage rapide StudiosDB
 * Usage: php start.php
 */
echo "\n";
echo "╔════════════════════════════════════════╗\n";
echo "║     🚀 StudiosDB v5 Pro - Démarrage    ║\n";
echo "╚════════════════════════════════════════╝\n";
echo "\n";

// 1. Nettoyer les caches
echo "1️⃣  Nettoyage des caches...\n";
passthru('php artisan optimize:clear');

// 2. Vérifier la syntaxe
echo "\n2️⃣  Vérification de la syntaxe PHP...\n";
$files = [
    'app/Http/Requests/Membres/StoreMembreRequest.php',
    'app/Http/Requests/Membres/UpdateMembreRequest.php',
];

$syntaxOk = true;
foreach ($files as $file) {
    $output = [];
    $return = 0;
    exec("php -l $file 2>&1", $output, $return);

    if ($return === 0) {
        echo '   ✅ '.basename($file)." OK\n";
    } else {
        echo '   ❌ '.basename($file)." ERREUR\n";
        $syntaxOk = false;
    }
}

if (! $syntaxOk) {
    echo "\n❌ Des erreurs de syntaxe ont été détectées.\n";
    echo "Corrigez les erreurs avant de continuer.\n";
    exit(1);
}

// 3. Vérifier Vite
echo "\n3️⃣  Vérification de Vite...\n";
$viteRunning = @file_get_contents('http://127.0.0.1:5173/@vite/client');
if ($viteRunning !== false) {
    echo "   ✅ Vite est actif sur le port 5173\n";
} else {
    echo "   ⚠️  Vite n'est pas actif\n";
    echo "   Lancez dans un autre terminal: npm run dev\n";
}

// 4. Instructions finales
echo "\n";
echo "╔════════════════════════════════════════════════════╗\n";
echo "║                  📋 INSTRUCTIONS                    ║\n";
echo "╠════════════════════════════════════════════════════╣\n";
echo "║                                                      ║\n";
echo "║  Terminal 1 (Vite - si pas actif):                  ║\n";
echo "║  $ npm run dev                                      ║\n";
echo "║                                                      ║\n";
echo "║  Terminal 2 (Laravel):                              ║\n";
echo "║  $ php artisan serve                                ║\n";
echo "║                                                      ║\n";
echo "║  Navigateur:                                        ║\n";
echo "║  🌐 http://127.0.0.1:8000/dashboard                 ║\n";
echo "║                                                      ║\n";
echo "╚════════════════════════════════════════════════════╝\n";
echo "\n";

// Proposer de lancer Laravel directement
echo 'Voulez-vous démarrer Laravel maintenant ? (o/n): ';
$handle = fopen('php://stdin', 'r');
$line = fgets($handle);
if (trim($line) === 'o' || trim($line) === 'O') {
    echo "\n🚀 Démarrage de Laravel...\n";
    echo "Appuyez Ctrl+C pour arrêter le serveur\n\n";
    passthru('php artisan serve');
}

fclose($handle);
