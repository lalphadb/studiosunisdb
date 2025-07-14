<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\User;
use App\Models\Cours;
use App\Models\Presence;
use App\Models\Paiement;
use App\Models\Seminaire;
use Illuminate\Support\Facades\DB;

class AdvancedStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $user = auth()->user();
        
        if ($user && $user->hasRole('super-admin')) {
            return $this->getSuperAdminStats();
        }
        
        return $this->getEcoleStats();
    }
    
    private function getSuperAdminStats(): array
    {
        $membresActifs = User::where('actif', true)->count();
        $totalEcoles = \App\Models\Ecole::where('actif', true)->count();
        $coursActifs = Cours::where('actif', true)->count();
        $presencesMois = Presence::whereMonth('created_at', now()->month)->count();
        $revenusMois = Paiement::whereMonth('date_paiement', now()->month)
            ->where('statut', 'complete')
            ->sum('montant');
        $seminairesVenir = Seminaire::where('date_debut', '>', now())->count();
            
        return [
            Stat::make('Écoles Actives', $totalEcoles)
                ->description('Total du réseau')
                ->descriptionIcon('heroicon-m-building-office')
                ->color('primary'),
                
            Stat::make('Membres Actifs', $membresActifs)
                ->description('Toutes écoles confondues')
                ->descriptionIcon('heroicon-m-users')
                ->color('success'),
                
            Stat::make('Cours Actifs', $coursActifs)
                ->description('Programmes disponibles')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('info'),
                
            Stat::make('Présences ce mois', $presencesMois)
                ->description('Participation ' . now()->format('M Y'))
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('warning'),
                
            Stat::make('Revenus du mois', number_format($revenusMois, 2) . ' $')
                ->description('Facturation ' . now()->format('M Y'))
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),
                
            Stat::make('Séminaires à venir', $seminairesVenir)
                ->description('Événements planifiés')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('info'),
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
            ->count();
            
        $revenusMois = Paiement::whereHas('user', function($query) use ($ecoleId) {
                $query->where('ecole_id', $ecoleId);
            })
            ->whereMonth('date_paiement', now()->month)
            ->where('statut', 'complete')
            ->sum('montant');
        
        $tauxPresence = $presencesMois > 0 ? 
            Presence::whereHas('sessionCours.cours', function($query) use ($ecoleId) {
                $query->where('ecole_id', $ecoleId);
            })
            ->whereMonth('created_at', now()->month)
            ->where('status', 'present')
            ->count() / $presencesMois * 100 : 0;
            
        return [
            Stat::make('Membres Actifs', $membresActifs)
                ->description('Dans votre école')
                ->descriptionIcon('heroicon-m-users')
                ->color('success'),
                
            Stat::make('Cours Actifs', $coursActifs)
                ->description('Programmes disponibles')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('info'),
                
            Stat::make('Présences ce mois', $presencesMois)
                ->description('Participations enregistrées')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('warning'),
                
            Stat::make('Taux de présence', round($tauxPresence, 1) . '%')
                ->description('Assiduité mensuelle')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color($tauxPresence >= 80 ? 'success' : ($tauxPresence >= 60 ? 'warning' : 'danger')),
                
            Stat::make('Revenus du mois', number_format($revenusMois, 2) . ' $')
                ->description('Facturation ' . now()->format('M Y'))
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),
        ];
    }
}
