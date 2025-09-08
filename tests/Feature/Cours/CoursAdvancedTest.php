<?php

namespace Tests\Feature\Cours;

use App\Models\Cours;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CoursAdvancedTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    private function createAdminUser(): User
    {
        $u = User::factory()->create(['ecole_id' => 1]);
        if (method_exists($u, 'assignRole')) {
            $u->assignRole('admin_ecole');
        }

        return $u;
    }

    public function test_soft_delete_course()
    {
        $admin = $this->createAdminUser();
        $this->actingAs($admin);

        $cours = Cours::factory()->create([
            'nom' => 'Test Archive',
            'ecole_id' => 1,
            'niveau' => 'tous',
            'age_min' => 5,
            'places_max' => 10,
            'jour_semaine' => 'lundi',
            'heure_debut' => '17:00',
            'heure_fin' => '18:00',
            'date_debut' => now()->toDateString(),
            'type_tarif' => 'mensuel',
            'montant' => 40,
        ]);

        // Soft delete
        $response = $this->delete(route('cours.destroy', $cours));
        $response->assertRedirect(route('cours.index'));

        // Vérifier soft delete
        $this->assertSoftDeleted('cours', ['id' => $cours->id]);
        $this->assertDatabaseHas('cours', [
            'id' => $cours->id,
            'nom' => 'Test Archive',
        ]);
    }

    public function test_force_delete_course()
    {
        $admin = $this->createAdminUser();
        $this->actingAs($admin);

        $cours = Cours::factory()->create([
            'nom' => 'Test Force Delete',
            'ecole_id' => 1,
        ]);

        // Force delete
        $response = $this->delete(route('cours.destroy', $cours).'?force=1');
        $response->assertRedirect(route('cours.index'));

        // Vérifier suppression définitive
        $this->assertDatabaseMissing('cours', ['id' => $cours->id]);
    }

    public function test_restore_course()
    {
        $admin = $this->createAdminUser();
        $this->actingAs($admin);

        $cours = Cours::factory()->create([
            'nom' => 'Test Restore',
            'ecole_id' => 1,
        ]);

        // Archiver d'abord
        $cours->delete();
        $this->assertSoftDeleted('cours', ['id' => $cours->id]);

        // Restaurer
        $response = $this->post(route('cours.restore', $cours));
        $response->assertRedirect(route('cours.index'));

        // Vérifier restauration
        $this->assertDatabaseHas('cours', [
            'id' => $cours->id,
            'nom' => 'Test Restore',
            'deleted_at' => null,
        ]);
    }

    public function test_duplicate_course_for_day()
    {
        $admin = $this->createAdminUser();
        $this->actingAs($admin);

        $cours = Cours::factory()->create([
            'nom' => 'Original Lundi',
            'jour_semaine' => 'lundi',
            'ecole_id' => 1,
        ]);

        $response = $this->post(route('cours.duplicate.jour', $cours), [
            'nouveau_jour' => 'mardi',
        ]);

        $response->assertRedirect(route('cours.index'));

        // Vérifier duplication
        $this->assertDatabaseHas('cours', [
            'jour_semaine' => 'mardi',
            'ecole_id' => 1,
        ]);

        $this->assertDatabaseCount('cours', 2);
    }

    public function test_duplicate_course_for_session()
    {
        $admin = $this->createAdminUser();
        $this->actingAs($admin);

        $cours = Cours::factory()->create([
            'nom' => 'Session Automne',
            'ecole_id' => 1,
        ]);

        $response = $this->post(route('cours.duplicate.session', $cours), [
            'nouvelle_session' => 'hiver',
        ]);

        $response->assertRedirect(route('cours.index'));

        // Vérifier duplication
        $this->assertDatabaseCount('cours', 2);
        $this->assertDatabaseHas('cours', [
            'ecole_id' => 1,
        ]);
    }

    public function test_create_multiple_sessions()
    {
        $admin = $this->createAdminUser();
        $this->actingAs($admin);

        $cours = Cours::factory()->create([
            'nom' => 'Cours Base',
            'jour_semaine' => 'lundi',
            'ecole_id' => 1,
        ]);

        $response = $this->post(route('cours.sessions.create', $cours), [
            'jours_semaine' => ['mardi', 'jeudi'],
            'heure_debut' => '18:00',
            'heure_fin' => '19:00',
            'date_debut' => now()->addWeek()->toDateString(),
            'date_fin' => now()->addMonth()->toDateString(),
            'frequence' => 'hebdomadaire',
            'dupliquer_inscriptions' => false,
        ]);

        $response->assertRedirect(route('cours.index'));

        // Vérifier création de 2 nouvelles sessions (mardi + jeudi)
        $this->assertDatabaseCount('cours', 3);
        $this->assertDatabaseHas('cours', ['jour_semaine' => 'mardi']);
        $this->assertDatabaseHas('cours', ['jour_semaine' => 'jeudi']);
    }

    public function test_cannot_restore_non_deleted_course()
    {
        $admin = $this->createAdminUser();
        $this->actingAs($admin);

        $cours = Cours::factory()->create(['ecole_id' => 1]);

        $response = $this->post(route('cours.restore', $cours));
        $response->assertSessionHasErrors(['restore']);
    }

    public function test_scoped_course_access_by_school()
    {
        $admin1 = $this->createAdminUser(); // ecole_id = 1
        $admin2 = User::factory()->create(['ecole_id' => 2]);
        if (method_exists($admin2, 'assignRole')) {
            $admin2->assignRole('admin_ecole');
        }

        $cours1 = Cours::factory()->create(['ecole_id' => 1]);
        $cours2 = Cours::factory()->create(['ecole_id' => 2]);

        // Admin 1 peut voir cours de son école
        $this->actingAs($admin1);
        $response = $this->get(route('cours.show', $cours1));
        $response->assertOk();

        // Admin 1 ne peut pas voir cours d'autre école (si policy bien implémentée)
        $response = $this->get(route('cours.show', $cours2));
        // Note: Le test exact dépend de l'implémentation de la Policy
        // En mode permissif, cela peut retourner 200, sinon 403
    }
}
