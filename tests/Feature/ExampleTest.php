<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        // Test la redirection vers login au lieu de la page d'accueil
        $response = $this->get('/');

        $response->assertStatus(302); // Redirection
        $response->assertRedirect('/login'); // Vers login comme configuré
    }
}
