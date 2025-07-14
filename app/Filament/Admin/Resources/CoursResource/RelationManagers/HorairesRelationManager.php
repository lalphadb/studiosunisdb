<?php

namespace App\Filament\Admin\Resources\CoursResource\RelationManagers;

use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class HorairesRelationManager extends RelationManager
{
    protected static string $relationship = 'horaires';
    protected static ?string $title = 'Horaires du cours';
    protected static ?string $modelLabel = 'horaire';
    protected static ?string $pluralModelLabel = 'horaires';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('⏰ Horaire')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('jour_semaine')
                                    ->label('Jour de la semaine')
                                    ->required()
                                    ->options([
                                        1 => '📅 Lundi',
                                        2 => '📅 Mardi',
                                        3 => '📅 Mercredi',
                                        4 => '📅 Jeudi',
                                        5 => '📅 Vendredi',
                                        6 => '🎯 Samedi',
                                        7 => '🎯 Dimanche',
                                    ]),

                                Forms\Components\TextInput::make('salle')
                                    ->label('Salle')
                                    ->placeholder('Ex: Dojo Principal'),
                            ]),

                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TimePicker::make('heure_debut')
                                    ->label('Heure de début')
                                    ->required()
                                    ->seconds(false),

                                Forms\Components\TimePicker::make('heure_fin')
                                    ->label('Heure de fin')
                                    ->required()
                                    ->seconds(false)
                                    ->after('heure_debut'),
                            ]),

                        Forms\Components\Select::make('instructeur_id')
                            ->label('Instructeur')
                            ->searchable()
                            ->options(function () {
                                $ecoleId = auth()->user()->ecole_id;
                                return User::where('ecole_id', $ecoleId)
                                    ->pluck('nom', 'id');
                            })
                            ->placeholder('Sélectionner un instructeur'),

                        Forms\Components\Toggle::make('actif')
                            ->label('Horaire actif')
                            ->default(true),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('jour_nom')
            ->columns([
                Tables\Columns\TextColumn::make('jour_nom')
                    ->label('Jour')
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('heure_debut')
                    ->label('Début')
                    ->time('H:i'),

                Tables\Columns\TextColumn::make('heure_fin')
                    ->label('Fin')
                    ->time('H:i'),

                Tables\Columns\TextColumn::make('salle')
                    ->label('Salle')
                    ->placeholder('Non définie'),

                Tables\Columns\TextColumn::make('instructeur.nom')
                    ->label('Instructeur')
                    ->placeholder('Non assigné'),

                Tables\Columns\IconColumn::make('actif')
                    ->label('Actif')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('actif')
                    ->label('Statut'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Ajouter horaire'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('jour_semaine');
    }
}
