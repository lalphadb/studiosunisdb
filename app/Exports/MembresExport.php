<?php

namespace App\Exports;

use App\Models\Membre;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Collection;

class MembresExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $ecoleId;
    protected $filters;

    public function __construct($ecoleId = null, array $filters = [])
    {
        $this->ecoleId = $ecoleId ?? auth()->user()->ecole_id;
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Membre::with(['ceintureActuelle', 'user'])
            ->where('ecole_id', $this->ecoleId);

        // Appliquer les filtres
        if (!empty($this->filters['statut'])) {
            $query->where('statut', $this->filters['statut']);
        }

        if (!empty($this->filters['recherche'])) {
            $query->recherche($this->filters['recherche']);
        }

        if (!empty($this->filters['ceinture_id'])) {
            $query->where('ceinture_actuelle_id', $this->filters['ceinture_id']);
        }

        return $query->orderBy('nom')->orderBy('prenom')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Prénom',
            'Nom',
            'Email',
            'Téléphone',
            'Date de naissance',
            'Âge',
            'Sexe',
            'Adresse',
            'Ville',
            'Code postal',
            'Province',
            'Contact urgence - Nom',
            'Contact urgence - Téléphone',
            'Contact urgence - Relation',
            'Statut',
            'Ceinture actuelle',
            'Date inscription',
            'Dernière présence',
            'Notes médicales',
            'Allergies',
            'Médicaments',
            'Consentement photos',
            'Consentement communications',
        ];
    }

    public function map($membre): array
    {
        return [
            $membre->id,
            $membre->prenom,
            $membre->nom,
            $membre->email,
            $membre->telephone,
            $membre->date_naissance?->format('Y-m-d'),
            $membre->age,
            $membre->sexe,
            $membre->adresse,
            $membre->ville,
            $membre->code_postal,
            $membre->province,
            $membre->contact_urgence_nom,
            $membre->contact_urgence_telephone,
            $membre->contact_urgence_relation,
            $membre->statut,
            $membre->ceintureActuelle?->nom,
            $membre->date_inscription?->format('Y-m-d'),
            $membre->date_derniere_presence?->format('Y-m-d'),
            $membre->notes_medicales,
            is_array($membre->allergies) ? implode(', ', $membre->allergies) : $membre->allergies,
            is_array($membre->medicaments) ? implode(', ', $membre->medicaments) : $membre->medicaments,
            $membre->consentement_photos ? 'Oui' : 'Non',
            $membre->consentement_communications ? 'Oui' : 'Non',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Style pour l'en-tête
        $sheet->getStyle('A1:X1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '1F2937'],
            ],
        ]);

        // Bordures
        $highestRow = $sheet->getHighestRow();
        $sheet->getStyle("A1:X{$highestRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => 'E5E7EB'],
                ],
            ],
        ]);

        return [];
    }
}
