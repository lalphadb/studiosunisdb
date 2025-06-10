<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CorrectPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Récupérer le rôle admin
        $adminRole = Role::where('name', 'admin')->first();
        
        if ($adminRole) {
            // Permissions CORRECTES pour Admin École (SANS create-ecole, delete-ecole)
            $adminPermissions = [
                'view-dashboard', 'access-admin',
                'view-ecoles', 'edit-ecole', // Peut voir et modifier SON école uniquement
                'manage-membres', 'create-membre', 'edit-membre', 'delete-membre', 'view-membres',
                'manage-cours', 'create-cours', 'edit-cours', 'delete-cours', 'view-cours',
                'manage-presences', 'take-presences', 'edit-presences', 'view-presences',
                'manage-ceintures', 'evaluate-ceintures', 'view-progressions',
                'manage-finances', 'view-paiements', 'create-paiement', 'edit-paiement',
                'view-reports', 'generate-reports', 'view-analytics', 'export-data'
            ];
            
            $adminRole->syncPermissions($adminPermissions);
            echo "✅ Permissions Admin École corrigées !" . PHP_EOL;
        }
        
        echo "📝 Admin École PEUT:" . PHP_EOL;
        echo "  - Voir/modifier SON école" . PHP_EOL;
        echo "  - Gérer membres de son école" . PHP_EOL;
        echo "  - Gérer cours de son école" . PHP_EOL;
        echo "  - Prendre présences" . PHP_EOL;
        echo "" . PHP_EOL;
        echo "❌ Admin École NE PEUT PAS:" . PHP_EOL;
        echo "  - Créer nouvelles écoles" . PHP_EOL;
        echo "  - Supprimer écoles" . PHP_EOL;
        echo "  - Voir autres écoles" . PHP_EOL;
    }
}
