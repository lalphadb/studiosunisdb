<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Ecole;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class FixLouisUserSeeder extends Seeder
{
    public function run()
    {
        $this->command->info("=== CRÉATION/CORRECTION UTILISATEUR LOUIS ===");

        // Utiliser la première école disponible
        $ecole = Ecole::first();
        if (!$ecole) {
            $this->command->error("Aucune école trouvée!");
            return;
        }

        // Vérifier le rôle admin_ecole
        $role = Role::firstOrCreate(['name' => 'admin_ecole']);

        // Supprimer et recréer l'utilisateur louis@4lb.ca
        User::where('email', 'louis@4lb.ca')->delete();

        $user = User::create([
            'name' => 'Louis Admin École',
            'email' => 'louis@4lb.ca',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
            'ecole_id' => $ecole->id
        ]);

        $user->assignRole('admin_ecole');

        $this->command->info("✅ Utilisateur louis@4lb.ca créé avec succès");
        $this->command->line("   Nom: {$user->name}");
        $this->command->line("   Email: {$user->email}");
        $this->command->line("   Mot de passe: password123");
        $this->command->line("   École: {$ecole->nom} (ID: {$ecole->id})");
        $this->command->line("   Rôle: admin_ecole");
    }
}
