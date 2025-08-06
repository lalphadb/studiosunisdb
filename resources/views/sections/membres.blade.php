@extends('layouts.admin')

@section('title', 'Gestion des Membres')

@section('content')
<div class="admin-header">
    <h1>👥 Gestion des Membres</h1>
    <p>Gérez les membres de votre école de karaté</p>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-number">{{ \App\Models\Membre::count() ?? 0 }}</div>
        <div class="stat-label">Membres Actifs</div>
    </div>
    <div class="stat-card">
        <div class="stat-number">{{ \App\Models\Membre::where('created_at', '>=', now()->startOfMonth())->count() ?? 0 }}</div>
        <div class="stat-label">Nouveaux ce mois</div>
    </div>
</div>

<div class="section-card">
    <h3>Actions Rapides</h3>
    <div class="actions-grid">
        <a href="/membres/create" class="btn btn-success">➕ Nouveau Membre</a>
        <a href="/membres?export=1" class="btn btn-warning">📊 Exporter Liste</a>
        <a href="/membres?filter=active" class="btn btn-primary">👥 Membres Actifs</a>
        <a href="/membres?filter=recent" class="btn btn-info">🆕 Récents</a>
    </div>
</div>

<div class="section-card">
    <h3>Liste des Membres</h3>
    <p style="text-align: center; opacity: 0.8; padding: 40px;">
        🚧 Section en développement - Intégration avec le modèle Membre en cours
    </p>
</div>
@endsection
