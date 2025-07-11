<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Presence;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class PresencesChart extends ChartWidget
{
    protected static ?string $heading = 'Présences (30 derniers jours)';
    
    protected static ?int $sort = 2;
    
    protected static string $color = 'success';

    protected function getData(): array
    {
        $data = [];
        $labels = [];
        
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $labels[] = $date->format('d/m');
            
            $query = Presence::whereHas('sessionCours', function ($q) use ($date) {
                $q->whereDate('date_debut', $date);
            })->where('status', 'present');
            
            // Appliquer le filtre école si l'utilisateur n'est pas super-admin
            if (!auth()->user()->hasRole('super-admin')) {
                $query->where('ecole_id', auth()->user()->ecole_id);
            }
            
            $data[] = $query->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Présences',
                    'data' => $data,
                    'backgroundColor' => 'rgba(34, 197, 94, 0.1)',
                    'borderColor' => 'rgb(34, 197, 94)',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
