<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Ecole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('   → Création du SuperAdmin...');
        
        // Récupérer la première école (StudiosUnis Siège Social)
        $ecole = Ecole::where('code', 'STU-001')->first();
        
        if (!$ecole) {
            $this->command->error('   ❌ École STU-001 non trouvée!');
            return;
        }
        
        // Créer le SuperAdmin
        $superadmin = User::updateOrCreate(
            ['email' => 'superadmin@studiosdb.ca'],
            [
                'nom' => 'Super',
                'prenom' => 'Admin',
                'email' => 'superadmin@studiosdb.ca',
                'password' => Hash::make('StudiosDB2024!'),
                'ecole_id' => $ecole->id,
                'telephone' => '1-888-STUDIOS',
                'actif' => true,
                'email_verified_at' => now(),
                'code_utilisateur' => 'SA-0001',
            ]
        );
        
        // Assigner le rôle
        $superadmin->assignRole('superadmin');
        
        $this->command->info('   ✓ SuperAdmin créé: superadmin@studiosdb.ca');
    }
}
