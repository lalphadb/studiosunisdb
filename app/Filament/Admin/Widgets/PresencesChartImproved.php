<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Presence;
use Illuminate\Support\Facades\DB;

class PresencesChartImproved extends ChartWidget
{
    protected static ?string $heading = 'Évolution des Présences';
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $user = auth()->user();
        
        // Derniers 30 jours
        $data = collect(range(29, 0))->map(function ($daysAgo) use ($user) {
            $date = now()->subDays($daysAgo);
            
            $query = Presence::whereDate('created_at', $date);
            
            // Si pas super-admin, filtrer par école
            if (!$user->hasRole('super-admin')) {
                $query->whereHas('user', function ($q) use ($user) {
                    $q->where('ecole_id', $user->ecole_id);
                });
            }
            
            return [
                'date' => $date->format('d/m'),
                'presents' => $query->where('status', 'present')->count(),
                'absents' => $query->where('status', 'absent')->count(),
                'retards' => $query->where('status', 'retard')->count(),
            ];
        });

        return [
            'datasets' => [
                [
                    'label' => 'Présents',
                    'data' => $data->pluck('presents')->toArray(),
                    'backgroundColor' => 'rgb(34, 197, 94)',
                    'borderColor' => 'rgb(34, 197, 94)',
                ],
                [
                    'label' => 'Absents',
                    'data' => $data->pluck('absents')->toArray(),
                    'backgroundColor' => 'rgb(239, 68, 68)',
                    'borderColor' => 'rgb(239, 68, 68)',
                ],
                [
                    'label' => 'Retards',
                    'data' => $data->pluck('retards')->toArray(),
                    'backgroundColor' => 'rgb(245, 158, 11)',
                    'borderColor' => 'rgb(245, 158, 11)',
                ],
            ],
            'labels' => $data->pluck('date')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                ],
            ],
        ];
    }
}
