<?php

namespace Tests\Feature\Users;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Spatie\Permission\Models\Role;

class UserSecurityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Créer rôles si pas d'autre mécanisme de seeding
        Role::firstOrCreate(['name' => 'superadmin']);
        Role::firstOrCreate(['name' => 'admin_ecole']);
        Role::firstOrCreate(['name' => 'instructeur']);
        Role::firstOrCreate(['name' => 'membre']);
    }

    private function createEcole(): int
    {
        return \DB::table('ecoles')->insertGetId([
            'nom' => 'École Test',
            'slug' => 'ecole-test',
            'ville' => 'Test City',
            'province' => 'QC',
            'est_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function test_admin_ecole_cannot_view_users_from_other_schools()
    {
        $ecole1 = $this->createEcole();
        $ecole2 = \DB::table('ecoles')->insertGetId([
            'nom' => 'École 2',
            'slug' => 'ecole-2',
            'ville' => 'Test City 2',
            'province' => 'QC',
            'est_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $admin1 = User::factory()->create(['ecole_id' => $ecole1]);
        $admin1->assignRole('admin_ecole');

        $user2 = User::factory()->create(['ecole_id' => $ecole2]);

        $this->actingAs($admin1);
        
        // Admin école 1 ne peut pas voir utilisateur école 2
        $response = $this->get(route('utilisateurs.index'));
        $response->assertOk();
        
        $users = $response->viewData('users') ?? $response->inertia('users');
        $userIds = collect($users['data'] ?? $users)->pluck('id')->toArray();
        
        $this->assertNotContains($user2->id, $userIds);
        $this->assertContains($admin1->id, $userIds);
    }

    public function test_admin_ecole_cannot_create_superadmin()
    {
        $ecole = $this->createEcole();
        $admin = User::factory()->create(['ecole_id' => $ecole]);
        $admin->assignRole('admin_ecole');

        $this->actingAs($admin);

        $response = $this->post(route('utilisateurs.store'), [
            'name' => 'Test Super',
            'email' => 'super@test.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'roles' => ['superadmin'], // Tentative création superadmin
        ]);

        $response->assertRedirect();
        
        // Vérifier que l'utilisateur n'a PAS le rôle superadmin
        $user = User::where('email', 'super@test.com')->first();
        $this->assertNotNull($user);
        $this->assertFalse($user->hasRole('superadmin'));
    }

    public function test_user_cannot_delete_himself()
    {
        $ecole = $this->createEcole();
        $admin = User::factory()->create(['ecole_id' => $ecole]);
        $admin->assignRole('admin_ecole');

        $this->actingAs($admin);

        // Tentative auto-suppression
        $response = $this->delete(route('utilisateurs.destroy', $admin));
        
        // Doit être refusée par la policy
        $response->assertForbidden();
    }

    public function test_admin_ecole_cannot_modify_superadmin()
    {
        $ecole = $this->createEcole();
        
        $superadmin = User::factory()->create(['ecole_id' => $ecole]);
        $superadmin->assignRole('superadmin');
        
        $admin = User::factory()->create(['ecole_id' => $ecole]);
        $admin->assignRole('admin_ecole');

        $this->actingAs($admin);

        // Tentative modification superadmin
        $response = $this->put(route('utilisateurs.update', $superadmin), [
            'name' => 'Modified Super',
            'email' => $superadmin->email,
        ]);

        $response->assertForbidden();
    }

    public function test_email_unique_per_school()
    {
        $ecole1 = $this->createEcole();
        $ecole2 = \DB::table('ecoles')->insertGetId([
            'nom' => 'École 2',
            'slug' => 'ecole-2',  
            'ville' => 'Test City 2',
            'province' => 'QC',
            'est_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $admin1 = User::factory()->create(['ecole_id' => $ecole1]);
        $admin1->assignRole('admin_ecole');

        // Utilisateur avec même email dans école 2
        User::factory()->create([
            'email' => 'test@example.com',
            'ecole_id' => $ecole2
        ]);

        $this->actingAs($admin1);

        // Admin école 1 peut créer utilisateur avec même email car école différente
        $response = $this->post(route('utilisateurs.store'), [
            'name' => 'Test User',
            'email' => 'test@example.com', // Même email mais école différente
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'ecole_id' => $ecole1
        ]);
    }

    public function test_superadmin_can_access_all_schools()
    {
        $ecole1 = $this->createEcole();
        $ecole2 = \DB::table('ecoles')->insertGetId([
            'nom' => 'École 2',
            'slug' => 'ecole-2',
            'ville' => 'Test City 2', 
            'province' => 'QC',
            'est_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $superadmin = User::factory()->create(['ecole_id' => $ecole1]);
        $superadmin->assignRole('superadmin');

        $user1 = User::factory()->create(['ecole_id' => $ecole1]);
        $user2 = User::factory()->create(['ecole_id' => $ecole2]);

        $this->actingAs($superadmin);

        $response = $this->get(route('utilisateurs.index'));
        $response->assertOk();

        // Superadmin voit utilisateurs des 2 écoles
        $users = $response->viewData('users') ?? $response->inertia('users');
        $userIds = collect($users['data'] ?? $users)->pluck('id')->toArray();
        
        $this->assertContains($user1->id, $userIds);
        $this->assertContains($user2->id, $userIds);
        $this->assertContains($superadmin->id, $userIds);
    }

    public function test_password_validation_requirements()
    {
        $ecole = $this->createEcole();
        $admin = User::factory()->create(['ecole_id' => $ecole]);
        $admin->assignRole('admin_ecole');

        $this->actingAs($admin);

        // Mot de passe trop faible
        $response = $this->post(route('utilisateurs.store'), [
            'name' => 'Test User',
            'email' => 'weak@test.com',
            'password' => 'simple', // Pas de majuscule, chiffre, symbole
            'password_confirmation' => 'simple',
        ]);

        $response->assertSessionHasErrors(['password']);
    }

    public function test_roles_filtered_by_permission()
    {
        $ecole = $this->createEcole();
        $admin = User::factory()->create(['ecole_id' => $ecole]);
        $admin->assignRole('admin_ecole');

        $this->actingAs($admin);

        $response = $this->get(route('utilisateurs.create'));
        $response->assertOk();

        $roles = $response->viewData('roles') ?? $response->inertia('roles');
        
        // Admin école ne doit pas voir option superadmin
        $this->assertNotContains('superadmin', $roles);
        $this->assertContains('instructeur', $roles);
        $this->assertContains('membre', $roles);
    }
}
