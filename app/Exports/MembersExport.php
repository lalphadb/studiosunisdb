<?php
declare(strict_types=1);

namespace App\Exports;

use App\Models\Membre;
use Carbon\CarbonImmutable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MembersExport implements FromQuery, WithHeadings, WithMapping, WithStyles
{
    use Exportable;

    protected array $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    /**
     * Query builder pour l'export
     */
    public function query()
    {
        $query = Membre::query()
            ->with([
                'user:id,email',
                'ceintureActuelle:id,name,color_hex',
                'ecole:id,nom'
            ])
            ->where('ecole_id', auth()->user()->ecole_id);

        // Applique les filtres
        if (!empty($this->filters['q'])) {
            $q = $this->filters['q'];
            $query->where(function ($w) use ($q) {
                $w->where('prenom', 'like', "%{$q}%")
                  ->orWhere('nom', 'like', "%{$q}%")
                  ->orWhere('telephone', 'like', "%{$q}%")
                  ->orWhereHas('user', fn($u) => $u->where('email', 'like', "%{$q}%"));
            });
        }

        if (!empty($this->filters['statut'])) {
            $query->where('statut', $this->filters['statut']);
        }

        if (!empty($this->filters['ceinture_id'])) {
            $query->where('ceinture_actuelle_id', $this->filters['ceinture_id']);
        }

        if (!empty($this->filters['age_group'])) {
            if ($this->filters['age_group'] === 'mineur') {
                $query->whereDate('date_naissance', '>', CarbonImmutable::now()->subYears(18)->toDateString());
            } elseif ($this->filters['age_group'] === 'adulte') {
                $query->whereDate('date_naissance', '<=', CarbonImmutable::now()->subYears(18)->toDateString());
            }
        }

        // Tri
        $sort = $this->filters['sort'] ?? 'nom';
        $dir = $this->filters['dir'] ?? 'asc';
        $query->orderBy($sort, $dir);

        return $query;
    }

    /**
     * Entêtes des colonnes Excel
     */
    public function headings(): array
    {
        return [
            'ID',
            'Prénom',
            'Nom',
            'Date de naissance',
            'Âge',
            'Sexe',
            'Téléphone',
            'Email',
            'Adresse',
            'Ville',
            'Code postal',
            'Province',
            'Ceinture actuelle',
            'Statut',
            'Date inscription',
            'Dernière présence',
            'Contact urgence - Nom',
            'Contact urgence - Tél',
            'Contact urgence - Relation',
            'Allergies/Conditions',
            'Consentement photos',
            'Consentement communications',
            'Notes instructeur',
            'Notes admin',
        ];
    }

    /**
     * Mapping des données pour chaque ligne
     */
    public function map($membre): array
    {
        return [
            $membre->id,
            $membre->prenom,
            $membre->nom,
            $membre->date_naissance?->format('Y-m-d'),
            $membre->age,
            $this->formatSexe($membre->sexe),
            $membre->telephone,
            $membre->user?->email ?? $membre->email,
            $membre->adresse,
            $membre->ville,
            $membre->code_postal,
            $membre->province ?? 'QC',
            $membre->ceintureActuelle?->name ?? 'Aucune',
            $this->formatStatut($membre->statut),
            $membre->date_inscription?->format('Y-m-d'),
            $membre->date_derniere_presence?->format('Y-m-d'),
            $membre->contact_urgence_nom,
            $membre->contact_urgence_telephone,
            $membre->contact_urgence_relation,
            $membre->allergies ? implode(', ', json_decode($membre->allergies, true) ?: []) : '',
            $membre->consentement_photos ? 'Oui' : 'Non',
            $membre->consentement_communications ? 'Oui' : 'Non',
            $membre->notes_instructeur,
            $membre->notes_admin,
        ];
    }

    /**
     * Styles pour l'Excel
     */
    public function styles(Worksheet $sheet)
    {
        // Ligne d'entête en gras avec fond gris
        $sheet->getStyle('A1:X1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 11,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FFE0E0E0'],
            ],
            'borders' => [
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ]);

        // Auto-size des colonnes
        foreach (range('A', 'X') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Freeze première ligne
        $sheet->freezePane('A2');

        return [];
    }

    /**
     * Formatage du sexe
     */
    private function formatSexe(?string $sexe): string
    {
        return match($sexe) {
            'M' => 'Masculin',
            'F' => 'Féminin',
            default => 'Autre',
        };
    }

    /**
     * Formatage du statut
     */
    private function formatStatut(?string $statut): string
    {
        return match($statut) {
            'actif' => 'Actif',
            'inactif' => 'Inactif',
            'suspendu' => 'Suspendu',
            default => 'Inconnu',
        };
    }
}
