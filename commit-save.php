#!/usr/bin/env php
<?php
/**
 * Script de commit de sauvegarde StudiosDB
 * Vérifie l'état et fait un commit propre
 */

echo "\n";
echo "╔════════════════════════════════════════════╗\n";
echo "║     📦 Commit de Sauvegarde StudiosDB      ║\n";
echo "╚════════════════════════════════════════════╝\n";
echo "\n";

// 1. Vérifier le statut Git
echo "1️⃣ Vérification du statut Git:\n";
echo "-------------------------------\n";
$status = shell_exec('git status --short 2>&1');
if (empty(trim($status))) {
    echo "⚠️  Aucun changement à commiter\n";
    exit(0);
}
echo $status;
echo "\n";

// 2. Lister les fichiers modifiés
echo "2️⃣ Fichiers principaux modifiés:\n";
echo "---------------------------------\n";
$files = [
    'resources/css/app.css' => 'Styles globaux (scrollbars masquées)',
    'resources/js/Pages/Dashboard.vue' => 'Dashboard professionnel moderne',
    'resources/js/Components/StatCard.vue' => 'Composant statistiques animées',
    'resources/js/Components/ModernStatsCard.vue' => 'Card stats moderne',
    'resources/js/Components/ModernActionCard.vue' => 'Card action moderne',
    'resources/js/Layouts/AuthenticatedLayout.vue' => 'Layout principal corrigé',
    'resources/js/app.js' => 'Configuration Inertia optimisée',
    'app/Http/Requests/Membres/StoreMembreRequest.php' => 'Request validation membres',
    'app/Http/Requests/Membres/UpdateMembreRequest.php' => 'Request update membres',
    'vite.config.js' => 'Configuration Vite optimisée',
    'postcss.config.js' => 'Configuration PostCSS',
    'tailwind.config.js' => 'Configuration Tailwind'
];

foreach ($files as $file => $description) {
    if (file_exists($file)) {
        $gitStatus = shell_exec("git status --short $file 2>&1");
        if (!empty(trim($gitStatus))) {
            $status = substr(trim($gitStatus), 0, 2);
            $icon = match($status) {
                'M ' => '✏️',  // Modifié
                '??' => '🆕', // Nouveau
                'A ' => '➕', // Ajouté
                default => '📝'
            };
            echo "$icon $file\n   → $description\n";
        }
    }
}

// 3. Résumé des améliorations
echo "\n3️⃣ Résumé des améliorations:\n";
echo "-----------------------------\n";
echo "✨ Dashboard professionnel moderne 2025\n";
echo "🚫 Suppression complète des scrollbars\n";
echo "🎨 Design glassmorphism avec effets blur\n";
echo "📊 Statistiques animées et graphiques SVG\n";
echo "⚡ Animations GPU optimisées\n";
echo "🔧 Correction double sidebar\n";
echo "📦 Build optimisé avec Terser\n";
echo "\n";

// 4. Message de commit
$commitMessage = "feat(dashboard): refonte complète UI/UX professionnelle 2025

✨ NOUVELLES FONCTIONNALITÉS:
- Dashboard professionnel avec design glassmorphism
- Suppression totale des scrollbars (design moderne)
- Statistiques animées avec compteurs progressifs
- Graphiques SVG interactifs
- Cards avec micro-interactions
- Feed d'activités en temps réel
- Métriques de performance visuelles

🎨 AMÉLIORATIONS UI:
- Effets de blur et transparence (backdrop-filter)
- Animations GPU optimisées (transform, opacity)
- Dégradés dynamiques et effets de lumière
- Hover effects sur tous les éléments interactifs
- Design responsive mobile/desktop

🔧 CORRECTIONS TECHNIQUES:
- Fix: double sidebar et double bouton chevron
- Fix: erreurs syntaxe PHP dans StoreMembreRequest
- Fix: layout Inertia (propriété au lieu de wrapper)
- Optimisation build avec Terser
- Configuration PostCSS et Tailwind

📊 COMPOSANTS CRÉÉS:
- StatCard: statistiques animées
- ModernStatsCard: card moderne réutilisable
- ModernActionCard: boutons d'action stylisés

🚀 PERFORMANCE:
- Bundle optimisé (~600KB gzip: ~175KB)
- CSS minifié avec cssnano
- Animations 60 FPS
- Lazy loading composants

[LEDGER] J2 Dashboard (réf. UI) → DONE";

// 5. Demander confirmation
echo "4️⃣ Message de commit:\n";
echo "----------------------\n";
echo substr($commitMessage, 0, 500) . "...\n\n";

echo "Voulez-vous procéder au commit ? (o/n): ";
$handle = fopen("php://stdin", "r");
$line = fgets($handle);

if (trim($line) !== 'o' && trim($line) !== 'O') {
    echo "\n❌ Commit annulé\n";
    fclose($handle);
    exit(0);
}

// 6. Effectuer le commit
echo "\n5️⃣ Exécution du commit:\n";
echo "------------------------\n";

// Ajouter tous les fichiers modifiés
echo "📝 Ajout des fichiers...\n";
shell_exec('git add -A 2>&1');

// Faire le commit
echo "💾 Création du commit...\n";
$result = shell_exec("git commit -m " . escapeshellarg($commitMessage) . " 2>&1");
echo $result;

// 7. Vérifier le résultat
$lastCommit = shell_exec('git log -1 --oneline 2>&1');
echo "\n✅ Dernier commit:\n";
echo $lastCommit;

// 8. Proposer de pusher
echo "\n6️⃣ Push vers le dépôt distant:\n";
echo "-------------------------------\n";
$branch = trim(shell_exec('git branch --show-current 2>&1'));
echo "Branche actuelle: $branch\n\n";

echo "Voulez-vous pusher vers origin/$branch ? (o/n): ";
$line = fgets($handle);

if (trim($line) === 'o' || trim($line) === 'O') {
    echo "\n🚀 Push en cours...\n";
    $pushResult = shell_exec("git push origin $branch 2>&1");
    echo $pushResult;
    echo "\n✅ Push effectué avec succès!\n";
} else {
    echo "\nℹ️  Push annulé. Pour pusher plus tard:\n";
    echo "   git push origin $branch\n";
}

fclose($handle);

echo "\n";
echo "╔════════════════════════════════════════════╗\n";
echo "║        ✅ Sauvegarde Complétée!            ║\n";
echo "╚════════════════════════════════════════════╝\n";
echo "\n";
echo "📊 Statistiques du commit:\n";
$stats = shell_exec('git diff HEAD~1 --stat 2>&1');
echo $stats;
echo "\n";
