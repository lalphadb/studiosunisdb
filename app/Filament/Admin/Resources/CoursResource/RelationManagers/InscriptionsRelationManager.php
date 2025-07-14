<?php

namespace App\Filament\Admin\Resources\CoursResource\RelationManagers;

use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class InscriptionsRelationManager extends RelationManager
{
    protected static string $relationship = 'inscriptions';
    protected static ?string $title = 'Inscriptions au cours';
    protected static ?string $modelLabel = 'inscription';
    protected static ?string $pluralModelLabel = 'inscriptions';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('👥 Inscription')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label('Membre')
                            ->required()
                            ->searchable()
                            ->options(function () {
                                $ecoleId = auth()->user()->ecole_id;
                                return User::where('ecole_id', $ecoleId)
                                    ->whereHas('roles', function ($query) {
                                        $query->where('name', 'membre');
                                    })
                                    ->pluck('nom', 'id');
                            }),

                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\DatePicker::make('date_inscription')
                                    ->label('Date d\'inscription')
                                    ->required()
                                    ->default(now()),

                                Forms\Components\Select::make('statut')
                                    ->label('Statut')
                                    ->required()
                                    ->options([
                                        'active' => '✅ Active',
                                        'suspendue' => '⏸️ Suspendue',
                                        'terminee' => '🏁 Terminée',
                                    ])
                                    ->default('active'),
                            ]),

                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('type_paiement')
                                    ->label('Type de paiement')
                                    ->required()
                                    ->options([
                                        'mensuel' => '💳 Mensuel',
                                        'seance' => '💰 Par séance',
                                        'carte' => '🎫 Carte 10 cours',
                                    ])
                                    ->default('mensuel'),

                                Forms\Components\TextInput::make('tarif_applique')
                                    ->label('Tarif appliqué')
                                    ->numeric()
                                    ->prefix('$')
                                    ->step(0.01),
                            ]),

                        Forms\Components\Textarea::make('notes')
                            ->label('Notes')
                            ->rows(2),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('user.nom')
            ->columns([
                Tables\Columns\TextColumn::make('user.nom')
                    ->label('Membre')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('date_inscription')
                    ->label('Inscrit le')
                    ->date('d/m/Y')
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('statut')
                    ->label('Statut')
                    ->colors([
                        'success' => 'active',
                        'warning' => 'suspendue',
                        'gray' => 'terminee',
                    ]),

                Tables\Columns\TextColumn::make('type_paiement')
                    ->label('Paiement')
                    ->formatStateUsing(fn (string $state): string => match($state) {
                        'mensuel' => '💳 Mensuel',
                        'seance' => '💰 Séance',
                        'carte' => '🎫 Carte',
                        default => $state
                    }),

                Tables\Columns\TextColumn::make('tarif_applique')
                    ->label('Tarif')
                    ->money('CAD'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('statut')
                    ->options([
                        'active' => 'Active',
                        'suspendue' => 'Suspendue',
                        'terminee' => 'Terminée',
                    ]),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Inscrire un membre'),
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
            ->defaultSort('date_inscription', 'desc');
    }
}
