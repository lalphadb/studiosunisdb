#!/usr/bin/env php
<?php
/**
 * Diagnostic rapide du problème de double sidebar
 */

echo "🔍 Diagnostic Double Sidebar\n";
echo "============================\n\n";

// 1. Vérifier que Dashboard.vue utilise le layout correctement
echo "1️⃣ Vérification de Dashboard.vue:\n";
$dashboardContent = file_get_contents('resources/js/Pages/Dashboard.vue');

// Ne devrait PAS avoir <AuthenticatedLayout> dans le template
if (strpos($dashboardContent, '<AuthenticatedLayout>') !== false) {
    echo "❌ Dashboard.vue utilise <AuthenticatedLayout> dans le template (mauvais)\n";
    echo "   Le layout devrait être défini comme propriété du composant\n";
} else {
    echo "✅ Dashboard.vue n'utilise pas <AuthenticatedLayout> dans le template\n";
}

// Devrait avoir layout: AuthenticatedLayout dans le script
if (strpos($dashboardContent, 'layout: AuthenticatedLayout') !== false) {
    echo "✅ Dashboard.vue définit le layout comme propriété\n";
} else {
    echo "⚠️  Dashboard.vue ne définit pas le layout comme propriété\n";
}

// 2. Vérifier app.js
echo "\n2️⃣ Vérification de app.js:\n";
$appContent = file_get_contents('resources/js/app.js');

// Ne devrait PAS ajouter automatiquement le layout
if (strpos($appContent, 'page.default.layout = AuthenticatedLayout') !== false) {
    echo "❌ app.js ajoute automatiquement le layout (peut causer duplication)\n";
} else {
    echo "✅ app.js n'ajoute pas automatiquement le layout\n";
}

// 3. Vérifier l'icône chevron
echo "\n3️⃣ Vérification de l'icône chevron:\n";
$layoutContent = file_get_contents('resources/js/Layouts/AuthenticatedLayout.vue');

// L'icône ne devrait avoir qu'un seul chevron
if (strpos($layoutContent, 'M11 19l-7-7 7-7m8 14l-7-7 7-7') !== false) {
    echo "❌ L'icône chevron a deux chevrons (double <<)\n";
} elseif (strpos($layoutContent, 'M15 19l-7-7 7-7') !== false) {
    echo "✅ L'icône chevron est correcte (un seul <)\n";
} else {
    echo "⚠️  Icône chevron non trouvée ou différente\n";
}

// 4. Vérifier les composants
echo "\n4️⃣ Vérification des composants:\n";
$components = [
    'ModernStatsCard' => 'resources/js/Components/ModernStatsCard.vue',
    'ModernActionCard' => 'resources/js/Components/ModernActionCard.vue'
];

foreach ($components as $name => $path) {
    if (file_exists($path)) {
        echo "✅ $name existe\n";
    } else {
        echo "❌ $name manquant\n";
    }
}

// 5. Instructions
echo "\n============================\n";
echo "📋 RÉSUMÉ:\n";
echo "============================\n";

$allGood = true;
if (strpos($dashboardContent, '<AuthenticatedLayout>') !== false) {
    echo "⚠️  Dashboard.vue doit être corrigé\n";
    $allGood = false;
}
if (strpos($appContent, 'page.default.layout = AuthenticatedLayout') !== false) {
    echo "⚠️  app.js doit être corrigé\n";
    $allGood = false;
}
if (strpos($layoutContent, 'M11 19l-7-7 7-7m8 14l-7-7 7-7') !== false) {
    echo "⚠️  L'icône chevron doit être corrigée\n";
    $allGood = false;
}

if ($allGood) {
    echo "✅ Tout semble correct!\n\n";
    echo "Actions à faire:\n";
    echo "1. npm run build\n";
    echo "2. php artisan serve\n";
    echo "3. Rafraîchir le navigateur avec Ctrl+Shift+R\n";
} else {
    echo "\n⚠️  Des corrections sont nécessaires\n";
}

echo "\n";
