<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CleanRolesSeeder extends Seeder
{
    public function run(): void
    {
        // Supprimer les rôles avec tirets
        Role::where('name', 'admin-ecole')->delete();
        Role::where('name', 'super-admin')->delete();
        
        // Supprimer le rôle "membre" et le remplacer par "user"
        $membreRole = Role::where('name', 'membre')->first();
        if ($membreRole) {
            // Transférer les utilisateurs de "membre" vers "user"
            $userRole = Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);
            
            foreach ($membreRole->users as $user) {
                $user->syncRoles(['user']);
            }
            
            $membreRole->delete();
        }

        // Créer/vérifier les rôles standards
        $roles = [
            'superadmin' => 'Super Administrateur',
            'admin_ecole' => 'Administrateur École', 
            'admin' => 'Administrateur',
            'instructeur' => 'Instructeur',
            'user' => 'Utilisateur',
        ];

        foreach ($roles as $name => $description) {
            Role::firstOrCreate([
                'name' => $name,
                'guard_name' => 'web'
            ]);
        }

        $this->command->info('Rôles nettoyés et standardisés');
    }
}
