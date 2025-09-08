<?php

namespace Database\Seeders;

use App\Models\Membre;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MembresSeeder extends Seeder
{
    /**
     * Membres de test pour StudiosDB
     */
    public function run(): void
    {
        // Créer des utilisateurs et membres de test
        $membres = [
            [
                'user' => [
                    'name' => 'Alice Dupont',
                    'email' => 'alice.dupont@test.com',
                    'password' => Hash::make('password'),
                    'active' => true,
                ],
                'membre' => [
                    'prenom' => 'Alice',
                    'nom' => 'Dupont',
                    'email' => 'alice.dupont@test.com',
                    'telephone' => '0612345678',
                    'date_naissance' => '2010-05-15',
                    'sexe' => 'F',
                    'adresse' => '123 Rue de la Paix, Paris',
                    'ville' => 'Paris',
                    'code_postal' => '75001',
                    'province' => 'Île-de-France',
                    'contact_urgence_nom' => 'Marie Dupont',
                    'contact_urgence_telephone' => '0687654321',
                    'contact_urgence_relation' => 'Mère',
                    'statut' => 'actif',
                    'ceinture_actuelle_id' => 3, // Orange
                    'date_inscription' => '2024-01-15',
                    'consentement_photos' => true,
                    'consentement_communications' => true,
                    'date_consentement' => now(),
                ],
            ],
            [
                'user' => [
                    'name' => 'Bob Martin',
                    'email' => 'bob.martin@test.com',
                    'password' => Hash::make('password'),
                    'active' => true,
                ],
                'membre' => [
                    'prenom' => 'Bob',
                    'nom' => 'Martin',
                    'email' => 'bob.martin@test.com',
                    'telephone' => '0698765432',
                    'date_naissance' => '2008-08-22',
                    'sexe' => 'M',
                    'adresse' => '456 Avenue des Sports, Lyon',
                    'ville' => 'Lyon',
                    'code_postal' => '69000',
                    'province' => 'Rhône-Alpes',
                    'contact_urgence_nom' => 'Pierre Martin',
                    'contact_urgence_telephone' => '0611223344',
                    'contact_urgence_relation' => 'Père',
                    'statut' => 'actif',
                    'ceinture_actuelle_id' => 5, // Bleue
                    'date_inscription' => '2023-09-10',
                    'consentement_photos' => true,
                    'consentement_communications' => true,
                    'date_consentement' => now(),
                ],
            ],
            [
                'user' => [
                    'name' => 'Claire Bernard',
                    'email' => 'claire.bernard@test.com',
                    'password' => Hash::make('password'),
                    'active' => true,
                ],
                'membre' => [
                    'prenom' => 'Claire',
                    'nom' => 'Bernard',
                    'email' => 'claire.bernard@test.com',
                    'telephone' => '0655566677',
                    'date_naissance' => '2006-12-03',
                    'sexe' => 'F',
                    'adresse' => '789 Boulevard Karate, Marseille',
                    'ville' => 'Marseille',
                    'code_postal' => '13000',
                    'province' => 'Provence-Alpes-Côte d\'Azur',
                    'contact_urgence_nom' => 'Jean Bernard',
                    'contact_urgence_telephone' => '0644555666',
                    'contact_urgence_relation' => 'Père',
                    'statut' => 'actif',
                    'ceinture_actuelle_id' => 7, // Verte
                    'date_inscription' => '2023-03-20',
                    'consentement_photos' => true,
                    'consentement_communications' => true,
                    'date_consentement' => now(),
                ],
            ],
            [
                'user' => [
                    'name' => 'David Petit',
                    'email' => 'david.petit@test.com',
                    'password' => Hash::make('password'),
                    'active' => true,
                ],
                'membre' => [
                    'prenom' => 'David',
                    'nom' => 'Petit',
                    'email' => 'david.petit@test.com',
                    'telephone' => '0644433322',
                    'date_naissance' => '2004-07-18',
                    'sexe' => 'M',
                    'adresse' => '321 Rue des Arts Martiaux, Toulouse',
                    'ville' => 'Toulouse',
                    'code_postal' => '31000',
                    'province' => 'Midi-Pyrénées',
                    'contact_urgence_nom' => 'Sophie Petit',
                    'contact_urgence_telephone' => '0633344455',
                    'contact_urgence_relation' => 'Mère',
                    'statut' => 'actif',
                    'ceinture_actuelle_id' => 9, // Marron 1 rayée
                    'date_inscription' => '2022-11-05',
                    'consentement_photos' => true,
                    'consentement_communications' => true,
                    'date_consentement' => now(),
                ],
            ],
            [
                'user' => [
                    'name' => 'Emma Moreau',
                    'email' => 'emma.moreau@test.com',
                    'password' => Hash::make('password'),
                    'active' => true,
                ],
                'membre' => [
                    'prenom' => 'Emma',
                    'nom' => 'Moreau',
                    'email' => 'emma.moreau@test.com',
                    'telephone' => '0622233344',
                    'date_naissance' => '2002-03-30',
                    'sexe' => 'F',
                    'adresse' => '654 Place du Dojo, Nice',
                    'ville' => 'Nice',
                    'code_postal' => '06000',
                    'province' => 'Provence-Alpes-Côte d\'Azur',
                    'contact_urgence_nom' => 'Michel Moreau',
                    'contact_urgence_telephone' => '0611122233',
                    'contact_urgence_relation' => 'Père',
                    'statut' => 'actif',
                    'ceinture_actuelle_id' => 12, // Noire 1er Dan
                    'date_inscription' => '2021-06-12',
                    'consentement_photos' => true,
                    'consentement_communications' => true,
                    'date_consentement' => now(),
                ],
            ],
        ];

        foreach ($membres as $data) {
            // Créer l'utilisateur
            $user = User::create($data['user']);

            // Créer le membre associé
            $data['membre']['user_id'] = $user->id;
            Membre::create($data['membre']);
        }

        $this->command->info('✅ '.count($membres).' membres de test créés !');
    }
}
