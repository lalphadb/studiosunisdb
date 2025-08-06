@extends('layouts.admin')

@section('title', 'Gestion des Cours')

@section('content')
<div class="admin-header">
    <h1>📚 Gestion des Cours</h1>
    <p>Planifiez et gérez les cours de karaté</p>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-number">{{ \App\Models\Cours::count() ?? 0 }}</div>
        <div class="stat-label">Cours Programmés</div>
    </div>
    <div class="stat-card">
        <div class="stat-number">{{ \App\Models\Cours::where('date_cours', '>=', now()->startOfWeek())->count() ?? 0 }}</div>
        <div class="stat-label">Cette Semaine</div>
    </div>
</div>

<div class="section-card">
    <h3>Planning</h3>
    <p style="text-align: center; opacity: 0.8; padding: 40px;">
        📅 Calendrier des cours en développement
    </p>
</div>
@endsection
