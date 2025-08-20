<?php

use App\Models\User;
use App\Models\Membre;
use function Pest\Laravel\{actingAs,get,post,put,delete};

it('liste les membres', function () {
    $user = User::factory()->create();
    actingAs($user);
    get('/membres')->assertOk();
});

it('crée un membre', function () {
    $user = User::factory()->create();
    actingAs($user);
    post('/membres', [
        'prenom' => 'Aki',
        'nom' => 'Tan',
        'date_naissance' => '2015-05-01',
        // ... autres champs requis ...
    ])->assertRedirect();
});
