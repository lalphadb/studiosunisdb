<?php

namespace Database\Factories;

use App\Models\Cours;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cours>
 */
class CoursFactory extends Factory
{
    protected $model = Cours::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $jour = collect(array_keys(Cours::JOURS_SEMAINE))->random();
        $nom = $this->faker->unique()->words(2, true);

        return [
            'ecole_id' => 1,
            'instructeur_id' => null,
            'nom' => ucfirst($nom),
            'slug' => Str::slug($nom.'-'.uniqid()),
            'niveau' => 'tous',
            'age_min' => 5,
            'age_max' => 12,
            'places_max' => 15,
            'places_reservees' => 0,
            'jour_semaine' => $jour,
            'heure_debut' => '17:00:00',
            'heure_fin' => '18:00:00',
            'date_debut' => now()->toDateString(),
            'date_fin' => null,
            'session' => 'automne',
            'type_tarif' => 'mensuel',
            'montant' => 40,
            'devise' => 'CAD',
            'couleur' => null,
            'actif' => true,
            'inscriptions_ouvertes' => true,
            'options' => null,
            'description' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
