#!/bin/bash

# ============================================================================
# SCRIPT POST-DÉPLOIEMENT - RÉPARATION AUTOMATIQUE
# StudiosUnisDB v3.8.3 - ANTI-GALÈRE
# ============================================================================

echo "🔧 POST-DÉPLOIEMENT - RÉPARATION AUTOMATIQUE"
echo "============================================="

# Configuration sécurisée
set -e  # Arrêter en cas d'erreur

# Couleurs
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m'

success() { echo -e "${GREEN}✅ $1${NC}"; }
error() { echo -e "${RED}❌ $1${NC}"; }
warning() { echo -e "${YELLOW}⚠️  $1${NC}"; }

echo "1. RÉPARATION MIGRATIONS"
echo "------------------------"
php artisan migrate --force
success "Migrations appliquées"

echo ""
echo "2. RÉPARATION PERMISSIONS COMPLÈTE"
echo "----------------------------------"

# Via Tinker pour réparation complète
php artisan tinker << 'TINKER_END'

echo "🔧 Début réparation permissions...\n";

// ============================================================================
// ÉTAPE 1: CRÉER TOUS LES RÔLES
// ============================================================================

$roles = ['superadmin', 'admin', 'instructeur', 'membre'];
foreach($roles as $roleName) {
    $role = \Spatie\Permission\Models\Role::firstOrCreate(['name' => $roleName]);
    echo ($role->wasRecentlyCreated ? "CRÉÉ" : "EXISTE") . ": Rôle $roleName\n";
}

// ============================================================================
// ÉTAPE 2: CRÉER TOUTES LES PERMISSIONS
// ============================================================================

$permissions = [
    // Générales
    'view-dashboard', 'access-admin', 'manage-all',
    
    // Écoles
    'view-ecoles', 'create-ecole', 'edit-ecole', 'delete-ecole', 'manage-ecoles',
    
    // Membres
    'view-membres', 'create-membre', 'edit-membre', 'delete-membre', 'manage-membres',
    'approve-membre', 'suspend-membre', 'export-membres',
    
    // Cours
    'view-cours', 'create-cours', 'edit-cours', 'delete-cours', 'manage-cours',
    'assign-instructeur', 'manage-horaires',
    
    // Présences
    'view-presences', 'create-presence', 'edit-presence', 'delete-presence',
    'take-presences', 'manage-presences', 'export-presences', 'view-statistics',
    
    // Ceintures (CRITIQUE)
    'view-ceintures', 'create-ceinture', 'edit-ceinture', 'delete-ceinture',
    'manage-ceintures', 'assign-ceintures', 'evaluate-ceintures', 'view-progressions',
    
    // Séminaires
    'view-seminaires', 'create-seminaire', 'edit-seminaire', 'delete-seminaire',
    'manage-seminaires', 'inscribe-seminaires',
    
    // Finances & Rapports
    'manage-finances', 'view-paiements', 'create-paiement', 'generate-factures',
    'view-reports', 'generate-reports', 'view-analytics', 'export-data'
];

foreach($permissions as $permName) {
    $permission = \Spatie\Permission\Models\Permission::firstOrCreate(['name' => $permName]);
    echo ($permission->wasRecentlyCreated ? "CRÉÉ" : "EXISTE") . ": Permission $permName\n";
}

echo "✅ " . count($permissions) . " permissions vérifiées\n";

// ============================================================================
// ÉTAPE 3: ASSIGNER PERMISSIONS AUX RÔLES
// ============================================================================

// SUPERADMIN = TOUTES LES PERMISSIONS
$superadminRole = \Spatie\Permission\Models\Role::where('name', 'superadmin')->first();
$allPermissions = \Spatie\Permission\Models\Permission::all();
$superadminRole->syncPermissions($allPermissions);
echo "✅ SuperAdmin: TOUTES les permissions (" . $allPermissions->count() . ")\n";

// ADMIN = Permissions de gestion d'école
$adminRole = \Spatie\Permission\Models\Role::where('name', 'admin')->first();
$adminPermissions = [
    'view-dashboard', 'access-admin',
    'view-ecoles',
    'view-membres', 'create-membre', 'edit-membre', 'delete-membre', 'export-membres',
    'view-cours', 'create-cours', 'edit-cours', 'delete-cours',
    'view-presences', 'take-presences', 'edit-presence', 'export-presences',
    'view-ceintures', 'manage-ceintures', 'assign-ceintures', 'evaluate-ceintures',
    'view-seminaires', 'create-seminaire', 'manage-seminaires'
];
$adminRole->syncPermissions($adminPermissions);
echo "✅ Admin: " . count($adminPermissions) . " permissions\n";

