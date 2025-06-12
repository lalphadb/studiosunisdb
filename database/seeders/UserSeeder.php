<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Ecole;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Créer rôles si pas existants
        $superAdmin = Role::firstOrCreate(['name' => 'superadmin']);
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $instructeur = Role::firstOrCreate(['name' => 'instructeur']);
        $membre = Role::firstOrCreate(['name' => 'membre']);

        // Utilisateur SuperAdmin
        $superAdminUser = User::firstOrCreate([
            'email' => 'louis@4lb.ca'
        ], [
            'name' => 'Louis Admin',
            'password' => bcrypt('password123'),
            'email_verified_at' => now(),
        ]);
        $superAdminUser->assignRole('superadmin');

        // Admin École Montréal
        $ecoleMontrealCentre = Ecole::where('nom', 'like', '%Montréal Centre%')->first();
        $adminMontreal = User::firstOrCreate([
            'email' => 'admin.montreal@studiosdb.com'
        ], [
            'name' => 'Admin Montréal',
            'password' => bcrypt('password123'),
            'ecole_id' => $ecoleMontrealCentre?->id,
            'email_verified_at' => now(),
        ]);
        $adminMontreal->assignRole('admin');

        // Instructeur test
        $instructeurTest = User::firstOrCreate([
            'email' => 'instructeur@studiosdb.com'
        ], [
            'name' => 'Instructeur Test',
            'password' => bcrypt('password123'),
            'ecole_id' => $ecoleMontrealCentre?->id,
            'email_verified_at' => now(),
        ]);
        $instructeurTest->assignRole('instructeur');

        echo "✅ Utilisateurs créés avec succès!\n";
        echo "SuperAdmin: louis@4lb.ca / password123\n";
        echo "Admin: admin.montreal@studiosdb.com / password123\n";
        echo "Instructeur: instructeur@studiosdb.com / password123\n";
    }
}
