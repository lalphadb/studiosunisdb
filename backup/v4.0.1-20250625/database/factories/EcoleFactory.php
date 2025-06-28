<?php

namespace Database\Factories;

use App\Models\Ecole;
use Illuminate\Database\Eloquent\Factories\Factory;

class EcoleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Ecole::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nom' => $this->faker->company . ' Karaté',
            'code' => strtoupper($this->faker->unique()->lexify('???')),
            'adresse' => $this->faker->streetAddress,
            'ville' => $this->faker->city,
            'province' => 'QC',
            'code_postal' => $this->faker->postcode,
            'telephone' => $this->faker->phoneNumber,
            'email' => $this->faker->unique()->safeEmail,
            'active' => true,
        ];
    }
}
