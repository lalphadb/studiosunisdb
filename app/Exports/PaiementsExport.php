<?php

namespace App\Exports;

use App\Models\Paiement;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PaiementsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $ecoleId;
    protected $dateDebut;
    protected $dateFin;

    public function __construct($ecoleId = null, $dateDebut = null, $dateFin = null)
    {
        $this->ecoleId = $ecoleId;
        $this->dateDebut = $dateDebut;
        $this->dateFin = $dateFin;
    }

    public function collection()
    {
        $query = Paiement::with(['user', 'user.ecole']);
        
        if ($this->ecoleId) {
            $query->whereHas('user', function($q) {
                $q->where('ecole_id', $this->ecoleId);
            });
        }
        
        if ($this->dateDebut) {
            $query->where('date_paiement', '>=', $this->dateDebut);
        }
        
        if ($this->dateFin) {
            $query->where('date_paiement', '<=', $this->dateFin);
        }
        
        return $query->orderBy('date_paiement', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'Date Paiement',
            'Utilisateur',
            'École',
            'Montant',
            'Méthode',
            'Référence',
            'Statut',
            'Reçu N°',
        ];
    }

    public function map($paiement): array
    {
        return [
            $paiement->date_paiement->format('d/m/Y'),
            $paiement->user->nom . ' ' . $paiement->user->prenom,
            $paiement->user->ecole?->nom ?? 'N/A',
            number_format($paiement->montant, 2) . ' $',
            $paiement->methode,
            $paiement->reference,
            $paiement->statut,
            $paiement->recu_numero,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
