<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - StudiosDB v6 Pro</title>
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
        .header {
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(15px);
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            text-align: center;
            border: 1px solid rgba(255,255,255,0.2);
        }
        .title { 
            font-size: 48px; 
            margin-bottom: 10px;
            background: linear-gradient(45deg, #fbbf24, #f59e0b);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .subtitle { font-size: 18px; opacity: 0.9; }
        .user-info {
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(10px);
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 30px;
            border: 1px solid rgba(255,255,255,0.2);
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }
        .stat-card {
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(15px);
            border-radius: 20px;
            padding: 30px;
            text-align: center;
            border: 1px solid rgba(255,255,255,0.2);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #10b981, #059669);
        }
        .stat-card:hover {
            transform: translateY(-8px);
            background: rgba(255,255,255,0.25);
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
        }
        .stat-number {
            font-size: 42px;
            font-weight: bold;
            margin-bottom: 15px;
            background: linear-gradient(45deg, #fbbf24, #f59e0b);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .stat-label {
            font-size: 16px;
            opacity: 0.9;
            font-weight: 500;
        }
        .sections-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 30px;
            margin-bottom: 40px;
        }
        .section-card {
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(15px);
            border-radius: 20px;
            padding: 30px;
            border: 1px solid rgba(255,255,255,0.2);
        }
        .section-title {
            font-size: 24px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 15px;
        }
        .btn {
            background: rgba(255,255,255,0.2);
            color: white;
            padding: 15px 20px;
            border: none;
            border-radius: 12px;
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
        .btn-primary { background: rgba(59, 130, 246, 0.8); border-color: rgba(59, 130, 246, 0.9); }
        .btn-success { background: rgba(16, 185, 129, 0.8); border-color: rgba(16, 185, 129, 0.9); }
        .btn-warning { background: rgba(245, 158, 11, 0.8); border-color: rgba(245, 158, 11, 0.9); }
        .btn-danger { background: rgba(239, 68, 68, 0.8); border-color: rgba(239, 68, 68, 0.9); }
        .btn-telescope { 
            background: linear-gradient(45deg, #8b5cf6, #a855f7); 
            border-color: rgba(168, 85, 247, 0.9);
            font-weight: 600;
        }
        .admin-section {
            background: linear-gradient(45deg, rgba(139, 92, 246, 0.2), rgba(168, 85, 247, 0.2));
            border: 2px solid rgba(168, 85, 247, 0.5);
        }
        .quick-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }
        .quick-stat {
            background: rgba(255,255,255,0.1);
            padding: 15px;
            border-radius: 10px;
            text-align: center;
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            opacity: 0.8;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="title">🥋 StudiosDB v6 Pro</div>
            <div class="subtitle">École de Karaté - Dashboard Professionnel Avancé</div>
        </div>

        <!-- User Info -->
        <div class="user-info">
            <h3>👤 Session Utilisateur</h3>
            <p><strong>Nom:</strong> {{ $user_name }}</p>
            <p><strong>Email:</strong> {{ $user_email }}</p>
            <p><strong>Rôle:</strong> <span style="background: rgba(16, 185, 129, 0.8); padding: 4px 12px; border-radius: 20px;">{{ $user_role }}</span></p>
        </div>

        <!-- Statistiques Principales -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number">{{ $total_membres }}</div>
                <div class="stat-label">👥 Membres Inscrits</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-number">{{ $total_cours }}</div>
                <div class="stat-label">📚 Cours Programmés</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-number">{{ $total_presences }}</div>
                <div class="stat-label">✅ Présences Enregistrées</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-number">{{ $total_paiements }}</div>
                <div class="stat-label">💰 Paiements Effectués</div>
            </div>
        </div>

        <!-- Sections Principales -->
        <div class="sections-grid">
            <!-- Gestion Membres -->
            <div class="section-card">
                <div class="section-title">
                    👥 Gestion des Membres
                </div>
                <div class="actions-grid">
                    <a href="/membres" class="btn btn-primary">� Liste</a>
                    <a href="/membres/create" class="btn btn-success">➕ Nouveau</a>
                    <a href="/membres?export=1" class="btn btn-warning">📊 Export</a>
                </div>
            </div>

            <!-- Gestion Cours -->
            <div class="section-card">
                <div class="section-title">
                    📚 Gestion des Cours
                </div>
                <div class="actions-grid">
                    <a href="/cours" class="btn btn-primary">📋 Planning</a>
                    <a href="/cours/create" class="btn btn-success">➕ Nouveau</a>
                    <a href="/presences" class="btn btn-warning">✅ Présences</a>
                </div>
            </div>

            <!-- Finances -->
            <div class="section-card">
                <div class="section-title">
                    💰 Gestion Financière
                </div>
                <div class="actions-grid">
                    <a href="/paiements" class="btn btn-primary">� Paiements</a>
                    <a href="/paiements/create" class="btn btn-success">➕ Encaisser</a>
                    <a href="/rapports" class="btn btn-warning">� Rapports</a>
                </div>
            </div>

            <!-- Administration Système -->
            <div class="section-card admin-section">
                <div class="section-title">
                    ⚙️ Administration Système
                </div>
                <div class="quick-stats">
                    <div class="quick-stat">
                        <div style="font-size: 20px; font-weight: bold;">{{ \App\Models\User::count() }}</div>
                        <div style="font-size: 12px;">Utilisateurs</div>
                    </div>
                    <div class="quick-stat">
                        <div style="font-size: 20px; font-weight: bold;">{{ \Illuminate\Support\Facades\DB::table('telescope_entries')->count() }}</div>
                        <div style="font-size: 12px;">Logs Telescope</div>
                    </div>
                </div>
                <div class="actions-grid">
                    <a href="/telescope" class="btn btn-telescope">🔭 Telescope</a>
                    <a href="/users" class="btn btn-primary">👤 Utilisateurs</a>
                    <a href="/settings" class="btn btn-warning">⚙️ Paramètres</a>
                    <a href="/backup" class="btn btn-success">💾 Sauvegarde</a>
                </div>
            </div>
        </div>

        <!-- Actions Rapides -->
        <div class="section-card">
            <div class="section-title">
                ⚡ Actions Rapides
            </div>
            <div class="actions-grid">
                <a href="/test-auth" class="btn btn-primary">🔍 Test Auth</a>
                <a href="/phpinfo" class="btn btn-warning">📋 PHP Info</a>
                <a href="/logs" class="btn btn-warning">📄 Logs Système</a>
                <a href="/cache/clear" class="btn btn-success">🗑️ Vider Cache</a>
                <a href="/logout" class="btn btn-danger">🚪 Déconnexion</a>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>StudiosDB v6 Pro avec Laravel Telescope - {{ date('Y-m-d H:i:s') }}</p>
            <p>Système complet de gestion d'école de karaté</p>
        </div>
    </div>
</body>
</html>
