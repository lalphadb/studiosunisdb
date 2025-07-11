<?php

namespace App\Filament\Resources\EcoleResource\Pages;

use App\Filament\Resources\EcoleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListEcoles extends ListRecords
{
    protected static string $resource = EcoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Nouvelle école')
                ->icon('heroicon-o-plus'),
        ];
    }

    /**
     * Onglets pour filtrer les écoles
     */
    public function getTabs(): array
    {
        return [
            'toutes' => Tab::make('Toutes les écoles')
                ->badge(fn () => \App\Models\Ecole::count()),

            'actives' => Tab::make('Actives')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('actif', true))
                ->badge(fn () => \App\Models\Ecole::where('actif', true)->count())
                ->badgeColor('success'),

            'inactives' => Tab::make('Inactives')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('actif', false))
                ->badge(fn () => \App\Models\Ecole::where('actif', false)->count())
                ->badgeColor('danger'),
        ];
    }

    public function getTitle(): string
    {
        return 'Gestion des Écoles StudiosUnisDB';
    }
}
