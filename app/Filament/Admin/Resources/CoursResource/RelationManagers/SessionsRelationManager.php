<?php

namespace App\Filament\Admin\Resources\CoursResource\RelationManagers;

use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class SessionsRelationManager extends RelationManager
{
    protected static string $relationship = 'sessions';
    protected static ?string $title = 'Sessions du cours';
    protected static ?string $modelLabel = 'session';
    protected static ?string $pluralModelLabel = 'sessions';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('📅 Session')
                    ->schema([
                        Forms\Components\DatePicker::make('date')
                            ->label('Date')
                            ->required(),

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

                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('salle')
                                    ->label('Salle'),

                                Forms\Components\Select::make('instructeur_id')
                                    ->label('Instructeur')
                                    ->searchable()
                                    ->options(function () {
                                        $ecoleId = auth()->user()->ecole_id;
                                        return User::where('ecole_id', $ecoleId)
                                            ->pluck('nom', 'id');
                                    }),
                            ]),

                        Forms\Components\Select::make('statut')
                            ->label('Statut')
                            ->required()
                            ->options([
                                'planifie' => '⏳ Planifiée',
                                'en_cours' => '▶️ En cours',
                                'complete' => '✅ Terminée',
                                'annule' => '❌ Annulée',
                            ])
                            ->default('planifie'),

                        Forms\Components\Textarea::make('notes')
                            ->label('Notes')
                            ->rows(2),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('date')
            ->columns([
                Tables\Columns\TextColumn::make('date')
                    ->label('Date')
                    ->date('d/m/Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('heure_debut')
                    ->label('Début')
                    ->time('H:i'),

                Tables\Columns\TextColumn::make('heure_fin')
                    ->label('Fin')
                    ->time('H:i'),

                Tables\Columns\BadgeColumn::make('statut')
                    ->label('Statut')
                    ->colors([
                        'gray' => 'planifie',
                        'warning' => 'en_cours',
                        'success' => 'complete',
                        'danger' => 'annule',
                    ])
                    ->icons([
                        'heroicon-o-clock' => 'planifie',
                        'heroicon-o-play' => 'en_cours',
                        'heroicon-o-check-circle' => 'complete',
                        'heroicon-o-x-circle' => 'annule',
                    ]),

                Tables\Columns\TextColumn::make('instructeur.nom')
                    ->label('Instructeur')
                    ->placeholder('Non assigné'),

                Tables\Columns\TextColumn::make('salle')
                    ->label('Salle')
                    ->placeholder('Non définie'),

                Tables\Columns\TextColumn::make('presences_count')
                    ->label('Présences')
                    ->counts('presences')
                    ->suffix(' présent(s)'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('statut')
                    ->options([
                        'planifie' => 'Planifiée',
                        'en_cours' => 'En cours',
                        'complete' => 'Terminée',
                        'annule' => 'Annulée',
                    ]),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Ajouter session'),
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
            ->defaultSort('date', 'desc');
    }
}
