<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Export Utilisateurs - StudiosDB</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 30px; }
        .logo { font-size: 18px; font-weight: bold; color: #1e40af; }
        .subtitle { color: #64748b; margin-top: 5px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f8fafc; font-weight: bold; }
        .footer { margin-top: 30px; text-align: center; color: #64748b; font-size: 10px; }
        .loi25 { background-color: #fef3c7; padding: 10px; border-left: 4px solid #f59e0b; margin: 20px 0; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">StudiosDB - Export Utilisateurs</div>
        <div class="subtitle">Généré le {{ date('d/m/Y à H:i') }}</div>
    </div>

    <div class="loi25">
        <strong>Conformité Loi 25 :</strong> Ce document contient des données personnelles et doit être traité selon la réglementation québécoise sur la protection des renseignements personnels.
    </div>

    <table>
        <thead>
            <tr>
                <th>Nom</th>
                <th>Email</th>
                <th>École</th>
                <th>Ceinture</th>
                <th>Rôle</th>
                <th>Actif</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->ecole?->nom ?? 'N/A' }}</td>
                <td>{{ $user->ceinture_actuelle?->nom ?? 'Aucune' }}</td>
                <td>{{ $user->roles->pluck('name')->implode(', ') }}</td>
                <td>{{ $user->active ? 'Oui' : 'Non' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Document généré par StudiosDB - Système conforme à la Loi 25 du Québec</p>
        <p>Export demandé par : {{ auth()->user()->email }} - IP: {{ request()->ip() }}</p>
    </div>
</body>
</html>
