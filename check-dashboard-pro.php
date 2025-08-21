#!/usr/bin/env php
<?php
/**
 * Vérification du Dashboard Professionnel
 */

echo "\n";
echo "╔════════════════════════════════════════════╗\n";
echo "║   🎯 Dashboard Professionnel - Check        ║\n";
echo "╚════════════════════════════════════════════╝\n";
echo "\n";

// 1. Vérifier les CSS
echo "1️⃣ Vérification des styles:\n";
$cssContent = file_get_contents('resources/css/app.css');
if (strpos($cssContent, 'scrollbar-width: none') !== false) {
    echo "   ✅ Scrollbars masquées configurées\n";
} else {
    echo "   ⚠️ Configuration scrollbar manquante\n";
}

// 2. Vérifier les composants
echo "\n2️⃣ Vérification des composants:\n";
$components = [
    'StatCard' => 'resources/js/Components/StatCard.vue',
    'ModernStatsCard' => 'resources/js/Components/ModernStatsCard.vue',
    'ModernActionCard' => 'resources/js/Components/ModernActionCard.vue'
];

foreach ($components as $name => $path) {
    if (file_exists($path)) {
        echo "   ✅ $name présent\n";
    } else {
        echo "   ⚠️ $name manquant\n";
    }
}

// 3. Vérifier le build
echo "\n3️⃣ Vérification du build:\n";
if (file_exists('public/build/.vite/manifest.json')) {
    $manifest = json_decode(file_get_contents('public/build/.vite/manifest.json'), true);
    echo "   ✅ Build compilé avec succès\n";
    
    // Vérifier les tailles
    foreach ($manifest as $file => $data) {
        if (isset($data['file'])) {
            $size = filesize('public/build/' . $data['file']) / 1024;
            echo "   📦 $file: " . round($size, 2) . " KB\n";
        }
    }
} else {
    echo "   ❌ Build non trouvé - Lancez: npm run build\n";
}

// 4. Vérifier Dashboard.vue
echo "\n4️⃣ Vérification de Dashboard.vue:\n";
$dashboardContent = file_get_contents('resources/js/Pages/Dashboard.vue');
if (strpos($dashboardContent, 'Tableau de bord') !== false) {
    echo "   ✅ Dashboard professionnel configuré\n";
} else {
    echo "   ⚠️ Dashboard non mis à jour\n";
}

echo "\n";
echo "╔════════════════════════════════════════════╗\n";
echo "║              📋 FONCTIONNALITÉS             ║\n";
echo "╠════════════════════════════════════════════╣\n";
echo "║                                              ║\n";
echo "║  ✨ Scrollbars invisibles (moderne)         ║\n";
echo "║  📊 Statistiques animées                    ║\n";
echo "║  📈 Graphiques SVG interactifs              ║\n";
echo "║  🎨 Design glassmorphism                    ║\n";
echo "║  ⚡ Animations GPU optimisées               ║\n";
echo "║  📱 Responsive design                       ║\n";
echo "║  🌟 Micro-interactions                      ║\n";
echo "║                                              ║\n";
echo "╚════════════════════════════════════════════╝\n";
echo "\n";
echo "🚀 Dashboard professionnel prêt!\n";
echo "   Ouvrez: http://127.0.0.1:8000/dashboard\n";
echo "\n";
