    /**
     * Export des paiements en Excel
     */
    public function export()
    {
        $this->authorize('export-paiements');

        $query = \App\Models\Paiement::with(['membre', 'ecole', 'user']);

        // Filtrage par école pour admin d'école
        if (!auth()->user()->hasRole('superadmin') && auth()->user()->ecole_id) {
            $query->where('ecole_id', auth()->user()->ecole_id);
        }

        $paiements = $query->get();

        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\PaiementsExport($paiements), 
            'paiements-' . now()->format('Y-m-d') . '.xlsx'
        );
    }
}
