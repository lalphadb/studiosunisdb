<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class UsersExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $ecoleId;

    public function __construct($ecoleId = null)
    {
        $this->ecoleId = $ecoleId;
    }

    public function collection()
    {
        $query = User::with(['ecole', 'roles']);
        
        if ($this->ecoleId) {
            $query->where('ecole_id', $this->ecoleId);
        }
        
        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Code Utilisateur',
            'Nom Complet',
            'Email',
            'École',
            'Rôle',
            'Téléphone',
            'Date Naissance',
            'Statut',
            'Date Inscription',
        ];
    }

    public function map($user): array
    {
        return [
            $user->code_utilisateur,
            $user->nom . ' ' . $user->prenom,
            $user->email,
            $user->ecole?->nom ?? 'N/A',
            $user->roles->pluck('name')->implode(', '),
            $user->telephone,
            $user->date_naissance?->format('d/m/Y'),
            $user->actif ? 'Actif' : 'Inactif',
            $user->created_at->format('d/m/Y'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
