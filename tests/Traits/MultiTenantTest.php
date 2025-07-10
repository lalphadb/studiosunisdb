<?php

namespace Tests\Traits;

use App\Models\{User, Ecole};
use Spatie\Permission\Models\Role;

trait MultiTenantTest
{
    protected Ecole $ecole1;
    protected Ecole $ecole2;
    protected User $superadmin;
    protected User $admin1;
    protected User $admin2;
    protected User $membre1;
    protected User $membre2;
    
    /**
     * Configuration multi-tenant pour les tests
     */
    protected function setupMultiTenant(): void
    {
        // Créer les rôles si nécessaire
        $this->ensureRolesExist();
        
        // Créer 2 écoles de test
        $this->ecole1 = Ecole::factory()->create([
            'nom' => 'École Test 1',
            'code' => 'TEST1',
            'active' => true
        ]);
        
        $this->ecole2 = Ecole::factory()->create([
            'nom' => 'École Test 2',
            'code' => 'TEST2',
            'active' => true
        ]);
        
        // Créer les utilisateurs
        $this->superadmin = User::factory()->create(['name' => 'SuperAdmin Test']);
        $this->superadmin->assignRole('superadmin');
        
        $this->admin1 = User::factory()->create([
            'name' => 'Admin École 1',
            'ecole_id' => $this->ecole1->id
        ]);
        $this->admin1->assignRole('admin_ecole');
        
        $this->admin2 = User::factory()->create([
            'name' => 'Admin École 2',
            'ecole_id' => $this->ecole2->id
        ]);
        $this->admin2->assignRole('admin_ecole');
        
        $this->membre1 = User::factory()->create([
            'name' => 'Membre École 1',
            'ecole_id' => $this->ecole1->id
        ]);
        $this->membre1->assignRole('membre');
        
        $this->membre2 = User::factory()->create([
            'name' => 'Membre École 2',
            'ecole_id' => $this->ecole2->id
        ]);
        $this->membre2->assignRole('membre');
    }
    
    /**
     * S'assurer que les rôles existent
     */
    private function ensureRolesExist(): void
    {
        $roles = ['superadmin', 'admin_ecole', 'membre'];
        
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }
    }
    
    /**
     * Assert qu'un utilisateur ne peut voir que les données de son école
     */
    protected function assertCanOnlySeeOwnEcoleData($response, User $user): void
    {
        if ($user->hasRole('superadmin')) {
            // SuperAdmin voit tout
            $response->assertOk();
        } else {
            // Autres ne voient que leur école
            $content = $response->getContent();
            $this->assertStringContainsString($user->ecole->nom, $content);
            
            // Ne doit pas voir l'autre école
            $otherEcole = $user->ecole_id === $this->ecole1->id ? $this->ecole2 : $this->ecole1;
            $this->assertStringNotContainsString($otherEcole->nom, $content);
        }
    }
    
    /**
     * Nettoyer après les tests
     */
    protected function tearDownMultiTenant(): void
    {
        // Nettoyer dans l'ordre inverse des dépendances
        User::whereIn('id', [
            $this->membre1->id ?? null,
            $this->membre2->id ?? null,
            $this->admin1->id ?? null,
            $this->admin2->id ?? null,
            $this->superadmin->id ?? null,
        ])->delete();
        
        Ecole::whereIn('id', [
            $this->ecole1->id ?? null,
            $this->ecole2->id ?? null,
        ])->delete();
    }
}
