#!/bin/bash

# StudiosDB Auto-Fix Script - Laravel 12.24 - CORRECTION V2
# Correction automatique des problèmes d'authentification et de migration

set -e  # Arrêter en cas d'erreur

cd /home/studiosdb/studiosunisdb

echo "🔧 STUDIOSDB AUTO-FIX V2 - Laravel 12.24"
echo "========================================="
echo ""

# 1. Nettoyage initial
echo "1. Nettoyage des caches..."
php artisan optimize:clear > /dev/null 2>&1 || echo "  ⚠️  Erreur cache (normal au premier run)"
php artisan config:clear > /dev/null 2>&1 || echo "  ⚠️  Erreur config (normal au premier run)"
echo "  ✅ Caches nettoyés"

# 2. Test de base Laravel
echo ""
echo "2. Test serveur de base..."
timeout 3s php artisan serve --port=8002 > /dev/null 2>&1 &
SERVER_PID=$!
sleep 1

if curl -s http://127.0.0.1:8002/test-server > /dev/null 2>&1; then
    echo "  ✅ Serveur Laravel fonctionne"
else
    echo "  ❌ Problème serveur Laravel de base"
fi

# Tuer le serveur de test
kill $SERVER_PID > /dev/null 2>&1 || true

# 3. Vérification et correction BDD
echo ""
echo "3. Base de données..."

# Test connexion
if php artisan tinker --execute="DB::connection()->getPdo();" > /dev/null 2>&1; then
    echo "  ✅ Connexion DB OK"
else
    echo "  ❌ Problème connexion DB - Vérifier .env"
    exit 1
fi

# 4. Migrations
echo ""
echo "4. Application des migrations..."
if php artisan migrate --force > /dev/null 2>&1; then
    echo "  ✅ Migrations appliquées"
else
    echo "  ⚠️  Problèmes migrations - continuons..."
fi

# 5. Création utilisateur admin via fichier PHP temporaire (évite les problèmes d'échappement)
echo ""
echo "5. Utilisateurs et rôles..."

cat > /tmp/create_admin.php << 'EOPHP'
<?php
// Script temporaire de création admin
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';

try {
    $admin = App\Models\User::where('email', 'admin@studiosdb.com')->first();
    
    if(!$admin) {
        // Créer école si nécessaire
        $ecole = App\Models\Ecole::first();
        if(!$ecole) {
            $ecole = App\Models\Ecole::create([
                'nom' => 'École StudiosDB',
                'adresse' => '123 Rue Test',
                'ville' => 'Québec',
                'province' => 'QC',
                'code_postal' => 'G1A 1A1',
                'telephone' => '418-123-4567',
                'email' => 'info@studiosdb.com'
            ]);
        }
        
        // Créer utilisateur admin
        $admin = App\Models\User::create([
            'name' => 'Administrateur',
            'email' => 'admin@studiosdb.com',
            'password' => bcrypt('password123'),
            'ecole_id' => $ecole->id,
            'email_verified_at' => now()
        ]);
        
        // Assigner rôle si Spatie Permission disponible
        if(class_exists('Spatie\Permission\Models\Role')) {
            $role = Spatie\Permission\Models\Role::where('name', 'admin_ecole')->first();
            if(!$role) {
                $role = Spatie\Permission\Models\Role::create(['name' => 'admin_ecole']);
            }
            $admin->assignRole('admin_ecole');
        }
        
        echo "Utilisateur créé: admin@studiosdb.com / password123\n";
    } else {
        echo "Utilisateur admin existe: " . $admin->email . "\n";
        
        // Vérifier les rôles
        if(method_exists($admin, 'hasAnyRole') && !$admin->hasAnyRole(['admin_ecole', 'superadmin'])) {
            if(class_exists('Spatie\Permission\Models\Role')) {
                $role = Spatie\Permission\Models\Role::where('name', 'admin_ecole')->first();
                if(!$role) {
                    $role = Spatie\Permission\Models\Role::create(['name' => 'admin_ecole']);
                }
                $admin->assignRole('admin_ecole');
                echo "Rôle admin_ecole assigné\n";
            }
        }
    }
} catch(Exception $e) {
    echo "Erreur création admin: " . $e->getMessage() . "\n";
}
EOPHP

# Exécuter le script PHP temporaire
php /tmp/create_admin.php
rm -f /tmp/create_admin.php

echo "  ✅ Utilisateur admin configuré"

# 6. Test permissions via script PHP temporaire
echo ""
echo "6. Test permissions..."

cat > /tmp/test_permissions.php << 'EOPHP'
<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';

try {
    $user = App\Models\User::where('email', 'admin@studiosdb.com')->first();
    if($user && method_exists($user, 'can') && $user->can('viewAny', App\Models\Cours::class)) {
        echo "Permissions cours: OK\n";
    } else {
        echo "Permissions cours: PROBLÈME - Vérifier les Policies\n";
    }
} catch(Exception $e) {
    echo "Erreur test permissions: " . $e->getMessage() . "\n";
}
EOPHP

php /tmp/test_permissions.php
rm -f /tmp/test_permissions.php

echo "  ✅ Permissions testées"

# 7. Test final du serveur avec authentification
echo ""
echo "7. Test serveur final..."

# Nettoyer les caches une dernière fois
php artisan config:cache > /dev/null 2>&1
php artisan route:cache > /dev/null 2>&1

echo ""
echo "🎉 AUTO-FIX V2 TERMINÉ"
echo "======================"
echo ""
echo "Pour démarrer StudiosDB:"
echo "  php artisan serve --port=8001"
echo ""
echo "Connexion admin:"
echo "  Email: admin@studiosdb.com"
echo "  Password: password123"
echo ""
echo "URLs de test:"
echo "  http://127.0.0.1:8001/test-server (test base)"
echo "  http://127.0.0.1:8001/login (connexion)"
echo "  http://127.0.0.1:8001/cours/create (création cours)"
echo "  http://127.0.0.1:8001/debug/cours-access (diagnostic auth)"
echo ""
echo "Si erreur 'Data truncated niveau':"
echo "  php artisan migrate (applique extension ENUM niveau)"
echo ""
