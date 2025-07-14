<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;
    protected static ?string $pollingInterval = null; // Pas de polling automatique

    protected function getStats(): array
    {
        return [
            Stat::make('🥋 StudiosDB', 'v4.1.10.2')
                ->description('Système de gestion')
                ->descriptionIcon('heroicon-m-computer-desktop')
                ->color('success'),
                
            Stat::make('🚀 Status', 'Opérationnel')
                ->description('Serveur actif')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('info'),
                
            Stat::make('📊 Dashboard', 'Prêt')
                ->description('Interface admin')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('warning'),
        ];
    }
}
