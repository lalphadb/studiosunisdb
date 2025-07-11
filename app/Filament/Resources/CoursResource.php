<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CoursResource\Pages;
use App\Models\Cours;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CoursResource extends Resource
{
    protected static ?string $model = Cours::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    
    protected static ?string $navigationGroup = 'Gestion École';
    
    protected static ?int $navigationSort = 2;
    
    protected static ?string $modelLabel = 'Cours';
    
    protected static ?string $pluralModelLabel = 'Cours';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informations du cours')
                    ->schema([
                        Forms\Components\TextInput::make('nom')
                            ->label('Nom du cours')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('description')
                            ->label('Description')
                            ->rows(3)
                            ->columnSpanFull(),
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\Select::make('type')
                                    ->label('Type de cours')
                                    ->options([
                                        'regulier' => 'Régulier',
                                        'special' => 'Spécial',
                                        'seminaire' => 'Séminaire',
                                    ])
                                    ->default('regulier')
                                    ->required(),
                                Forms\Components\TextInput::make('niveau_requis')
                                    ->label('Niveau requis')
                                    ->placeholder('Ex: Ceinture jaune')
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('duree_minutes')
                                    ->label('Durée (minutes)')
                                    ->numeric()
                                    ->default(60)
                                    ->required()
                                    ->minValue(15)
                                    ->maxValue(240),
                            ]),
                    ]),
                
                Forms\Components\Section::make('Capacité et restrictions')
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('capacite_max')
                                    ->label('Capacité maximale')
                                    ->numeric()
                                    ->default(20)
                                    ->minValue(1)
                                    ->maxValue(100),
                                Forms\Components\TextInput::make('age_minimum')
                                    ->label('Âge minimum')
                                    ->numeric()
                                    ->minValue(3)
                                    ->maxValue(99),
                                Forms\Components\TextInput::make('age_maximum')
                                    ->label('Âge maximum')
                                    ->numeric()
                                    ->minValue(3)
                                    ->maxValue(99),
                            ]),
                    ]),
                
                Forms\Components\Section::make('Tarification')
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('prix_mensuel')
                                    ->label('Prix mensuel ($)')
                                    ->numeric()
                                    ->prefix('$')
                                    ->minValue(0)
                                    ->step(0.01),
                                Forms\Components\TextInput::make('prix_seance')
                                    ->label('Prix par séance ($)')
                                    ->numeric()
                                    ->prefix('$')
                                    ->minValue(0)
                                    ->step(0.01),
                                Forms\Components\TextInput::make('prix_carte_10')
                                    ->label('Prix carte 10 séances ($)')
                                    ->numeric()
                                    ->prefix('$')
                                    ->minValue(0)
                                    ->step(0.01),
                            ]),
                    ]),
                
                Forms\Components\Section::make('Configuration')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\ColorPicker::make('couleur_calendrier')
                                    ->label('Couleur dans le calendrier')
                                    ->default('#3B82F6'),
                                Forms\Components\Toggle::make('inscription_requise')
                                    ->label('Inscription requise')
                                    ->default(true)
                                    ->helperText('Les étudiants doivent-ils s\'inscrire à ce cours?'),
                            ]),
                        Forms\Components\Toggle::make('actif')
                            ->label('Cours actif')
                            ->default(true)
                            ->columnSpanFull(),
                    ]),
                
                // École automatiquement assignée pour les admins
                Forms\Components\Hidden::make('ecole_id')
                    ->default(fn () => auth()->user()->ecole_id),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nom')
                    ->label('Nom du cours')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'regulier' => 'primary',
                        'special' => 'warning',
                        'seminaire' => 'success',
                    }),
                Tables\Columns\TextColumn::make('ecole.nom')
                    ->label('École')
                    ->sortable()
                    ->visible(fn () => auth()->user()->hasRole('super-admin')),
                Tables\Columns\TextColumn::make('niveau_requis')
                    ->label('Niveau')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('capacite_max')
                    ->label('Capacité')
                    ->suffix(' places'),
                Tables\Columns\TextColumn::make('inscriptions_count')
                    ->label('Inscrits')
                    ->counts('inscriptions')
                    ->badge()
                    ->color(fn ($record) => 
                        $record->inscriptions_count >= $record->capacite_max ? 'danger' : 'success'
                    ),
                Tables\Columns\TextColumn::make('duree_minutes')
                    ->label('Durée')
                    ->suffix(' min')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('prix_mensuel')
                    ->label('Prix/mois')
                    ->money('CAD')
                    ->sortable(),
                Tables\Columns\ColorColumn::make('couleur_calendrier')
                    ->label('Couleur')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('actif')
                    ->label('Actif')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Type de cours')
                    ->options([
                        'regulier' => 'Régulier',
                        'special' => 'Spécial',
                        'seminaire' => 'Séminaire',
                    ]),
                Tables\Filters\TernaryFilter::make('actif')
                    ->label('Statut')
                    ->boolean()
                    ->trueLabel('Actifs seulement')
                    ->falseLabel('Inactifs seulement')
                    ->native(false),
                Tables\Filters\Filter::make('complet')
                    ->label('Cours complets')
                    ->query(fn (Builder $query): Builder => 
                        $query->whereColumn('inscriptions_count', '>=', 'capacite_max')
                    ),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('duplicate')
                    ->label('Dupliquer')
                    ->icon('heroicon-o-document-duplicate')
                    ->action(function (Cours $record): void {
                        $newCours = $record->replicate();
                        $newCours->nom = $record->nom . ' (Copie)';
                        $newCours->save();
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Dupliquer le cours')
                    ->modalDescription('Voulez-vous créer une copie de ce cours?')
                    ->modalSubmitActionLabel('Oui, dupliquer'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->requiresConfirmation(),
                ]),
            ])
            ->defaultSort('nom');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCours::route('/'),
            'create' => Pages\CreateCours::route('/create'),
            'view' => Pages\ViewCours::route('/{record}'),
            'edit' => Pages\EditCours::route('/{record}/edit'),
        ];
    }

    // Filtre multi-tenant
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()
            ->withCount('inscriptions');
        
        // Admin voit seulement les cours de son école
        if (!auth()->user()->hasRole('super-admin')) {
            $query->where('ecole_id', auth()->user()->ecole_id);
        }
        
        return $query;
    }

    // Permissions - Les admins peuvent tout faire pour leur école
    public static function canViewAny(): bool
    {
        return auth()->user()->hasAnyRole(['super-admin', 'admin', 'gestionnaire']);
    }

    public static function canCreate(): bool
    {
        return auth()->user()->hasAnyRole(['super-admin', 'admin']);
    }

    public static function canEdit($record): bool
    {
        return auth()->user()->hasAnyRole(['super-admin', 'admin']);
    }

    public static function canDelete($record): bool
    {
        // Empêcher la suppression si des inscriptions existent
        if ($record->inscriptions()->exists()) {
            return false;
        }
        
        return auth()->user()->hasAnyRole(['super-admin', 'admin']);
    }
}
