<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Member;
use App\Models\Belt;
use App\Models\Family;
use Faker\Factory as Faker;

class MemberSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('fr_CA');
        
        // Créer des ceintures si elles n'existent pas
        $belts = Belt::count() > 0 ? Belt::all() : collect([
            Belt::create(['name' => 'Blanche', 'color_hex' => '#FFFFFF', 'order' => 1]),
            Belt::create(['name' => 'Jaune', 'color_hex' => '#FFD700', 'order' => 2]),
            Belt::create(['name' => 'Orange', 'color_hex' => '#FFA500', 'order' => 3]),
            Belt::create(['name' => 'Verte', 'color_hex' => '#00FF00', 'order' => 4]),
            Belt::create(['name' => 'Bleue', 'color_hex' => '#0000FF', 'order' => 5]),
            Belt::create(['name' => 'Marron', 'color_hex' => '#8B4513', 'order' => 6]),
            Belt::create(['name' => 'Noire', 'color_hex' => '#000000', 'order' => 7]),
        ]);
        
        // Créer 50 membres de test
        for ($i = 0; $i < 50; $i++) {
            $birthDate = $faker->dateTimeBetween('-50 years', '-5 years');
            $isMinor = $birthDate > now()->subYears(18);
            
            Member::create([
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'email' => $faker->unique()->safeEmail,
                'phone' => $faker->phoneNumber,
                'birth_date' => $birthDate,
                'gender' => $faker->randomElement(['M', 'F', 'Other']),
                'address' => $faker->streetAddress,
                'city' => $faker->city,
                'postal_code' => strtoupper($faker->regexify('[A-Z]\d[A-Z] \d[A-Z]\d')),
                'province' => 'QC',
                'emergency_contact_name' => $isMinor ? $faker->name : null,
                'emergency_contact_phone' => $isMinor ? $faker->phoneNumber : null,
                'emergency_contact_relationship' => $isMinor ? $faker->randomElement(['Parent', 'Tuteur', 'Grand-parent']) : null,
                'status' => $faker->randomElement(['active', 'active', 'active', 'inactive', 'suspended']),
                'current_belt_id' => $belts->random()->id,
                'registration_date' => $faker->dateTimeBetween('-3 years', 'now'),
                'last_attendance_date' => $faker->dateTimeBetween('-30 days', 'now'),
                'medical_notes' => $faker->optional(0.2)->sentence,
                'allergies' => $faker->optional(0.1)->randomElements(['Noix', 'Lactose', 'Gluten', 'Œufs'], 2),
                'medications' => $faker->optional(0.1)->randomElements(['Ventolin', 'EpiPen', 'Ritalin'], 1),
                'consent_photos' => $faker->boolean(80),
                'consent_communications' => $faker->boolean(90),
                'consent_date' => $faker->dateTimeBetween('-1 year', 'now'),
            ]);
        }
        
        $this->command->info('50 membres créés avec succès!');
    }
}
