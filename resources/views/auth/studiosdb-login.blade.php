<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - StudiosDB Enterprise</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-container mx-auto max-w-7xl px-4 {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 100%;
            max-width: 450px;
        }
        .login-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }
        .login-header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        .login-header p {
            opacity: 0.9;
            font-size: 1.1rem;
        }
        .login-body {
            padding: 40px 30px;
        }
        .form-floating {
            margin-bottom: 1.5rem;
        }
        .block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 {
            border-radius: 15px;
            border: 2px solid #e9ecef;
            padding: 1rem;
            font-size: 1rem;
        }
        .block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25);
        }
        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 15px;
            padding: 1rem 2rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            width: 100%;
            transition: all 0.3s ease;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }
        .debug-panel {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
            border-left: 4px solid #28a745;
        }
        .debug-panel h6 {
            color: #28a745;
            margin-bottom: 15px;
        }
        .credential-item {
            background: white;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 10px;
            border: 1px solid #dee2e6;
        }
        .alert {
            border-radius: 10px;
            border: none;
        }
        .form-check-input:checked {
            background-color: #667eea;
            border-color: #667eea;
        }
    </style>
</head>
<body>
    <div class="login-container mx-auto max-w-7xl px-4">
        <div class="login-header">
            <h1><i class="fas fa-graduation-cap"></i> StudiosDB</h1>
            <p>Système de gestion des écoles</p>
        </div>
        
        <div class="login-body">
            @if(session('message'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    <i class="fas fa-check-circle"></i> {{ session('message') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                
                <div class="form-floating">
                    <input type="email" 
                           class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('email') is-invalid @enderror" 
                           id="email" 
                           name="email" 
                           placeholder="name@example.com"
                           value="{{ old('email') }}" 
                           required 
                           autocomplete="email" 
                           autofocus>
                    <label for="email"><i class="fas fa-envelope"></i> Adresse email</label>
                    @error('email')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-floating">
                    <input type="password" 
                           class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('password') is-invalid @enderror" 
                           id="password" 
                           name="password" 
                           placeholder="Mot de passe"
                           required 
                           autocomplete="current-password">
                    <label for="password"><i class="fas fa-lock"></i> Mot de passe</label>
                    @error('password')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="remember" name="remember">
                    <label class="form-check-label" for="remember">
                        Se souvenir de moi
                    </label>
                </div>

                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded btn-login">
                    <i class="fas fa-sign-in-alt"></i> Se connecter
                </button>
            </form>

            @if(app()->environment('local') || config('app.debug'))
            <div class="debug-panel">
                <h6><i class="fas fa-tools"></i> Mode Développement - Comptes de test</h6>
                
                <div class="credential-item">
                    <strong>Super Administrateur</strong><br>
                    <code>lalpha@4lb.ca</code> / <code>password123</code>
                </div>
                
                <div class="credential-item">
                    <strong>Administrateur École</strong><br>
                    <code>louis@4lb.ca</code> / <code>password123</code>
                </div>
                
                <small class="text-muted">
                    <i class="fas fa-info-circle"></i> 
                    Ces comptes sont créés automatiquement en mode développement.
                </small>
            </div>
            @endif
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
