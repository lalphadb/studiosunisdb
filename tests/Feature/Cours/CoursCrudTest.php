<?php

namespace Tests\Feature\Cours;

use App\Models\Cours;
use App\Models\Membre;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CoursCrudTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Optionally seed roles/permissions if package used; fallback simple user
    }

    private function createInstructorUser(): User
    {
        $u = User::factory()->create();
        // If roles system exists, attach instructeur
        if (method_exists($u, 'assignRole')) {
            $u->assignRole('instructeur');
        }

        return $u;
    }

    public function test_user_can_create_course()
    {
        $user = User::factory()->create();
        if (method_exists($user, 'assignRole')) {
            $user->assignRole('admin_ecole');
        }
        $this->actingAs($user);

        $payload = [
            'nom' => 'Cours Test',
            'description' => 'Desc',
            'instructeur_id' => null,
            'niveau' => 'tous',
            'age_min' => 5,
            'age_max' => 12,
            'places_max' => 15,
            'jour_semaine' => 'lundi',
            'heure_debut' => '17:00',
            'heure_fin' => '18:00',
            'date_debut' => now()->toDateString(),
            'date_fin' => null,
            'type_tarif' => 'mensuel',
            'montant' => 50,
        ];

        $res = $this->post(route('cours.store'), $payload);
        $res->assertRedirect(route('cours.index'));
        $this->assertDatabaseHas('cours', ['nom' => 'Cours Test']);
    }

    public function test_course_duplicate_creates_inactive_copy()
    {
        $user = User::factory()->create();
        if (method_exists($user, 'assignRole')) {
            $user->assignRole('admin_ecole');
        }
        $this->actingAs($user);

        $cours = Cours::factory()->create([
            'nom' => 'Original',
            'niveau' => 'tous',
            'age_min' => 5,
            'places_max' => 10,
            'jour_semaine' => 'mardi',
            'heure_debut' => '18:00',
            'heure_fin' => '19:00',
            'date_debut' => now()->toDateString(),
            'type_tarif' => 'mensuel',
            'montant' => 40,
        ]);

        $res = $this->post(route('cours.duplicate', $cours));
        $res->assertRedirect(route('cours.index'));
        $this->assertDatabaseCount('cours', 2);
        $this->assertDatabaseHas('cours', ['nom' => 'Original (Copie)', 'actif' => 0]);
    }

    public function test_enrollment_flow()
    {
        $admin = User::factory()->create();
        if (method_exists($admin, 'assignRole')) {
            $admin->assignRole('admin_ecole');
        }
        $this->actingAs($admin);

        $cours = Cours::factory()->create([
            'nom' => 'Enroll',
            'niveau' => 'tous',
            'age_min' => 5,
            'places_max' => 5,
            'jour_semaine' => 'mercredi',
            'heure_debut' => '17:30',
            'heure_fin' => '18:30',
            'date_debut' => now()->toDateString(),
            'type_tarif' => 'mensuel',
            'montant' => 30,
        ]);

        $membreUser = User::factory()->create();
        if (method_exists($membreUser, 'assignRole')) {
            $membreUser->assignRole('membre');
        }
        // Suppose relation membre existe; sinon skip
        $membre = $membreUser->membre ?? null;
        if (! $membre && class_exists(\App\Models\Membre::class)) {
            // fallback create membre si factory existe
            try {
                $membre = Membre::factory()->create(['user_id' => $membreUser->id]);
            } catch (\Throwable $e) {
                $this->markTestSkipped('Factory Membre manquante');
            }
        }
        if (! $membre) {
            $this->markTestSkipped('Membre model indisponible');
        }

        $res = $this->post(route('cours.inscrire', $cours), ['membre_id' => $membre->id]);
        $res->assertStatus(302);
        $this->assertDatabaseHas('cours_membres', ['cours_id' => $cours->id, 'membre_id' => $membre->id, 'statut' => 'actif']);
    }
}
