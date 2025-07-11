<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Nouvel utilisateur')
                ->icon('heroicon-o-plus'),
        ];
    }

    public function getTabs(): array
    {
        $baseQuery = auth()->user()?->hasRole('super-admin') 
            ? \App\Models\User::query()
            : \App\Models\User::where('ecole_id', auth()->user()?->ecole_id);

        return [
            'tous' => Tab::make('Tous')
                ->badge(fn () => (clone $baseQuery)->count()),

            'membres' => Tab::make('Membres')
                ->modifyQueryUsing(fn (Builder $query) => $query->role('membre'))
                ->badge(fn () => (clone $baseQuery)->role('membre')->count())
                ->badgeColor('success'),

            'instructeurs' => Tab::make('Instructeurs')
                ->modifyQueryUsing(fn (Builder $query) => $query->role('instructeur'))
                ->badge(fn () => (clone $baseQuery)->role('instructeur')->count())
                ->badgeColor('info'),

            'administratifs' => Tab::make('Administratifs')
                ->modifyQueryUsing(fn (Builder $query) => $query->role(['admin', 'gestionnaire']))
                ->badge(fn () => (clone $baseQuery)->role(['admin', 'gestionnaire'])->count())
                ->badgeColor('warning'),
        ];
    }

    public function getTitle(): string
    {
        return 'Gestion des Utilisateurs';
    }
}
