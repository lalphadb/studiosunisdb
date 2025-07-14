<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\ChartWidget;

class PresencesChart extends ChartWidget
{
    protected static ?string $heading = 'Graphique des Présences';
    protected static ?int $sort = 2;
    protected static ?string $pollingInterval = null; // Pas de polling

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'Présences cette semaine',
                    'data' => [12, 19, 15, 20, 18, 22, 25],
                    'backgroundColor' => [
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(255, 205, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                        'rgba(199, 199, 199, 0.2)',
                    ],
                    'borderColor' => [
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 99, 132, 1)',
                        'rgba(255, 205, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)',
                        'rgba(199, 199, 199, 1)',
                    ],
                    'borderWidth' => 1,
                ],
            ],
            'labels' => ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
