<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StudiosDB v6 - Membres Blade</title>
    <style>
        body { font-family: -apple-system, sans-serif; margin: 0; padding: 0; background: #f9fafb; }
        .header { background: white; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1); padding: 20px 0; }
        .header-content { max-width: 1200px; margin: 0 auto; padding: 0 20px; display: flex; justify-content: space-between; align-items: center; }
        .container { max-width: 1200px; margin: 0 auto; padding: 30px 20px; }
        .card { background: white; padding: 25px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1); margin-bottom: 20px; }
        .stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .stat { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1); text-align: center; }
        .stat-value { font-size: 28px; font-weight: bold; margin-bottom: 5px; }
        .stat-label { color: #6b7280; font-size: 14px; }
        .table { width: 100%; border-collapse: collapse; }
        .table th, .table td { padding: 12px; text-align: left; border-bottom: 1px solid #e5e7eb; }
        .table th { background: #f9fafb; font-weight: 500; color: #374151; }
        .badge { padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 500; }
        .badge-success { background: #dcfce7; color: #166534; }
        .badge-warning { background: #fef3c7; color: #92400e; }
        .btn { padding: 8px 16px; text-decoration: none; border-radius: 4px; font-size: 14px; font-weight: 500; display: inline-block; }
        .btn-primary { background: #2563eb; color: white; }
        .btn-secondary { background: #6b7280; color: white; }
        .nav-links { display: flex; gap: 15px; margin-bottom: 20px; }
        .nav-links a { padding: 10px 16px; background: #f3f4f6; color: #374151; text-decoration: none; border-radius: 6px; font-weight: 500; }
        .nav-links a:hover { background: #e5e7eb; }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="header-content">
            <h1>👥 Gestion des Membres</h1>
            <div>StudiosDB v6 - Version Blade</div>
        </div>
    </div>

    <div class="container">
        <!-- Navigation -->
        <div class="nav-links">
            <a href="/blade/dashboard">📊 Dashboard</a>
            <a href="/blade/debug">🔧 Debug</a>
            <a href="/membres">🧪 Test Membres Inertia</a>
        </div>

        <!-- Stats -->
        <div class="stats">
            <div class="stat">
                <div class="stat-value" style="color: #16a34a;">{{ $total }}</div>
                <div class="stat-label">Total Membres</div>
            </div>
            <div class="stat">
                <div class="stat-value" style="color: #2563eb;">{{ $actifs }}</div>
                <div class="stat-label">Membres Actifs</div>
            </div>
            <div class="stat">
                <div class="stat-value" style="color: #d97706;">{{ $total - $actifs }}</div>
                <div class="stat-label">Membres Inactifs</div>
            </div>
        </div>

        <!-- Liste des membres -->
        <div class="card">
            <h2 style="margin-top: 0;">📋 Liste des Membres</h2>
            
            @if($membres->count() > 0)
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Date Inscription</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($membres as $membre)
                            <tr>
                                <td>{{ $membre->id }}</td>
                                <td>{{ $membre->prenom }} {{ $membre->nom }}</td>
                                <td>{{ $membre->user->email ?? 'N/A' }}</td>
                                <td>{{ $membre->date_inscription->format('d/m/Y') }}</td>
                                <td>
                                    @if($membre->statut == 'actif')
                                        <span class="badge badge-success">✅ Actif</span>
                                    @else
                                        <span class="badge badge-warning">⚠️ {{ ucfirst($membre->statut) }}</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="#" class="btn btn-primary">👁️ Voir</a>
                                    <a href="#" class="btn btn-secondary">✏️ Éditer</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                
                <!-- Pagination -->
                <div style="margin-top: 20px;">
                    {{ $membres->links() }}
                </div>
            @else
                <div style="text-align: center; padding: 40px; color: #6b7280;">
                    <h3>Aucun membre trouvé</h3>
                    <p>La base de données ne contient aucun membre pour le moment.</p>
                    <a href="#" class="btn btn-primary">➕ Ajouter un membre</a>
                </div>
            @endif
        </div>
    </div>
</body>
</html>
