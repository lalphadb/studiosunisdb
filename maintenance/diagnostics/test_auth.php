<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Simuler une requête de login
$request = Illuminate\Http\Request::create('/login', 'POST', [
    'email' => 'louis@4lb.ca',
    'password' => 'password123'
]);

$response = $kernel->handle($request);

echo "Status: " . $response->getStatusCode() . "\n";
echo "Headers: " . json_encode($response->headers->all()) . "\n";

// Test de vérification de l'utilisateur
$user = \App\Models\User::where('email', 'louis@4lb.ca')->first();
if ($user) {
    echo "User found: " . $user->email . "\n";
    echo "User role: " . $user->role . "\n";
    
    if (\Illuminate\Support\Facades\Hash::check('password123', $user->password)) {
        echo "Password verification: SUCCESS\n";
    } else {
        echo "Password verification: FAILED\n";
    }
} else {
    echo "User NOT found\n";
}
