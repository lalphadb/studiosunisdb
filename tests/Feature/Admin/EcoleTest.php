<?php

namespace Tests\Feature\Admin;

use App\Models\Ecole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EcoleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    public function test_can_view_ecoles_index(): void
    {
        $response = $this->get(route('admin.ecoles.index'));
        
        $response->assertStatus(200);
        $response->assertViewIs('admin.ecoles.index');
    }

    public function test_can_create_ecole(): void
    {
        $ecoleData = [
            'nom' => 'École Test',
            'code_ecole' => 'TEST',
            'adresse' => '123 Rue Test',
            'ville' => 'Montréal',
            'province' => 'Québec',
            'code_postal' => 'H1H 1H1',
            'statut' => 'actif',
        ];

        $response = $this->post(route('admin.ecoles.store'), $ecoleData);
        
        $response->assertRedirect(route('admin.ecoles.index'));
        $this->assertDatabaseHas('ecoles', $ecoleData);
    }

    public function test_can_update_ecole(): void
    {
        $ecole = Ecole::factory()->create();
        
        $updateData = [
            'nom' => 'École Modifiée',
            'code_ecole' => $ecole->code_ecole,
            'adresse' => $ecole->adresse,
            'ville' => $ecole->ville,
            'province' => $ecole->province,
            'code_postal' => $ecole->code_postal,
            'statut' => 'inactif',
        ];

        $response = $this->put(route('admin.ecoles.update', $ecole), $updateData);
        
        $response->assertRedirect(route('admin.ecoles.index'));
        $this->assertDatabaseHas('ecoles', $updateData);
    }

    public function test_can_delete_ecole(): void
    {
        $ecole = Ecole::factory()->create();

        $response = $this->delete(route('admin.ecoles.destroy', $ecole));
        
        $response->assertRedirect(route('admin.ecoles.index'));
        $this->assertSoftDeleted('ecoles', ['id' => $ecole->id]);
    }
}
