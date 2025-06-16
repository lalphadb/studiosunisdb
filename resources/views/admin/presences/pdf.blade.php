<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rapport de Présences - StudiosUnisDB</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            font-size: 12px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #0f172a;
            padding-bottom: 15px;
        }
        .header h1 {
            color: #0f172a;
            margin: 0;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .stats {
            display: flex;
            justify-content: space-around;
            margin: 20px 0;
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
        }
        .stat-item {
            text-align: center;
        }
        .stat-number {
            font-size: 18px;
            font-weight: bold;
            color: #0f172a;
        }
        .stat-label {
            font-size: 11px;
            color: #666;
            text-transform: uppercase;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #0f172a;
            color: white;
            font-weight: bold;
            font-size: 11px;
            text-transform: uppercase;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .status-present { color: #059669; font-weight: bold; }
        .status-absent { color: #dc2626; font-weight: bold; }
        .status-retard { color: #d97706; font-weight: bold; }
        .status-excuse { color: #2563eb; font-weight: bold; }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }
        .no-data {
            text-align: center;
            padding: 40px;
            color: #666;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🥋 StudiosUnisDB</h1>
        <p><strong>Rapport de Présences</strong></p>
        <p>Généré le {{ \Carbon\Carbon::now()->format('d/m/Y à H:i') }}</p>
    </div>

    @if($presences->count() > 0)
        <div class="stats">
            <div class="stat-item">
                <div class="stat-number">{{ $presences->where('statut', 'present')->count() }}</div>
                <div class="stat-label">Présents</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">{{ $presences->where('statut', 'absent')->count() }}</div>
                <div class="stat-label">Absents</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">{{ $presences->where('statut', 'retard')->count() }}</div>
                <div class="stat-label">Retards</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">{{ $presences->where('statut', 'excuse')->count() }}</div>
                <div class="stat-label">Excusés</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">{{ $presences->count() }}</div>
                <div class="stat-label">Total</div>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>École</th>
                    <th>Cours</th>
                    <th>Membre</th>
                    <th>Statut</th>
                    <th>Heure</th>
                    <th>Notes</th>
                </tr>
            </thead>
            <tbody>
                @foreach($presences as $presence)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($presence->date_presence)->format('d/m/Y') }}</td>
                        <td>{{ $presence->cours->ecole->nom }}</td>
                        <td>{{ $presence->cours->nom }}</td>
                        <td>{{ $presence->membre->prenom }} {{ $presence->membre->nom }}</td>
                        <td class="status-{{ $presence->statut }}">
                            @switch($presence->statut)
                                @case('present') ✓ Présent @break
                                @case('absent') ✗ Absent @break
                                @case('retard') ⏰ Retard @break
                                @case('excuse') ℹ Excusé @break
                            @endswitch
                        </td>
                        <td>
                            {{ $presence->heure_arrivee ? \Carbon\Carbon::parse($presence->heure_arrivee)->format('H:i') : '-' }}
                        </td>
                        <td>{{ $presence->notes ?: '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="no-data">
            <p>Aucune présence trouvée pour les critères sélectionnés.</p>
        </div>
    @endif

    <div class="footer">
        <p>StudiosUnisDB - Système de Gestion des Écoles de Karaté</p>
        <p>22 Studios Unis du Québec - {{ url('/') }}</p>
    </div>
</body>
</html>
