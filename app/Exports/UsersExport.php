<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class UsersExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $users;

    public function __construct($users)
    {
        $this->users = $users;
    }

    public function collection()
    {
        return $this->users;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nom complet',
            'Email',
            'Téléphone',
            'Date naissance',
            'Sexe',
            'Adresse',
            'Ville',
            'Code postal',
            'École',
            'Ceinture actuelle',
            'Rôles',
            'Actif',
            'Date inscription',
            'Contact urgence',
            'Téléphone urgence',
        ];
    }

    public function map($user): array
    {
        return [
            $user->id,
            $user->name,
            $user->email,
            $user->telephone,
            $user->date_naissance?->format('d/m/Y'),
            $user->sexe,
            $user->adresse,
            $user->ville,
            $user->code_postal,
            $user->ecole?->nom,
            $user->ceinture_actuelle?->nom,
            $user->roles->pluck('name')->implode(', '),
            $user->active ? 'Oui' : 'Non',
            $user->date_inscription?->format('d/m/Y'),
            $user->contact_urgence_nom,
            $user->contact_urgence_telephone,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
