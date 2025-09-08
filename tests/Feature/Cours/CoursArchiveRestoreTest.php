<?php

namespace Tests\Feature\Cours;

use App\Models\Cours;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CoursArchiveRestoreTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Seed roles/permissions si le seeder existe sinon création minimale
        if (class_exists(\Database\Seeders\DatabaseSeeder::class)) {
            $this->seed();
        }
        // Création utilisateur admin_ecole minimal
        if (! User::where('email', 'admin@example.com')->exists()) {
            $admin = User::factory()->create(['email' => 'admin@example.com']);
            if (method_exists($admin, 'assignRole')) {
                $admin->assignRole('admin_ecole');
            }
        }
    }

    private function actingAsAdmin()
    {
        $user = User::where('email', 'admin@example.com')->first();
        $this->actingAs($user);

        return $user;
    }

    public function test_can_archive_cours()
    {
        $this->actingAsAdmin();
        $cours = Cours::factory()->create();

        $response = $this->delete(route('cours.destroy', $cours));
        $response->assertRedirect(route('cours.index'));
        $this->assertSoftDeleted('cours', ['id' => $cours->id]);
    }

    public function test_cannot_force_delete_with_active_members()
    {
        $this->actingAsAdmin();
        $cours = Cours::factory()->create();
        // Simuler inscription active via pivot direct (si factory Membre existe sinon skip)
        if (class_exists(\App\Models\Membre::class)) {
            $membre = \App\Models\Membre::factory()->create();
            $cours->membres()->attach($membre->id, ['date_inscription' => now(), 'statut' => 'actif']);
        }
        $response = $this->delete(route('cours.destroy', [$cours, 'force' => 1]));
        // Doit rediriger back avec erreur
        $this->assertDatabaseHas('cours', ['id' => $cours->id, 'deleted_at' => null]);
    }

    public function test_can_force_delete_without_members()
    {
        $this->actingAsAdmin();
        $cours = Cours::factory()->create();
        $response = $this->delete(route('cours.destroy', [$cours, 'force' => 1]));
        $response->assertRedirect(route('cours.index'));
        $this->assertDatabaseMissing('cours', ['id' => $cours->id]);
    }

    public function test_can_restore_archived_cours()
    {
        $this->actingAsAdmin();
        $cours = Cours::factory()->create();
        $this->delete(route('cours.destroy', $cours)); // archive
        $this->assertSoftDeleted('cours', ['id' => $cours->id]);
        $response = $this->post("/cours/{$cours->id}/restore");
        $response->assertRedirect(route('cours.index'));
        $this->assertDatabaseHas('cours', ['id' => $cours->id, 'deleted_at' => null]);
    }
}
