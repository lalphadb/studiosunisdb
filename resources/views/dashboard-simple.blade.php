<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Test HTML Pur</title>
    <style>
        body {
            margin: 0;
            padding: 40px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Arial', sans-serif;
            color: white;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: rgba(255,255,255,0.1);
            padding: 40px;
            border-radius: 20px;
            backdrop-filter: blur(15px);
            box-shadow: 0 8px 32px rgba(0,0,0,0.3);
            text-align: center;
        }
        h1 {
            font-size: 48px;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        .info-box {
            background: rgba(255,255,255,0.2);
            padding: 20px;
            border-radius: 15px;
            margin: 20px 0;
            text-align: left;
        }
        .btn {
            display: inline-block;
            padding: 15px 30px;
            background: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 25px;
            margin: 10px;
            font-size: 16px;
            transition: all 0.3s;
        }
        .btn:hover {
            background: #45a049;
            transform: translateY(-2px);
        }
        .btn-danger {
            background: #f44336;
        }
        .btn-danger:hover {
            background: #da190b;
        }
        .btn-primary {
            background: #2196F3;
        }
        .btn-primary:hover {
            background: #0b7dda;
        }
        .success-list {
            text-align: left;
            max-width: 300px;
            margin: 0 auto;
        }
        .success-list li {
            margin: 10px 0;
            font-size: 18px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🚀 TEST HTML PUR RÉUSSI !</h1>
        
        <p style="font-size: 24px; margin-bottom: 30px;">
            Laravel fonctionne parfaitement sans Vue.js
        </p>
        
        <div class="info-box">
            <h3>👤 Informations Utilisateur</h3>
            @if($user)
                <p><strong>Nom:</strong> {{ $user->name }}</p>
                <p><strong>Email:</strong> {{ $user->email }}</p>
                <p><strong>ID:</strong> {{ $user->id }}</p>
            @else
                <p><em>Utilisateur non connecté</em></p>
            @endif
        </div>
        
        <div class="info-box">
            <h3>⏰ Informations Système</h3>
            <p><strong>Timestamp:</strong> {{ $timestamp->format('Y-m-d H:i:s') }}</p>
            <p><strong>Laravel:</strong> {{ app()->version() }}</p>
            <p><strong>PHP:</strong> {{ PHP_VERSION }}</p>
            <p><strong>Environnement:</strong> {{ config('app.env') }}</p>
        </div>
        
        <div class="info-box" style="background: rgba(76, 175, 80, 0.2);">
            <h3 style="color: #4CAF50;">✅ Composants Fonctionnels</h3>
            <ul class="success-list">
                <li>✅ Serveur Laravel</li>
                <li>✅ Base de données</li>
                <li>✅ Authentification</li>
                <li>✅ Contrôleurs PHP</li>
                <li>✅ Vues Blade</li>
                <li>✅ Routes</li>
            </ul>
        </div>
        
        <div style="margin-top: 40px;">
            <h3>🔗 Navigation de Test</h3>
            <a href="/dashboard-json" class="btn">Test JSON</a>
            <a href="/dashboard-simple" class="btn btn-primary">Test Inertia+Vue</a>
            <a href="/dashboard" class="btn btn-danger">Dashboard Original (Blanc)</a>
            <a href="/membres" class="btn">Page Membres (OK)</a>
        </div>
        
        <div class="info-box" style="background: rgba(255, 193, 7, 0.2); margin-top: 40px;">
            <h3 style="color: #FFC107;">🔍 Conclusion Diagnostic</h3>
            <p><strong>Si vous voyez cette page:</strong></p>
            <ul>
                <li>Laravel fonctionne parfaitement</li>
                <li>Le serveur PHP fonctionne</li>
                <li>La base de données est connectée</li>
                <li>Le problème est dans <strong>Inertia.js + Vue.js</strong></li>
            </ul>
        </div>
    </div>
</body>
</html>
