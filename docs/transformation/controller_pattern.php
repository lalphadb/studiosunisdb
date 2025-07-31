<?php
// Pattern standard pour les contrôleurs StudiosDB v5

public function index(Request $request): Response
{
    // 1. Validation des filtres
    $validated = $request->validate([
        'search' => 'nullable|string|max:255',
        'statut' => 'nullable|string|in:actif,inactif',
        // Autres filtres spécifiques
    ]);

    // 2. Construction de la requête avec filtres
    $query = [Model]::with(['relations'])
        ->when($validated['search'] ?? null, function($q, $search) {
            $q->where('nom', 'like', "%{$search}%");
        })
        ->when($validated['statut'] ?? null, fn($q, $statut) => $q->where('statut', $statut));

    // 3. Pagination
    $items = $query->paginate(25)->withQueryString();

    // 4. Statistiques pour KPI
    $stats = [
        'total_items' => [Model]::count(),
        'items_actifs' => [Model]::where('statut', 'actif')->count(),
        'nouveaux_mois' => [Model]::whereDate('created_at', '>=', now()->startOfMonth())->count(),
        'items_special' => $this->calculateSpecialStat(),
    ];

    // 5. Données additionnelles
    $additional_data = RelatedModel::all();

    return Inertia::render('[Module]/Index', compact('items', 'stats', 'additional_data'));
}

private function calculateSpecialStat(): int
{
    // Logique spécifique au module
    return 0;
}
