<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\User;
use App\Models\Cours;
use App\Models\Presence;
use App\Models\Paiement;
use App\Models\Ecole;
use Illuminate\Support\Facades\DB;

class StatsOverviewImproved extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $user = auth()->user();
        
        if ($user && $user->hasRole('superadmin')) {
            return $this->getSuperAdminStats();
        }
        
        return $this->getEcoleStats();
    }
    
    private function getSuperAdminStats(): array
    {
        $totalEcoles = Ecole::where('actif', true)->count();
        $totalMembres = User::where('actif', true)->count();
        $coursActifs = Cours::where('actif', true)->count();
        
        $presencesMois = Presence::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
            
        $tauxPresenceMois = $presencesMois > 0 ? 
            Presence::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->where('status', 'present')
                ->count() / $presencesMois * 100 : 0;
            
        $revenusMois = Paiement::whereMonth('date_paiement', now()->month)
            ->whereYear('date_paiement', now()->year)
            ->where('statut', 'complete')
            ->sum('montant');
            
        return [
            Stat::make('Écoles Actives', $totalEcoles)
                ->description('Total du réseau')
                ->descriptionIcon('heroicon-m-building-office')
                ->color('primary'),
                
            Stat::make('Membres Actifs', $totalMembres)
                ->description('Toutes écoles confondues')
                ->descriptionIcon('heroicon-m-users')
                ->color('success'),
                
            Stat::make('Cours Actifs', $coursActifs)
                ->description('Programmes disponibles')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('info'),
                
            Stat::make('Taux de Présence', round($tauxPresenceMois, 1) . '%')
                ->description('Assiduité ce mois')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color($tauxPresenceMois >= 80 ? 'success' : ($tauxPresenceMois >= 60 ? 'warning' : 'danger')),
                
            Stat::make('Revenus du Mois', number_format($revenusMois, 2, ',', ' ') . ' $')
                ->description('Facturation ' . now()->format('M Y'))
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),
        ];
    }
    
    private function getEcoleStats(): array
    {
        $user = auth()->user();
        $ecoleId = $user->ecole_id ?? null;
        
        if (!$ecoleId) {
            return [];
        }
        
        $membresActifs = User::where('ecole_id', $ecoleId)
            ->where('actif', true)
            ->count();
            
        $coursActifs = Cours::where('ecole_id', $ecoleId)
            ->where('actif', true)
            ->count();
            
        $presencesMois = Presence::whereHas('sessionCours.cours', function($query) use ($ecoleId) {
                $query->where('ecole_id', $ecoleId);
            })
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
            
        $tauxPresenceMois = $presencesMois > 0 ? 
            Presence::whereHas('sessionCours.cours', function($query) use ($ecoleId) {
                $query->where('ecole_id', $ecoleId);
            })
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->where('status', 'present')
            ->count() / $presencesMois * 100 : 0;
            
        $revenusMois = Paiement::whereHas('user', function($query) use ($ecoleId) {
                $query->where('ecole_id', $ecoleId);
            })
            ->whereMonth('date_paiement', now()->month)
            ->whereYear('date_paiement', now()->year)
            ->where('statut', 'complete')
            ->sum('montant');
        
        $nouveauxMembres = User::where('ecole_id', $ecoleId)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
            
        return [
            Stat::make('Membres Actifs', $membresActifs)
                ->description('Dans votre école')
                ->descriptionIcon('heroicon-m-users')
                ->color('success'),
                
            Stat::make('Nouveaux ce Mois', $nouveauxMembres)
                ->description('Nouvelles inscriptions')
                ->descriptionIcon('heroicon-m-user-plus')
                ->color('info'),
                
            Stat::make('Cours Actifs', $coursActifs)
                ->description('Programmes disponibles')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('primary'),
                
            Stat::make('Taux de Présence', round($tauxPresenceMois, 1) . '%')
                ->description('Assiduité mensuelle')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color($tauxPresenceMois >= 80 ? 'success' : ($tauxPresenceMois >= 60 ? 'warning' : 'danger')),
                
            Stat::make('Revenus du Mois', number_format($revenusMois, 2, ',', ' ') . ' $')
                ->description('Facturation ' . now()->format('M Y'))
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),
        ];
    }
}
