<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StudiosDB v5 - Debug & Navigation</title>
    <style>
        body { font-family: -apple-system, sans-serif; margin: 0; padding: 20px; background: #f5f5f5; }
        .container { max-width: 1200px; margin: 0 auto; }
        .card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { color: #2563eb; margin-bottom: 10px; }
        .success { background: #dcfce7; border: 1px solid #bbf7d0; color: #166534; padding: 15px; border-radius: 6px; margin-bottom: 20px; }
        .nav-buttons { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-bottom: 30px; }
        .btn { display: block; padding: 15px 20px; text-decoration: none; border-radius: 6px; text-align: center; font-weight: 500; transition: all 0.2s; }
        .btn-primary { background: #2563eb; color: white; }
        .btn-success { background: #16a34a; color: white; }
        .btn-warning { background: #d97706; color: white; }
        .btn-info { background: #0891b2; color: white; }
        .btn:hover { transform: translateY(-2px); box-shadow: 0 4px 15px rgba(0,0,0,0.2); }
        .extensions { display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 10px; }
        .ext { background: #f3f4f6; padding: 8px; border-radius: 4px; font-family: monospace; font-size: 14px; }
        .ext.active { background: #dcfce7; color: #166534; }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>🛠️ StudiosDB v5 Pro - Debug & Navigation</h1>
            <div class="success">
                <strong>✅ Laravel {{ $laravel_version }} fonctionne parfaitement !</strong><br>
                PHP {{ $php_version }} • Toutes extensions installées
            </div>
        </div>

        <!-- Navigation -->
        <div class="card">
            <h2>🧭 Navigation</h2>
            <div class="nav-buttons">
                <a href="/blade/login" class="btn btn-primary">🔑 Login Blade</a>
                <a href="/blade/dashboard" class="btn btn-success">📊 Dashboard Blade</a>
                <a href="/blade/membres" class="btn btn-warning">👥 Membres Blade</a>
                <a href="/blade/debug" class="btn btn-info">🔧 Debug (cette page)</a>
            </div>
            
            <div style="background: #fef3c7; padding: 15px; border-radius: 6px; border: 1px solid #f59e0b;">
                <strong>💡 Info:</strong> Ces pages Blade contournent temporairement Inertia.js en attendant la correction.
            </div>
        </div>

        <!-- Extensions PHP -->
        <div class="card">
            <h2>🔧 Extensions PHP</h2>
            <div class="extensions">
                @foreach($extensions as $ext)
                    <div class="ext active">✅ {{ $ext }}</div>
                @endforeach
            </div>
        </div>

        <!-- Tests Inertia -->
        <div class="card">
            <h2>🧪 Tests Inertia (Pages Blanches)</h2>
            <div class="nav-buttons">
                <a href="/login" class="btn" style="background: #dc2626; color: white;">❌ Login Inertia</a>
                <a href="/dashboard" class="btn" style="background: #dc2626; color: white;">❌ Dashboard Inertia</a>
                <a href="/membres" class="btn" style="background: #dc2626; color: white;">❌ Membres Inertia</a>
            </div>
            <p style="color: #dc2626; font-style: italic;">Ces liens mènent aux pages blanches Inertia (à corriger)</p>
        </div>

        <!-- Diagnostic -->
        <div class="card">
            <h2>📋 Diagnostic</h2>
            <div style="background: #f3f4f6; padding: 15px; border-radius: 6px; font-family: monospace; line-height: 1.6;">
                <div><strong>Laravel Version:</strong> {{ $laravel_version }}</div>
                <div><strong>PHP Version:</strong> {{ $php_version }}</div>
                <div><strong>Extensions Chargées:</strong> {{ count($extensions) }}</div>
                <div><strong>Timestamp:</strong> {{ now()->format('Y-m-d H:i:s') }}</div>
            </div>
        </div>
    </div>
</body>
</html>
