<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\User;
use App\Models\Cours;
use App\Models\Ecole;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;
    
    // DÉSACTIVER LE POLLING AUTOMATIQUE
    protected static ?string $pollingInterval = null;
    protected static bool $isLazy = false;

    protected function getStats(): array
    {
        $user = auth()->user();
        
        if ($user && $user->hasRole('superadmin')) {
            $totalEcoles = Ecole::where('actif', true)->count();
            $totalMembres = User::where('actif', true)->count();
            $coursActifs = Cours::where('actif', true)->count();
            
            return [
                Stat::make('Écoles Actives', $totalEcoles)
                    ->description('Total du réseau')
                    ->descriptionIcon('heroicon-m-building-office')
                    ->color('primary'),
                    
                Stat::make('Membres Actifs', $totalMembres)
                    ->description('Toutes écoles')
                    ->descriptionIcon('heroicon-m-users')
                    ->color('success'),
                    
                Stat::make('Cours Actifs', $coursActifs)
                    ->description('Programmes disponibles')
                    ->descriptionIcon('heroicon-m-academic-cap')
                    ->color('info'),
            ];
        }
        
        // Stats pour admin école
        $ecoleId = $user->ecole_id ?? null;
        if (!$ecoleId) return [];
        
        $membresEcole = User::where('ecole_id', $ecoleId)->where('actif', true)->count();
        $coursEcole = Cours::where('ecole_id', $ecoleId)->where('actif', true)->count();
        
        return [
            Stat::make('Membres Actifs', $membresEcole)
                ->description('Dans votre école')
                ->descriptionIcon('heroicon-m-users')
                ->color('success'),
                
            Stat::make('Cours Actifs', $coursEcole)
                ->description('Programmes disponibles')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('info'),
        ];
    }
}
