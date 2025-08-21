#!/usr/bin/env php
<?php
// Script de diagnostic pour npm run dev

echo "🔍 Diagnostic npm run dev\n";
echo "==========================\n\n";

// Vérifier Node et npm
echo "📦 Versions:\n";
passthru('node --version 2>&1', $nodeReturn);
if ($nodeReturn !== 0) {
    echo "❌ Node.js n'est pas accessible\n";
}
passthru('npm --version 2>&1', $npmReturn);
if ($npmReturn !== 0) {
    echo "❌ npm n'est pas accessible\n";
}

// Vérifier que les dépendances sont installées
echo "\n📋 Vérification node_modules:\n";
if (file_exists('node_modules')) {
    $count = count(glob('node_modules/*', GLOB_ONLYDIR));
    echo "✅ node_modules existe avec $count packages\n";
    
    // Vérifier vite spécifiquement
    if (file_exists('node_modules/.bin/vite')) {
        echo "✅ vite binaire présent\n";
    } else {
        echo "❌ vite binaire manquant dans node_modules/.bin/\n";
    }
} else {
    echo "❌ node_modules n'existe pas - Lancer 'npm install'\n";
}

// Vérifier la configuration
echo "\n📝 Fichiers de configuration:\n";
$configs = ['vite.config.js', 'package.json', 'package-lock.json'];
foreach ($configs as $config) {
    if (file_exists($config)) {
        echo "✅ $config présent\n";
    } else {
        echo "❌ $config manquant\n";
    }
}

// Essayer de lancer vite avec capture d'erreur
echo "\n🚀 Tentative de lancement de vite:\n";
echo "Commande: npm run dev\n";
echo "----------------------------------------\n";

// Utiliser exec pour capturer stdout et stderr
$output = [];
$return = 0;
exec('npm run dev 2>&1', $output, $return);

if ($return !== 0) {
    echo "❌ Erreur lors du lancement (code: $return)\n";
    echo "Message d'erreur:\n";
    echo implode("\n", $output) . "\n";
    
    // Essayer directement avec node
    echo "\n🔧 Essai direct avec node:\n";
    $output2 = [];
    exec('node node_modules/vite/bin/vite.js 2>&1', $output2, $return2);
    if ($return2 !== 0) {
        echo "❌ Erreur avec node direct:\n";
        echo implode("\n", $output2) . "\n";
    }
} else {
    echo "✅ Vite a démarré avec succès\n";
}

// Vérifier les ports
echo "\n🌐 Vérification des ports:\n";
exec('lsof -i:5173 2>&1', $portOutput, $portReturn);
if ($portReturn === 0) {
    echo "⚠️ Port 5173 est déjà utilisé:\n";
    echo implode("\n", $portOutput) . "\n";
} else {
    echo "✅ Port 5173 est libre\n";
}

echo "\n==========================\n";
echo "✨ Diagnostic terminé\n";
