<?php

namespace App\Filament\Admin\Resources\CoursResource\Pages;

use App\Filament\Admin\Resources\CoursResource;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ManageHoraires extends ManageRelatedRecords
{
    protected static string $resource = CoursResource::class;
    protected static string $relationship = 'horaires';
    protected static ?string $navigationIcon = 'heroicon-o-clock';

    public static function getNavigationLabel(): string
    {
        return 'Horaires';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('⏰ Nouvel Horaire')
                    ->description('Définir un créneau récurrent pour ce cours')
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
                                    ->placeholder('Ex: Dojo Principal, Salle 2')
                                    ->helperText('Optionnel'),
                            ]),

                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TimePicker::make('heure_debut')
                                    ->label('Heure de début')
                                    ->required()
                                    ->seconds(false)
                                    ->minutesStep(15),

                                Forms\Components\TimePicker::make('heure_fin')
                                    ->label('Heure de fin')
                                    ->required()
                                    ->seconds(false)
                                    ->minutesStep(15)
                                    ->after('heure_debut'),
                            ]),

                        Forms\Components\Select::make('instructeur_id')
                            ->label('Instructeur')
                            ->searchable()
                            ->options(function () {
                                $ecoleId = auth()->user()->ecole_id;
                                return User::where('ecole_id', $ecoleId)
                                    ->whereHas('roles', function ($query) {
                                        $query->whereIn('name', ['instructeur', 'admin', 'gestionnaire']);
                                    })
                                    ->pluck('nom_complet', 'id');
                            })
                            ->helperText('Instructeur par défaut pour ce créneau'),

                        Forms\Components\Toggle::make('actif')
                            ->label('Horaire actif')
                            ->default(true)
                            ->helperText('Désactiver temporairement ce créneau'),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('jour_nom')
            ->columns([
                Tables\Columns\TextColumn::make('jour_emoji')
                    ->label('')
                    ->size('lg'),

                Tables\Columns\TextColumn::make('jour_nom')
                    ->label('Jour')
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('horaire_format')
                    ->label('Horaire')
                    ->color('info'),

                Tables\Columns\TextColumn::make('salle')
                    ->label('Salle')
                    ->placeholder('Non définie'),

                Tables\Columns\TextColumn::make('instructeur.nom_complet')
                    ->label('Instructeur')
                    ->placeholder('Non assigné'),

                Tables\Columns\IconColumn::make('actif')
                    ->label('Actif')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('actif')
                    ->label('Statut')
                    ->placeholder('Tous')
                    ->trueLabel('Actifs')
                    ->falseLabel('Inactifs'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Ajouter un horaire')
                    ->icon('heroicon-o-plus'),
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
