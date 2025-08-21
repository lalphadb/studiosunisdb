<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Membre;
use App\Models\Ceinture;
use App\Models\Cours;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

echo "\n=== ANALYSE DE LA BASE DE DONNÉES ===\n\n";

// 1. Tables existantes
echo "📊 TABLES EXISTANTES:\n";
echo "--------------------\n";
$tables = DB::select('SHOW TABLES');
foreach ($tables as $table) {
    $tableName = array_values((array)$table)[0];
    $count = DB::table($tableName)->count();
    echo "- $tableName ($count lignes)\n";
}

// 2. Utilisateurs
echo "\n👤 UTILISATEURS:\n";
echo "----------------\n";
$users = User::all();
echo "Total: " . $users->count() . " utilisateurs\n";
foreach ($users as $user) {
    $roles = $user->getRoleNames()->join(', ') ?: 'Aucun rôle';
    echo "- {$user->name} ({$user->email}) - Rôles: $roles\n";
}

// 3. Rôles et Permissions
echo "\n🔐 RÔLES:\n";
echo "---------\n";
$roles = Role::all();
foreach ($roles as $role) {
    $permsCount = $role->permissions->count();
    echo "- {$role->name} ({$permsCount} permissions)\n";
}

// 4. Ceintures
echo "\n🥋 CEINTURES:\n";
echo "-------------\n";
$ceintures = Ceinture::orderBy('ordre')->get();
echo "Total: " . $ceintures->count() . " ceintures\n";
if ($ceintures->count() > 0) {
    foreach ($ceintures->take(5) as $c) {
        echo "- {$c->ordre}. {$c->nom} ({$c->couleur_hex})\n";
    }
    if ($ceintures->count() > 5) {
        echo "... et " . ($ceintures->count() - 5) . " autres\n";
    }
}

// 5. Membres
echo "\n👥 MEMBRES:\n";
echo "-----------\n";
$membres = Membre::all();
echo "Total: " . $membres->count() . " membres\n";
if ($membres->count() > 0) {
    echo "- Actifs: " . Membre::where('statut', 'actif')->count() . "\n";
    echo "- Inactifs: " . Membre::where('statut', 'inactif')->count() . "\n";
    echo "- Suspendus: " . Membre::where('statut', 'suspendu')->count() . "\n";
}

// 6. Cours
echo "\n📚 COURS:\n";
echo "---------\n";
$cours = Cours::all();
echo "Total: " . $cours->count() . " cours\n";
if ($cours->count() > 0) {
    echo "- Actifs: " . Cours::where('actif', true)->count() . "\n";
    echo "- Inactifs: " . Cours::where('actif', false)->count() . "\n";
}

// 7. Vérifier SuperAdmin
echo "\n⚠️  SUPER ADMIN ACTUEL:\n";
echo "----------------------\n";
$superAdmin = User::where('email', 'louis@4lb.ca')->first();
if ($superAdmin) {
    echo "✅ Trouvé: {$superAdmin->name} ({$superAdmin->email})\n";
    echo "   Rôles: " . $superAdmin->getRoleNames()->join(', ') . "\n";
} else {
    echo "❌ Aucun utilisateur avec email louis@4lb.ca\n";
}

echo "\n=== FIN DE L'ANALYSE ===\n\n";
