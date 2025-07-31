<?php
// 🔍 Test PHP simple pour StudiosDB V5
// ===================================

echo "<h1>🔍 Test PHP StudiosDB V5</h1>";

echo "<h2>✅ PHP fonctionne!</h2>";
echo "<p>Version PHP: " . PHP_VERSION . "</p>";
echo "<p>Heure: " . date('Y-m-d H:i:s') . "</p>";

echo "<h2>📊 Extensions PHP:</h2>";
$extensions = ['pdo', 'pdo_mysql', 'mbstring', 'openssl', 'curl', 'json'];
foreach($extensions as $ext) {
    $status = extension_loaded($ext) ? '✅' : '❌';
    echo "<p>$status $ext</p>";
}

echo "<h2>🗃️ Test Base de Données:</h2>";
try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=studiosdb_central', 'studiosdb', 'StudiosDB2024!');
    echo "<p>✅ Connexion DB réussie</p>";
} catch (Exception $e) {
    echo "<p>❌ Erreur DB: " . $e->getMessage() . "</p>";
}

echo "<h2>📁 Répertoires:</h2>";
$dirs = [
    '/home/studiosdb/studiosunisdb/studiosdb_v5_pro/storage/logs',
    '/home/studiosdb/studiosunisdb/studiosdb_v5_pro/bootstrap/cache',
    '/home/studiosdb/studiosunisdb/studiosdb_v5_pro/public'
];

foreach($dirs as $dir) {
    $status = is_writable($dir) ? '✅ Writable' : (file_exists($dir) ? '❌ Non writable' : '❌ Inexistant');
    echo "<p>$status $dir</p>";
}

echo "<h2>🎯 Actions:</h2>";
echo '<p><a href="/dashboard" style="padding: 10px; background: #007cba; color: white; text-decoration: none;">Dashboard</a></p>';
echo '<p><a href="/test" style="padding: 10px; background: #28a745; color: white; text-decoration: none;">Test API</a></p>';
?>