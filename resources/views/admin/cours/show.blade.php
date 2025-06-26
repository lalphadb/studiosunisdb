<h1>Debug Policy Cours</h1>
<p>User: {{ auth()->user()->name }}</p>
<p>Roles: {{ auth()->user()->roles->pluck('name')->implode(', ') }}</p>
<p>École user: {{ auth()->user()->ecole_id }}</p>
<p>École cours: {{ $cours->ecole_id ?? 'N/A' }}</p>
<p>Can view cours: {{ auth()->user()->can('view', $cours) ? 'YES' : 'NO' }}</p>
<p>Can viewAny cours: {{ auth()->user()->can('viewAny', App\Models\Cours::class) ? 'YES' : 'NO' }}</p>
