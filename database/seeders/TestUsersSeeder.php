<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Ecole;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class TestUsersSeeder extends Seeder
{
    public function run()
    {
        // Créer l'école de test avec TOUS les champs obligatoires
        $ecole = Ecole::firstOrCreate([
            'nom' => 'École Test StudiosDB'
        ], [
            'code' => 'TEST01',  // OBLIGATOIRE ET UNIQUE
            'adresse' => '123 Rue du Karaté',
            'ville' => 'Québec',
            'province' => 'QC',
            'code_postal' => 'G1A 1A1',
            'telephone' => '418-123-4567',
            'email' => 'info@studiosdb-test.ca',
            'site_web' => 'https://studiosdb-test.ca',
            'description' => 'École de test pour StudiosDB',
            'proprietaire' => 'Studios Unis',
            'active' => true
        ]);

        // Créer les rôles s'ils n'existent pas
        $superadminRole = Role::firstOrCreate(['name' => 'superadmin']);
        $adminEcoleRole = Role::firstOrCreate(['name' => 'admin_ecole']);
        $membreRole = Role::firstOrCreate(['name' => 'membre']);
        $instructeurRole = Role::firstOrCreate(['name' => 'instructeur']);

        // Super Administrateur
        $superAdmin = User::firstOrCreate([
            'email' => 'lalpha@4lb.ca'
        ], [
            'name' => 'Super Admin',
            'nom_famille' => 'StudiosDB',
            'password' => Hash::make('password123'),
            'ecole_id' => $ecole->id,
            'email_verified_at' => now()
        ]);
        $superAdmin->assignRole($superadminRole);

        // Administrateur École
        $adminEcole = User::firstOrCreate([
            'email' => 'louis@4lb.ca'
        ], [
            'name' => 'Louis',
            'nom_famille' => 'Admin École',
            'password' => Hash::make('password123'),
            'ecole_id' => $ecole->id,
            'email_verified_at' => now()
        ]);
        $adminEcole->assignRole($adminEcoleRole);

        $this->command->info('✅ Utilisateurs de test créés avec succès !');
        $this->command->info('🔑 SuperAdmin: lalpha@4lb.ca / password123');
        $this->command->info('🔑 Admin École: louis@4lb.ca / password123');
        $this->command->info('🏫 École créée: ' . $ecole->nom . ' (Code: ' . $ecole->code . ')');
    }
}