// INSTRUCTEUR = Permissions limitées
$instructeurRole = \Spatie\Permission\Models\Role::where('name', 'instructeur')->first();
$instructeurPermissions = [
    'view-dashboard', 'access-admin',
    'view-membres', 'view-cours',
    'view-presences', 'take-presences',
    'view-ceintures'
];
$instructeurRole->syncPermissions($instructeurPermissions);
echo "✅ Instructeur: " . count($instructeurPermissions) . " permissions\n";

// ============================================================================
// ÉTAPE 4: RÉPARER/CRÉER UTILISATEURS CRITIQUES
// ============================================================================

// SUPERADMIN
$superadmin = \App\Models\User::updateOrCreate(
    ['email' => 'lalpha@4lb.ca'],
    [
        'name' => 'Super Admin LAlpha',
        'password' => \Illuminate\Support\Facades\Hash::make('QwerTfc443-studios!'),
        'ecole_id' => null,
        'email_verified_at' => now(),
        'statut' => 'actif'
    ]
);
$superadmin->syncRoles(['superadmin']);
echo "✅ SuperAdmin lalpha@4lb.ca réparé\n";

// ADMIN ST-ÉMILE
// D'abord, créer/vérifier l'école St-Émile
$stEmileEcole = \App\Models\Ecole::firstOrCreate(
    ['nom' => 'Studios Unis St-Émile'],
    [
        'adresse' => '123 Rue Principale',
        'ville' => 'Saint-Émile',
        'province' => 'QC',
        'code_postal' => 'G3E 1A1',
        'telephone' => '418-555-0199',
        'email' => 'st.emile@studiosunisqc.com',
        'directeur' => 'Louis Administrateur',
        'capacite_max' => 50,
        'statut' => 'actif'
    ]
);

$adminStEmile = \App\Models\User::updateOrCreate(
    ['email' => 'louis@4lb.ca'],
    [
        'name' => 'Louis Admin St-Émile',
        'password' => \Illuminate\Support\Facades\Hash::make('B0bby2111'),
        'ecole_id' => $stEmileEcole->id,
        'email_verified_at' => now(),
        'statut' => 'actif'
    ]
);
$adminStEmile->syncRoles(['admin']);
echo "✅ Admin St-Émile louis@4lb.ca réparé (École ID: {$stEmileEcole->id})\n";

// ============================================================================
// ÉTAPE 5: VÉRIFICATIONS FINALES
// ============================================================================

echo "\n=== VÉRIFICATIONS FINALES ===\n";
echo "SuperAdmin permissions: " . $superadmin->getAllPermissions()->count() . "\n";
echo "Admin permissions: " . $adminStEmile->getAllPermissions()->count() . "\n";
echo "SuperAdmin peut voir ceintures: " . ($superadmin->can('view-ceintures') ? 'OUI' : 'NON') . "\n";
echo "Admin peut voir ceintures: " . ($adminStEmile->can('view-ceintures') ? 'OUI' : 'NON') . "\n";

echo "\n🎉 RÉPARATION PERMISSIONS TERMINÉE\n";

exit;
TINKER_END

success "Permissions réparées via Tinker"

echo ""
echo "3. NETTOYAGE CACHES"
echo "-------------------"
php artisan permission:cache-reset
php artisan optimize:clear
php artisan config:clear
success "Caches nettoyés"

echo ""
echo "4. VÉRIFICATION FINALE"
echo "----------------------"
if [ -f "./scripts/verify-deployment.sh" ]; then
    ./scripts/verify-deployment.sh
else
    warning "Script de vérification non trouvé"
fi

echo ""
echo "🎉 POST-DÉPLOIEMENT TERMINÉ"
echo "==========================="
echo "✅ Permissions: RÉPARÉES"
echo "✅ Utilisateurs: CONFIGURÉS"
echo "✅ Écoles: VÉRIFIÉES"
echo ""
echo "🔑 Comptes de test:"
echo "• SuperAdmin: lalpha@4lb.ca / QwerTfc443-studios!"
echo "• Admin École: louis@4lb.ca / B0bby2111"
echo ""
echo "📝 En cas de problème, relancez: ./scripts/post-deploy-fix.sh"
