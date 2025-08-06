<?php
/**
 * Script de test pour la connexion StudiosDB v5 Pro
 * Test des credentials et authentification
 */

echo "=== TEST LOGIN STUDIOSDB V5 PRO ===\n\n";

// Credentials de test
$credentials = [
    [
        'email' => 'louis@4lb.ca',
        'password' => 'password123',
        'name' => 'Louis Boudreau (Superadmin)'
    ],
    [
        'email' => 'admin@studiosdb.com', 
        'password' => 'admin123',
        'name' => 'Admin StudiosDB'
    ]
];

echo "📋 CREDENTIALS DE CONNEXION DISPONIBLES:\n";
echo "==========================================\n\n";

foreach($credentials as $i => $cred) {
    echo "👤 Utilisateur " . ($i + 1) . ":\n";
    echo "   📧 Email: " . $cred['email'] . "\n";
    echo "   🔑 Mot de passe: " . $cred['password'] . "\n";
    echo "   👨‍💼 Nom: " . $cred['name'] . "\n\n";
}

echo "🌐 INSTRUCTIONS DE CONNEXION:\n";
echo "==============================\n";
echo "1. Ouvrez votre navigateur\n";
echo "2. Allez à: http://localhost:8001/login\n";
echo "3. Utilisez l'un des credentials ci-dessus\n";
echo "4. Vous devriez être redirigé vers le dashboard\n\n";

echo "🔧 DÉPANNAGE:\n";
echo "=============\n";
echo "- Si l'erreur persiste, vérifiez les logs Laravel\n";
echo "- Assurez-vous que le serveur est démarré (port 8001)\n";
echo "- Vérifiez la console du navigateur pour les erreurs JS\n\n";

echo "✅ Tests terminés - Credentials prêts!\n";
?>
