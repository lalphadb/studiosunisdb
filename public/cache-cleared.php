<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StudiosDB - Cache Cleared</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }
        .container {
            text-align: center;
            background: rgba(255,255,255,0.1);
            padding: 2rem;
            border-radius: 10px;
            backdrop-filter: blur(10px);
        }
        .btn {
            background: #4CAF50;
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            margin: 10px;
            text-decoration: none;
            display: inline-block;
        }
        .btn:hover { background: #45a049; }
        .info { background: rgba(255,255,255,0.2); padding: 1rem; border-radius: 5px; margin: 1rem 0; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 StudiosDB v5 Pro</h1>
        <h2>Cache Complètement Vidé !</h2>
        
        <div class="info">
            <h3>✅ Nouveaux Assets Générés</h3>
            <p>JS: app-Cq779qrS.js (594KB)</p>
            <p>CSS: app-DwFTuiu4.css (64KB)</p>
            <p>Timestamp: <?php echo date('Y-m-d H:i:s'); ?></p>
        </div>

        <div class="info">
            <h3>🎯 Instructions</h3>
            <p>1. Videz le cache de votre navigateur (Ctrl+Shift+R ou Cmd+Shift+R)</p>
            <p>2. Ou utilisez la navigation privée</p>
            <p>3. Cliquez sur "Aller au Login" ci-dessous</p>
        </div>

        <a href="/login" class="btn">🔐 Aller au Login</a>
        <a href="/dashboard" class="btn">📊 Aller au Dashboard</a>
        
        <div class="info">
            <h3>🔑 Credentials de test</h3>
            <p><strong>Email:</strong> louis@4lb.ca</p>
            <p><strong>Mot de passe:</strong> password123</p>
        </div>

        <script>
            // Force la suppression du cache côté navigateur
            if ('serviceWorker' in navigator) {
                navigator.serviceWorker.getRegistrations().then(function(registrations) {
                    for(let registration of registrations) {
                        registration.unregister();
                    }
                });
            }
            
            // Supprime le localStorage et sessionStorage
            localStorage.clear();
            sessionStorage.clear();
            
            console.log('Cache navigateur vidé - Nouveaux assets: app-Cq779qrS.js, app-DwFTuiu4.css');
        </script>
    </div>
</body>
</html>
