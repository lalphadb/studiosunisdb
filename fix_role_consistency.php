<?php

require 'vendor/autoload.php';
use Illuminate\Database\Capsule\Manager as DB;

// Configuration base de données
$capsule = new DB;
$capsule->addConnection([
    'driver' => 'mysql',
    'host' => env('DB_HOST', '127.0.0.1'),
    'database' => env('DB_DATABASE', 'studiosdb'),
    'username' => env('DB_USERNAME'),
    'password' => env('DB_PASSWORD'),
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
]);
$capsule->setAsGlobal();
$capsule->bootEloquent();

echo "🔧 Correction de la terminologie des rôles...\n";

// Corriger dans la table roles
DB::table('roles')
    ->where('name', 'super-admin')
    ->update(['name' => 'superadmin']);

DB::table('roles')
    ->where('name', 'admin-ecole')
    ->update(['name' => 'admin_ecole']);

echo "✅ Rôles unifiés : superadmin, admin_ecole, instructeur, membre\n";
echo "🎯 Terminologie cohérente appliquée !\n";
