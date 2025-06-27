<?php

// Bootstrap Laravel
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Cours;

echo "🔍 TEST PERMISSIONS LOUIS@4LB.CA\n";
echo "================================\n\n";

// Trouver Louis
$louis = User::where('email', 'louis@4lb.ca')->first();

if (!$louis) {
    echo "❌ Utilisateur louis@4lb.ca non trouvé!\n";
    exit(1);
}

echo "✅ Utilisateur trouvé:\n";
echo "   Nom: {$louis->name}\n";
echo "   Email: {$louis->email}\n";
echo "   École ID: {$louis->ecole_id}\n";
echo "   École: " . ($louis->ecole->nom ?? 'Non assignée') . "\n\n";

// Vérifier les rôles
$roles = $louis->roles;
echo "🎭 RÔLES ({$roles->count()}):\n";
foreach ($roles as $role) {
    echo "   - {$role->name}\n";
}
echo "\n";

// Vérifier les permissions
$permissions = $louis->getAllPermissions();
echo "🔑 PERMISSIONS ({$permissions->count()}):\n";
foreach ($permissions->take(10) as $permission) {
    echo "   - {$permission->name}\n";
}
if ($permissions->count() > 10) {
    echo "   ... et " . ($permissions->count() - 10) . " autres\n";
}
echo "\n";

// Tester les permissions spécifiques aux cours
echo "📚 PERMISSIONS COURS:\n";
echo "   viewAny Cours: " . ($louis->can('viewAny', Cours::class) ? '✅ OUI' : '❌ NON') . "\n";
echo "   create Cours: " . ($louis->can('create', Cours::class) ? '✅ OUI' : '❌ NON') . "\n";

// Tester avec un cours existant
$cours = Cours::first();
if ($cours) {
    echo "   view Cours #{$cours->id}: " . ($louis->can('view', $cours) ? '✅ OUI' : '❌ NON') . "\n";
    echo "   update Cours #{$cours->id}: " . ($louis->can('update', $cours) ? '✅ OUI' : '❌ NON') . "\n";
    echo "   delete Cours #{$cours->id}: " . ($louis->can('delete', $cours) ? '✅ OUI' : '❌ NON') . "\n";
} else {
    echo "   ⚠️ Aucun cours en base pour tester\n";
}

echo "\n🎯 Test terminé!\n";
