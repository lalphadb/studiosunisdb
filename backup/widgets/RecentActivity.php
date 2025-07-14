<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class RecentActivity extends BaseWidget
{
    protected static ?string $heading = 'Nouveaux utilisateurs';
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 'full';
    
    // DÉSACTIVER LE POLLING AUTOMATIQUE
    protected static ?string $pollingInterval = null;
    protected static bool $isLazy = false;

    protected function getStats(): array
    {
        return [
            Stat::make('Activité', 'Normale')
                ->description('Système opérationnel')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
        ];
    }
}
