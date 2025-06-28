<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Ecole;
use Spatie\Permission\Models\Role;

class TestDataSeeder extends Seeder
{
    public function run(): void
    {
        // Créer les rôles s'ils n'existent pas
        $roles = ['superadmin', 'admin_ecole', 'instructeur', 'membre'];
        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }

        // Créer une école de test
        $ecole = Ecole::firstOrCreate([
            'nom' => 'Club de Karaté Saint-Émile'
        ], [
            'code' => 'CSE',
            'adresse' => '123 Rue Principale',
            'ville' => 'Montmagny',
            'province' => 'QC',
            'code_postal' => 'G0R 1Y0',
            'telephone' => '418-248-1234',
            'email' => 'contact@karatesaintemile.ca',
            'description' => 'École de karaté traditionnelle',
            'proprietaire' => 'Sensei Martin',
            'active' => true,
        ]);

        // Créer admin école (utilisateur existant louis@4lb.ca)
        $adminEcole = User::firstOrCreate([
            'email' => 'louis@4lb.ca'
        ], [
            'name' => 'Louis Lanteigne',
            'password' => bcrypt('password123'),
            'ecole_id' => $ecole->id,
            'telephone' => '418-248-5678',
            'date_naissance' => '1985-05-15',
            'sexe' => 'M',
            'adresse' => '456 Avenue du Karaté',
            'ville' => 'Montmagny',
            'code_postal' => 'G0R 1Y0',
            'active' => true,
        ]);
        $adminEcole->assignRole('admin_ecole');

        // Créer superadmin (utilisateur existant lalpha@4lb.ca)
        $superAdmin = User::firstOrCreate([
            'email' => 'lalpha@4lb.ca'
        ], [
            'name' => 'Super Admin',
            'password' => bcrypt('password123'),
            'telephone' => '418-248-9999',
            'date_naissance' => '1980-01-01',
            'sexe' => 'M',
            'active' => true,
        ]);
        $superAdmin->assignRole('superadmin');

        // Créer des membres de test
        $membres = [
            ['name' => 'Isabelle Lanteigne', 'email' => 'isabelle@example.com', 'age' => '1992-03-20'],
            ['name' => 'Marc Tremblay', 'email' => 'marc@example.com', 'age' => '1988-07-12'],
            ['name' => 'Sophie Gagnon', 'email' => 'sophie@example.com', 'age' => '1995-11-08'],
            ['name' => 'Pierre Morin', 'email' => 'pierre@example.com', 'age' => '1990-09-25'],
            ['name' => 'Julie Bouchard', 'email' => 'julie@example.com', 'age' => '1987-04-18'],
            ['name' => 'Alexandre Roy', 'email' => 'alex@example.com', 'age' => '1993-12-03'],
        ];

        foreach ($membres as $membreData) {
            $membre = User::firstOrCreate([
                'email' => $membreData['email']
            ], [
                'name' => $membreData['name'],
                'password' => bcrypt('password123'),
                'ecole_id' => $ecole->id,
                'telephone' => '418-248-' . rand(1000, 9999),
                'date_naissance' => $membreData['age'],
                'sexe' => rand(0, 1) ? 'M' : 'F',
                'adresse' => rand(100, 999) . ' Rue des Membres',
                'ville' => 'Montmagny',
                'code_postal' => 'G0R 1Y0',
                'active' => true,
            ]);
            $membre->assignRole('membre');
        }

        $this->command->info('✅ Données de test créées :');
        $this->command->info('   - 1 école : ' . $ecole->nom);
        $this->command->info('   - 1 admin école : ' . $adminEcole->name);
        $this->command->info('   - 1 superadmin : ' . $superAdmin->name);
        $this->command->info('   - ' . count($membres) . ' membres');
    }
}
