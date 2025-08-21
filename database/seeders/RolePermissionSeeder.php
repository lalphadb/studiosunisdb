<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\{Role, Permission};
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $roles = ['superadmin','admin_ecole','instructeur','membre'];

        $perms = [
            // Membres
            'membres.view','membres.create','membres.edit','membres.delete','membres.export','membres.bulk','membres.changer-ceinture',
            // Cours
            'cours.view','cours.create','cours.edit','cours.delete','cours.planning',
            // Présences
            'presences.view','presences.tablette','presences.marquer','presences.rapports','presences.export',
            // Paiements
            'paiements.view','paiements.create','paiements.confirmer','paiements.rembourser','paiements.generer-factures',
            // Admin
            'admin.utilisateurs','admin.configuration','admin.logs','admin.backup','admin.statistiques',
        ];

        foreach ($perms as $p) { Permission::findOrCreate($p); }

        $map = [
            'superadmin'   => $perms,
            'admin_ecole'  => array_diff($perms, ['admin.backup']),
            'instructeur'  => ['membres.view','membres.export','membres.changer-ceinture','cours.view','presences.view','presences.tablette','presences.marquer'],
            'membre'       => [], // via policies (self only)
        ];

        foreach ($roles as $r) {
            $role = Role::findOrCreate($r);
            $role->syncPermissions($map[$r] ?? []);
        }

        if ($u = User::where('email','louis@4lb.ca')->first()) {
            $u->syncRoles(['admin_ecole']);
        }
    }
}
