<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StudiosDB v6 - Dashboard Blade</title>
    <style>
        body { font-family: -apple-system, sans-serif; margin: 0; padding: 0; background: #f9fafb; }
        .header { background: white; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1); padding: 20px 0; }
        .header-content { max-width: 1200px; margin: 0 auto; padding: 0 20px; display: flex; justify-content: space-between; align-items: center; }
        .header h1 { margin: 0; color: #1f2937; }
        .user-info { color: #6b7280; }
        .container { max-width: 1200px; margin: 0 auto; padding: 30px 20px; }
        .success { background: #dcfce7; border: 1px solid #bbf7d0; color: #166534; padding: 20px; border-radius: 8px; margin-bottom: 30px; }
        .metrics { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .metric-card { background: white; padding: 25px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1); }
        .metric-card h3 { margin: 0 0 10px 0; color: #6b7280; font-size: 14px; font-weight: 500; text-transform: uppercase; }
        .metric-card .value { font-size: 32px; font-weight: bold; margin-bottom: 5px; }
        .metric-card .label { color: #6b7280; font-size: 14px; }
        .actions { background: white; padding: 25px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1); }
        .actions h2 { margin-top: 0; }
        .action-buttons { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-bottom: 20px; }
        .btn { padding: 12px 20px; text-decoration: none; border-radius: 6px; text-align: center; font-weight: 500; display: block; transition: all 0.2s; }
        .btn-primary { background: #2563eb; color: white; }
        .btn-success { background: #16a34a; color: white; }
        .btn-warning { background: #d97706; color: white; }
        .btn-danger { background: #dc2626; color: white; }
        .btn:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.15); }
        .info { background: #dbeafe; border: 1px solid #93c5fd; color: #1e40af; padding: 15px; border-radius: 6px; }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="header-content">
            <h1>📊 StudiosDB v6 Pro - Dashboard</h1>
            <div class="user-info">
                Connecté: {{ $user->name }} | {{ $timestamp }}
            </div>
        </div>
    </div>

    <div class="container">
        <!-- Success -->
        <div class="success">
            <h2 style="margin-top: 0;">🎉 Dashboard Blade Fonctionnel !</h2>
            <p style="margin-bottom: 0;">Laravel + Blade = ✅ Parfait | Inertia.js = 🔧 En cours de réparation</p>
        </div>

        <!-- Métriques -->
        <div class="metrics">
            <div class="metric-card">
                <h3>Membres Actifs</h3>
                <div class="value" style="color: #2563eb;">{{ $metriques['membres_actifs'] }}</div>
                <div class="label">membres avec statut actif</div>
            </div>
            
            <div class="metric-card">
                <h3>Total Membres</h3>
                <div class="value" style="color: #16a34a;">{{ $metriques['total_membres'] }}</div>
                <div class="label">membres inscrits</div>
            </div>
            
            <div class="metric-card">
                <h3>Cours Actifs</h3>
                <div class="value" style="color: #d97706;">{{ $metriques['cours_actifs'] }}</div>
                <div class="label">cours en activité</div>
            </div>
            
            <div class="metric-card">
                <h3>Utilisateurs</h3>
                <div class="value" style="color: #7c3aed;">{{ $metriques['users_total'] }}</div>
                <div class="label">comptes créés</div>
            </div>
            
            <div class="metric-card">
                <h3>Présences Semaine</h3>
                <div class="value" style="color: #dc2626;">{{ $metriques['presences_semaine'] }}</div>
                <div class="label">présences cette semaine</div>
            </div>
        </div>

        <!-- Actions -->
        <div class="actions">
            <h2>🛠️ Actions & Navigation</h2>
            
            <div class="action-buttons">
                <a href="/blade/membres" class="btn btn-primary">👥 Gestion Membres</a>
                <a href="/blade/debug" class="btn btn-success">🔧 Page Debug</a>
                <a href="/dashboard" class="btn btn-warning">🧪 Test Dashboard Inertia</a>
                <form action="/blade/logout" method="POST" style="margin: 0;">
                    @csrf
                    <button type="submit" class="btn btn-danger" style="width: 100%; border: none; cursor: pointer;">🚪 Déconnexion</button>
                </form>
            </div>
            
            <div class="info">
                <strong>💡 Information:</strong> Ce dashboard Blade fonctionne parfaitement et contourne Inertia.js. 
                Toutes les données sont réelles et proviennent de votre base de données StudiosDB.
            </div>
        </div>
    </div>
</body>
</html>
