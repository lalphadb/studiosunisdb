<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StudiosDB v5 - Login Blade</title>
    <style>
        body { font-family: -apple-system, sans-serif; margin: 0; padding: 0; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .container { background: white; padding: 40px; border-radius: 12px; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1); width: 100%; max-width: 400px; }
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { color: #1f2937; margin-bottom: 8px; font-size: 28px; }
        .header p { color: #6b7280; margin: 0; }
        .success { background: #dcfce7; border: 1px solid #bbf7d0; color: #166534; padding: 15px; border-radius: 6px; margin-bottom: 25px; text-align: center; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 500; color: #374151; }
        .form-group input { width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 16px; transition: border-color 0.2s; box-sizing: border-box; }
        .form-group input:focus { outline: none; border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1); }
        .btn { width: 100%; padding: 12px; background: #2563eb; color: white; border: none; border-radius: 6px; font-size: 16px; font-weight: 500; cursor: pointer; transition: background-color 0.2s; }
        .btn:hover { background: #1d4ed8; }
        .checkbox-group { display: flex; align-items: center; margin-bottom: 20px; }
        .checkbox-group input { width: auto; margin-right: 8px; }
        .nav-links { text-align: center; margin-top: 25px; padding-top: 25px; border-top: 1px solid #e5e7eb; }
        .nav-links a { color: #2563eb; text-decoration: none; margin: 0 10px; }
        .nav-links a:hover { text-decoration: underline; }
        .test-info { background: #dbeafe; border: 1px solid #93c5fd; color: #1e40af; padding: 15px; border-radius: 6px; margin-top: 20px; font-size: 14px; }
        .error { background: #fef2f2; border: 1px solid #fecaca; color: #dc2626; padding: 15px; border-radius: 6px; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>StudiosDB v5</h1>
            <p>Connexion - Version Blade</p>
        </div>

        <!-- Success message -->
        <div class="success">
            <strong>✅ Laravel fonctionne parfaitement !</strong><br>
            Version Blade temporaire en attendant correction Inertia
        </div>

        <!-- Erreurs -->
        @if($errors->any())
            <div class="error">
                @foreach($errors->all() as $error)
                    {{ $error }}<br>
                @endforeach
            </div>
        @endif

        <!-- Formulaire -->
        <form method="POST" action="/blade/login">
            @csrf
            
            <div class="form-group">
                <label for="email">Adresse email</label>
                <input type="email" id="email" name="email" value="louis@4lb.ca" required autofocus>
            </div>
            
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" value="password" required>
            </div>
            
            <div class="checkbox-group">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">Se souvenir de moi</label>
            </div>
            
            <button type="submit" class="btn">Se connecter</button>
        </form>

        <!-- Test info -->
        <div class="test-info">
            <strong>🧪 Compte de test :</strong><br>
            Email: louis@4lb.ca<br>
            Password: password
        </div>

        <!-- Navigation -->
        <div class="nav-links">
            <a href="/blade/debug">🔧 Debug</a>
            <a href="/login">🚀 Test Inertia</a>
        </div>
    </div>
</body>
</html>
