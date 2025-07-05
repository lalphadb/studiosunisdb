<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Ecole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EcoleTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Créer les rôles pour les tests
        \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'superadmin']);
        \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'admin_ecole']);
        \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'membre']);
        
        // Créer permissions
        \Spatie\Permission\Models\Permission::firstOrCreate(['name' => 'ecoles.view']);
        \Spatie\Permission\Models\Permission::firstOrCreate(['name' => 'admin.dashboard']);
        
        $this->user = User::factory()->create();
        $this->user->assignRole('superadmin');
        $this->actingAs($this->user);
    }

    public function test_can_access_ecoles_index(): void
    {
        $response = $this->get(route('admin.ecoles.index'));
        $response->assertStatus(200);
    }

    public function test_can_create_ecole(): void
    {
        $ecoleData = [
            'nom' => 'École Test',
            'adresse' => '123 Rue Test',
            'telephone' => '514-123-4567',
            'email' => 'test@ecole.com',
            'ville' => 'Montréal',
            'code_postal' => 'H1H 1H1'
        ];

        $response = $this->post(route('admin.ecoles.store'), $ecoleData);
        $response->assertRedirect();
        
        $this->assertDatabaseHas('ecoles', [
            'nom' => 'École Test',
            'email' => 'test@ecole.com'
        ]);
    }
}
