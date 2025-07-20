<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Dashboard Simple</title>
    <style>
        body {
            background: linear-gradient(135deg, #1f2937, #3b82f6);
            color: white;
            font-family: system-ui, -apple-system, sans-serif;
            margin: 0;
            padding: 20px;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .container {
            text-align: center;
            max-width: 600px;
        }
        .success {
            background: rgba(34, 197, 94, 0.2);
            border: 2px solid #22c55e;
            border-radius: 12px;
            padding: 30px;
            margin: 20px 0;
        }
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 20px;
            margin: 30px 0;
        }
        .stat {
            background: rgba(255, 255, 255, 0.1);
            padding: 20px;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🎉 StudiosDB v5 Test OK!</h1>
        
        <div class="success">
            <h2>✅ Laravel Fonctionne Parfaitement</h2>
            <p>Status: 200 OK | Auth: {{ auth()->user()->name ?? 'Utilisateur' }}</p>
        </div>
        
        <div class="stats">
            <div class="stat">
                <h3>👥</h3>
                <div>250</div>
                <small>Membres</small>
            </div>
            <div class="stat">
                <h3>📚</h3>
                <div>18</div>
                <small>Cours</small>
            </div>
            <div class="stat">
                <h3>✅</h3>
                <div>343</div>
                <small>Présences</small>
            </div>
            <div class="stat">
                <h3>💰</h3>
                <div>5750$</div>
                <small>Revenus</small>
            </div>
        </div>
        
        <div style="margin: 30px 0;">
            <h3>🔧 Diagnostic Problème</h3>
            <p><strong>Si tu vois cette page = Laravel OK</strong></p>
            <p><strong>Le problème est dans Inertia.js/Vue.js</strong></p>
        </div>
        
        <div style="background: rgba(255, 255, 255, 0.1); padding: 20px; border-radius: 8px;">
            <h4>🎯 Actions à faire:</h4>
            <p>1. Recompiler assets frontend</p>
            <p>2. Vérifier erreurs JavaScript (F12)</p>
            <p>3. Corriger configuration Inertia.js</p>
        </div>
        
        <div style="margin-top: 30px;">
            <a href="/dashboard" style="color: #60a5fa; text-decoration: none;">← Retour au dashboard Inertia</a>
        </div>
    </div>
</body>
</html>
