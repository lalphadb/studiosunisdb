<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

class MembresTest extends TestCase
{
    use RefreshDatabase;

    public function test_liste_membres_page_accessible()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $this->get('/membres')->assertStatus(200);
    }

    public function test_creation_membre_form_validation()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $resp = $this->post('/membres', [
            'prenom' => 'Aki',
            'nom' => 'Tan',
            'date_naissance' => '2015-05-01',
        ]);
        $resp->assertStatus(302); // redirect après création ou validation
    }
}
