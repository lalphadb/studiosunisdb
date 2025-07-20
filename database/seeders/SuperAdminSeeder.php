<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::updateOrCreate(
            ['email' => 'louis@4lb.ca'],
            [
                'name' => 'Louis Boudreau',
                'password' => bcrypt('password123'),
                'email_verified_at' => now(),
            ]
        );

        $role = Role::firstOrCreate(['name' => 'superadmin']);
        $user->assignRole($role);
    }
}
