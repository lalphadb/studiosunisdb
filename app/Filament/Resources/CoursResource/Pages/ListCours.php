<?php

namespace App\Filament\Resources\CoursResource\Pages;

use App\Filament\Resources\CoursResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListCours extends ListRecords
{
    protected static string $resource = CoursResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Nouveau cours')
                ->icon('heroicon-o-plus'),
        ];
    }

    public function getTabs(): array
    {
        $baseQuery = auth()->user()?->hasRole('super-admin') 
            ? \App\Models\Cours::query()
            : \App\Models\Cours::where('ecole_id', auth()->user()?->ecole_id);

        return [
            'tous' => Tab::make('Tous les cours')
                ->badge(fn () => (clone $baseQuery)->count()),

            'reguliers' => Tab::make('Réguliers')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('type', 'regulier'))
                ->badge(fn () => (clone $baseQuery)->where('type', 'regulier')->count())
                ->badgeColor('success'),

            'speciaux' => Tab::make('Spéciaux')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('type', 'special'))
                ->badge(fn () => (clone $baseQuery)->where('type', 'special')->count())
                ->badgeColor('warning'),

            'seminaires' => Tab::make('Séminaires')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('type', 'seminaire'))
                ->badge(fn () => (clone $baseQuery)->where('type', 'seminaire')->count())
                ->badgeColor('info'),
        ];
    }
}
