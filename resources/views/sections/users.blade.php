@extends('layouts.admin')

@section('title', 'Administration Système')

@section('content')
<div class="admin-header">
    <h1>👤 Gestion des Utilisateurs</h1>
    <p>Gérez les comptes utilisateurs et leurs permissions</p>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-number">{{ \App\Models\User::count() }}</div>
        <div class="stat-label">Utilisateurs Total</div>
    </div>
    <div class="stat-card">
        <div class="stat-number">{{ \App\Models\User::where('created_at', '>=', now()->startOfMonth())->count() }}</div>
        <div class="stat-label">Nouveaux ce mois</div>
    </div>
</div>

<div class="section-card">
    <h3>🔭 Monitoring Telescope</h3>
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-number">{{ \Illuminate\Support\Facades\DB::table('telescope_entries')->count() }}</div>
            <div class="stat-label">Entrées Telescope</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ \Illuminate\Support\Facades\DB::table('telescope_entries')->where('created_at', '>=', now()->subHour())->count() }}</div>
            <div class="stat-label">Dernière Heure</div>
        </div>
    </div>
    <div class="actions-grid">
        <a href="/telescope" class="btn btn-warning">🔭 Ouvrir Telescope</a>
        <a href="/telescope/requests" class="btn btn-primary">📡 Requêtes</a>
        <a href="/telescope/exceptions" class="btn btn-danger">⚠️ Exceptions</a>
        <a href="/telescope/queries" class="btn btn-info">🗄️ Requêtes SQL</a>
    </div>
</div>

<div class="section-card">
    <h3>Liste des Utilisateurs</h3>
    <div style="overflow-x: auto;">
        <table style="width: 100%; background: rgba(255,255,255,0.9); color: #333; border-radius: 10px; overflow: hidden;">
            <thead>
                <tr style="background: rgba(59, 130, 246, 0.8); color: white;">
                    <th style="padding: 15px; text-align: left;">ID</th>
                    <th style="padding: 15px; text-align: left;">Nom</th>
                    <th style="padding: 15px; text-align: left;">Email</th>
                    <th style="padding: 15px; text-align: left;">Créé le</th>
                </tr>
            </thead>
            <tbody>
                @foreach(\App\Models\User::latest()->take(10)->get() as $user)
                <tr style="border-bottom: 1px solid rgba(0,0,0,0.1);">
                    <td style="padding: 12px;">{{ $user->id }}</td>
                    <td style="padding: 12px;">{{ $user->name }}</td>
                    <td style="padding: 12px;">{{ $user->email }}</td>
                    <td style="padding: 12px;">{{ $user->created_at->format('d/m/Y H:i') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
