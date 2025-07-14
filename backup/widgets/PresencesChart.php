<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\ChartWidget;

class PresencesChart extends ChartWidget
{
    protected static ?string $heading = 'Présences (30 derniers jours)';
    protected static ?int $sort = 2;
    protected static string $color = 'success';
    
    // DÉSACTIVER LE POLLING AUTOMATIQUE
    protected static ?string $pollingInterval = null;
    protected static bool $isLazy = false;

    protected function getData(): array
    {
        // Données statiques pour éviter les requêtes répétées
        return [
            'datasets' => [
                [
                    'label' => 'Présences',
                    'data' => [0, 0, 0, 0, 0, 0, 0],
                    'backgroundColor' => 'rgb(34, 197, 94)',
                ],
            ],
            'labels' => ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
