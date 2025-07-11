<?php

namespace App\Filament\Resources\EcoleResource\Pages;

use App\Filament\Resources\EcoleResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists;
use Filament\Infolists\Infolist;

class ViewEcole extends ViewRecord
{
    protected static string $resource = EcoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->label('Modifier')
                ->icon('heroicon-o-pencil'),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Informations de l\'école')
                    ->schema([
                        Infolists\Components\TextEntry::make('nom')
                            ->label('Nom')
                            ->size(Infolists\Components\TextEntry\TextEntrySize::Large)
                            ->weight('bold'),

                        Infolists\Components\TextEntry::make('code')
                            ->label('Code')
                            ->badge()
                            ->color('primary'),

                        Infolists\Components\IconEntry::make('actif')
                            ->label('Statut')
                            ->boolean()
                            ->trueIcon('heroicon-o-check-circle')
                            ->falseIcon('heroicon-o-x-circle')
                            ->trueColor('success')
                            ->falseColor('danger'),
                    ])
                    ->columns(3),

                Infolists\Components\Section::make('Adresse')
                    ->schema([
                        Infolists\Components\TextEntry::make('adresse')
                            ->label('Adresse'),

                        Infolists\Components\TextEntry::make('ville')
                            ->label('Ville'),

                        Infolists\Components\TextEntry::make('code_postal')
                            ->label('Code postal'),
                    ])
                    ->columns(3),

                Infolists\Components\Section::make('Contact')
                    ->schema([
                        Infolists\Components\TextEntry::make('telephone')
                            ->label('Téléphone')
                            ->placeholder('Non renseigné'),

                        Infolists\Components\TextEntry::make('email')
                            ->label('Email')
                            ->placeholder('Non renseigné'),
                    ])
                    ->columns(2),

                Infolists\Components\Section::make('Statistiques')
                    ->schema([
                        Infolists\Components\TextEntry::make('users_count')
                            ->label('Nombre d\'utilisateurs')
                            ->getStateUsing(fn ($record) => $record->users()->count())
                            ->badge()
                            ->color('success'),

                        Infolists\Components\TextEntry::make('cours_count')
                            ->label('Nombre de cours')
                            ->getStateUsing(fn ($record) => $record->cours()->count())
                            ->badge()
                            ->color('info'),

                        Infolists\Components\TextEntry::make('created_at')
                            ->label('Créée le')
                            ->dateTime('d/m/Y à H:i'),
                    ])
                    ->columns(3),
            ]);
    }

    public function getTitle(): string
    {
        return 'École ' . $this->record->nom;
    }
}
