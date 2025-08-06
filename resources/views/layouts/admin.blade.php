<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - StudiosDB v5 Pro</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #1e3a8a 0%, #3730a3 50%, #581c87 100%);
            min-height: 100vh;
            color: white;
        }
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }
        .nav-header {
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(15px);
            border-radius: 20px;
            padding: 20px 30px;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: 1px solid rgba(255,255,255,0.2);
        }
        .nav-title {
            font-size: 24px;
            font-weight: bold;
            background: linear-gradient(45deg, #fbbf24, #f59e0b);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .nav-actions {
            display: flex;
            gap: 15px;
        }
        .admin-header {
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(15px);
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            text-align: center;
            border: 1px solid rgba(255,255,255,0.2);
        }
        .admin-header h1 {
            font-size: 36px;
            margin-bottom: 10px;
            background: linear-gradient(45deg, #fbbf24, #f59e0b);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .admin-header p {
            opacity: 0.9;
            font-size: 16px;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(15px);
            border-radius: 15px;
            padding: 25px;
            text-align: center;
            border: 1px solid rgba(255,255,255,0.2);
            transition: transform 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            background: rgba(255,255,255,0.25);
        }
        .stat-number {
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 10px;
            background: linear-gradient(45deg, #10b981, #059669);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .stat-label {
            font-size: 14px;
            opacity: 0.9;
        }
        .section-card {
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(15px);
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            border: 1px solid rgba(255,255,255,0.2);
        }
        .section-card h3 {
            font-size: 20px;
            margin-bottom: 20px;
            color: #fbbf24;
        }
        .actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 15px;
        }
        .btn {
            background: rgba(255,255,255,0.2);
            color: white;
            padding: 12px 16px;
            border: none;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.3s;
            border: 1px solid rgba(255,255,255,0.3);
            text-align: center;
        }
        .btn:hover {
            background: rgba(255,255,255,0.3);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
        }
        .btn-primary { background: rgba(59, 130, 246, 0.8); }
        .btn-success { background: rgba(16, 185, 129, 0.8); }
        .btn-warning { background: rgba(245, 158, 11, 0.8); }
        .btn-danger { background: rgba(239, 68, 68, 0.8); }
        .btn-info { background: rgba(6, 182, 212, 0.8); }
        .btn-small {
            padding: 8px 12px;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Navigation Header -->
        <div class="nav-header">
            <div class="nav-title">🥋 StudiosDB v5 Pro</div>
            <div class="nav-actions">
                <a href="/dashboard" class="btn btn-primary btn-small">🏠 Dashboard</a>
                <a href="/telescope" class="btn btn-warning btn-small">🔭 Telescope</a>
                <a href="/logout" class="btn btn-danger btn-small">🚪 Déconnexion</a>
            </div>
        </div>

        <!-- Content -->
        @yield('content')

        <!-- Footer -->
        <div style="text-align: center; margin-top: 40px; opacity: 0.8; font-size: 14px;">
            <p>StudiosDB v5 Pro avec Laravel Telescope - {{ date('Y-m-d H:i:s') }}</p>
        </div>
    </div>
</body>
</html>
