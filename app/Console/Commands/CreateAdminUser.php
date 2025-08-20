<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Command
{
    protected $signature = 'user:create-admin';
    protected $description = 'Créer un utilisateur administrateur';

    public function handle()
    {
        $user = User::create([
            'name' => 'Admin StudiosDB',
            'email' => 'admin@studiosdb.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now()
        ]);

        $this->info('✅ Utilisateur admin créé avec succès !');
        $this->info('📧 Email: admin@studiosdb.com');
        $this->info('🔑 Mot de passe: password123');
        
        return 0;
    }
}
