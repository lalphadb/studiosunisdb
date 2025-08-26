<?php
// Mise à jour de l'utilisateur admin avec les bons credentials
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

echo "\n=== MISE À JOUR UTILISATEUR ADMIN STUDIOSDB ===\n\n";

// Supprimer l'ancien admin si existe
User::where('email', 'admin@studiosdb.ca')->delete();

// Créer les rôles si nécessaire
$roles = ['superadmin', 'admin_ecole', 'instructeur', 'membre'];
foreach ($roles as $roleName) {
    Role::firstOrCreate(
        ['name' => $roleName],
        ['guard_name' => 'web']
    );
}

// Créer ou mettre à jour l'utilisateur admin
$adminEmail = 'louis@4lb.ca';
$adminPassword = 'password123';

// Supprimer l'ancien si existe et créer le nouveau
User::where('email', $adminEmail)->delete();

$user = User::create([
    'name' => 'Louis Admin',
    'email' => $adminEmail,
    'password' => Hash::make($adminPassword),
    'email_verified_at' => now(),
]);

// Assigner le rôle admin_ecole
$user->assignRole('admin_ecole');

echo "✅ UTILISATEUR ADMIN MIS À JOUR:\n";
echo "   Email: {$adminEmail}\n";
echo "   Mot de passe: {$adminPassword}\n";
echo "   Rôle: admin_ecole\n";

// Créer quelques membres de test
try {
    if (\Schema::hasTable('membres')) {
        \DB::table('membres')->truncate();
        
        $membres = [
            ['prenom' => 'Jean', 'nom' => 'Dupont', 'statut' => 'actif', 'date_naissance' => '2010-01-15'],
            ['prenom' => 'Marie', 'nom' => 'Tremblay', 'statut' => 'actif', 'date_naissance' => '2012-03-22'],
            ['prenom' => 'Pierre', 'nom' => 'Gagnon', 'statut' => 'actif', 'date_naissance' => '2011-07-08'],
            ['prenom' => 'Sophie', 'nom' => 'Roy', 'statut' => 'actif', 'date_naissance' => '2013-09-14'],
            ['prenom' => 'Luc', 'nom' => 'Bergeron', 'statut' => 'actif', 'date_naissance' => '2009-11-30'],
        ];
        
        foreach ($membres as $membre) {
            \DB::table('membres')->insert([
                'prenom' => $membre['prenom'],
                'nom' => $membre['nom'],
                'statut' => $membre['statut'],
                'date_naissance' => $membre['date_naissance'],
                'date_inscription' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        echo "\n✅ 5 membres de test créés\n";
    }
} catch (Exception $e) {
    echo "⚠️ " . $e->getMessage() . "\n";
}

// Créer quelques cours de test
try {
    if (\Schema::hasTable('cours')) {
        \DB::table('cours')->truncate();
        
        $cours = [
            [
                'nom' => 'Karaté débutant',
                'niveau' => 'debutant',
                'jour_semaine' => 'lundi',
                'heure_debut' => '18:00:00',
                'heure_fin' => '19:00:00',
                'actif' => true,
                'instructeur_id' => $user->id,
                'places_max' => 20,
                'age_min' => 6,
                'age_max' => 12,
                'tarif_mensuel' => 60,
                'date_debut' => now(),
            ],
            [
                'nom' => 'Karaté intermédiaire',
                'niveau' => 'intermediaire',
                'jour_semaine' => 'mercredi',
                'heure_debut' => '19:00:00',
                'heure_fin' => '20:30:00',
                'actif' => true,
                'instructeur_id' => $user->id,
                'places_max' => 15,
                'age_min' => 10,
                'age_max' => 16,
                'tarif_mensuel' => 75,
                'date_debut' => now(),
            ],
            [
                'nom' => 'Karaté avancé',
                'niveau' => 'avance',
                'jour_semaine' => 'vendredi',
                'heure_debut' => '20:00:00',
                'heure_fin' => '21:30:00',
                'actif' => true,
                'instructeur_id' => $user->id,
                'places_max' => 12,
                'age_min' => 14,
                'age_max' => 99,
                'tarif_mensuel' => 90,
                'date_debut' => now(),
            ],
        ];
        
        foreach ($cours as $c) {
            \DB::table('cours')->insert(array_merge($c, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
        echo "✅ 3 cours de test créés\n";
    }
} catch (Exception $e) {
    echo "⚠️ " . $e->getMessage() . "\n";
}

// Nettoyer les caches
\Artisan::call('optimize:clear');
echo "\n✅ Cache vidé\n";

echo "\n=== CONNEXION ===\n";
echo "URL: http://localhost:8000/login\n";
echo "Email: {$adminEmail}\n";
echo "Mot de passe: {$adminPassword}\n";

echo "\n=== FIN ===\n";
