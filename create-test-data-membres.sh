#!/bin/bash

# =================================================================
# 🎯 SCRIPT CRÉATION DONNÉES TEST MODULE MEMBRES
# =================================================================

echo "🎯 Création de données de test pour le module Membres"
echo "=================================================="

# Ajouter quelques membres supplémentaires pour tester la pagination et les filtres
php artisan tinker --execute="
try {
    echo '🔧 Création membres de test...';
    
    // Obtenir l'école ID de l'utilisateur connecté
    \$ecoleId = \\App\\Models\\User::find(1)->ecole_id;
    
    // Obtenir quelques ceintures pour la variété
    \$ceintures = \\App\\Models\\Ceinture::orderBy('order')->take(6)->get();
    
    // Données de test variées
    \$membresTest = [
        [
            'prenom' => 'David',
            'nom' => 'Tremblay', 
            'date_naissance' => '1995-03-15',
            'telephone' => '514-123-4567',
            'statut' => 'actif',
            'sexe' => 'M'
        ],
        [
            'prenom' => 'Sophie',
            'nom' => 'Leblanc',
            'date_naissance' => '2010-08-22', 
            'telephone' => '438-987-6543',
            'statut' => 'actif',
            'sexe' => 'F'
        ],
        [
            'prenom' => 'Marc',
            'nom' => 'Gagnon',
            'date_naissance' => '1988-12-05',
            'telephone' => '450-555-1234', 
            'statut' => 'inactif',
            'sexe' => 'M'
        ],
        [
            'prenom' => 'Julie',
            'nom' => 'Roy',
            'date_naissance' => '2008-05-18',
            'telephone' => '514-777-8888',
            'statut' => 'actif', 
            'sexe' => 'F'
        ],
        [
            'prenom' => 'Alexandre',
            'nom' => 'Côté',
            'date_naissance' => '1992-09-30',
            'telephone' => '438-333-2222',
            'statut' => 'suspendu',
            'sexe' => 'M'
        ]
    ];
    
    foreach (\$membresTest as \$index => \$data) {
        // Vérifier si le membre existe déjà
        \$existant = \\App\\Models\\Membre::where('prenom', \$data['prenom'])
                                        ->where('nom', \$data['nom'])
                                        ->where('ecole_id', \$ecoleId)
                                        ->first();
        
        if (!\$existant) {
            \$membre = \\App\\Models\\Membre::create([
                'ecole_id' => \$ecoleId,
                'prenom' => \$data['prenom'],
                'nom' => \$data['nom'],
                'date_naissance' => \$data['date_naissance'],
                'telephone' => \$data['telephone'],
                'statut' => \$data['statut'],
                'sexe' => \$data['sexe'],
                'date_inscription' => now()->subDays(rand(1, 60)),
                'ceinture_actuelle_id' => \$ceintures[rand(0, \$ceintures->count()-1)]->id,
                'ville' => ['Montréal', 'Québec', 'Laval', 'Gatineau'][rand(0,3)],
                'province' => 'QC',
                'consentement_photos' => rand(0,1),
                'consentement_communications' => true,
                'date_consentement' => now()
            ]);
            
            echo '✅ Créé: ' . \$membre->nom_complet . ' (' . \$membre->statut . ')';
        } else {
            echo '⚠️ Existe déjà: ' . \$data['prenom'] . ' ' . \$data['nom'];
        }
    }
    
    // Créer quelques utilisateurs liés pour tester les emails
    \$membresAvecEmail = \\App\\Models\\Membre::whereNull('user_id')->take(2)->get();
    foreach (\$membresAvecEmail as \$membre) {
        \$email = strtolower(\$membre->prenom . '.' . \$membre->nom . '@test.com');
        
        \$user = \\App\\Models\\User::create([
            'name' => \$membre->nom_complet,
            'email' => \$email,
            'password' => \\Hash::make('password123'),
            'ecole_id' => \$ecoleId,
            'email_verified_at' => now()
        ]);
        
        \$user->assignRole('membre');
        
        \$membre->update([
            'user_id' => \$user->id,
            'email' => \$email
        ]);
        
        echo '📧 Email créé: ' . \$email . ' pour ' . \$membre->nom_complet;
    }
    
    // Statistiques finales
    \$total = \\App\\Models\\Membre::where('ecole_id', \$ecoleId)->count();
    \$actifs = \\App\\Models\\Membre::where('ecole_id', \$ecoleId)->where('statut', 'actif')->count();
    \$avecEmail = \\App\\Models\\Membre::where('ecole_id', \$ecoleId)->whereNotNull('user_id')->count();
    
    echo '';
    echo '📊 STATISTIQUES FINALES:';
    echo '   Total membres: ' . \$total;
    echo '   Membres actifs: ' . \$actifs; 
    echo '   Avec compte email: ' . \$avecEmail;
    echo '✅ Données de test créées avec succès !';
    
} catch (Exception \$e) {
    echo '❌ Erreur: ' . \$e->getMessage();
}
"

echo ""
echo "🎯 Données de test créées !"
echo "Vous pouvez maintenant tester :"
echo "  - La pagination (plus de membres)"
echo "  - Les filtres par statut" 
echo "  - La recherche par nom/email/téléphone"
echo "  - Les actions Voir/Éditer/Supprimer"
echo ""
echo "🌐 Accédez à http://localhost:8000/membres"
