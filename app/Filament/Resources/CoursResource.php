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
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TimePicker;
use Filament\Tables\Filters\SelectFilter;

class CoursResource extends Resource
{
    protected static ?string $model = Cours::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    
    protected static ?string $navigationLabel = 'Cours';
    
    protected static ?string $modelLabel = 'Cours';
    
    protected static ?string $pluralModelLabel = 'Cours';
    
    protected static ?string $navigationGroup = 'Gestion École';
    
    protected static ?int $navigationSort = 2;

    /**
     * Scope multi-tenant : filtrer selon l'école de l'utilisateur connecté
     */
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        // Super-admin peut voir tous les cours
        if (auth()->user()?->hasRole('super-admin')) {
            return $query;
        }

        // Autres utilisateurs voient seulement les cours de leur école
        return $query->where('ecole_id', auth()->user()?->ecole_id);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informations de base')
                    ->schema([
                        TextInput::make('nom')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Ex: Karaté Enfants 6-8 ans'),

                        Textarea::make('description')
                            ->maxLength(1000)
                            ->rows(3)
                            ->placeholder('Description du cours...'),

                        Select::make('ecole_id')
                            ->label('École')
                            ->relationship('ecole', 'nom')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->visible(fn () => auth()->user()?->hasRole('super-admin'))
                            ->default(fn () => auth()->user()?->ecole_id),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Type et niveau')
                    ->schema([
                        Select::make('type')
                            ->options([
                                'regulier' => 'Régulier',
                                'special' => 'Spécial',
                                'seminaire' => 'Séminaire',
                            ])
                            ->default('regulier')
                            ->required(),

                        TextInput::make('niveau_requis')
                            ->label('Niveau requis')
                            ->maxLength(255)
                            ->placeholder('Ex: Ceinture jaune minimum'),

                        TextInput::make('age_minimum')
                            ->label('Âge minimum')
                            ->numeric()
                            ->minValue(3)
                            ->maxValue(99),

                        TextInput::make('age_maximum')
                            ->label('Âge maximum')
                            ->numeric()
                            ->minValue(3)
                            ->maxValue(99),
                    ])
                    ->columns(4),

                Forms\Components\Section::make('Capacité et durée')
                    ->schema([
                        TextInput::make('capacite_max')
                            ->label('Capacité maximale')
                            ->numeric()
                            ->default(20)
                            ->minValue(1)
                            ->maxValue(100)
                            ->required(),

                        TextInput::make('duree_minutes')
                            ->label('Durée (minutes)')
                            ->numeric()
                            ->default(60)
                            ->minValue(15)
                            ->maxValue(240)
                            ->required(),

                        ColorPicker::make('couleur_calendrier')
                            ->label('Couleur calendrier')
                            ->default('#3B82F6'),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('Tarification')
                    ->schema([
                        TextInput::make('prix_mensuel')
                            ->label('Prix mensuel')
                            ->numeric()
                            ->prefix('$')
                            ->step(0.01)
                            ->minValue(0),

                        TextInput::make('prix_seance')
                            ->label('Prix par séance')
                            ->numeric()
                            ->prefix('$')
                            ->step(0.01)
                            ->minValue(0),

                        TextInput::make('prix_carte_10')
                            ->label('Prix carte 10 séances')
                            ->numeric()
                            ->prefix('$')
                            ->step(0.01)
                            ->minValue(0),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('Horaires')
                    ->schema([
                        Repeater::make('horaires')
                            ->relationship()
                            ->schema([
                                Select::make('jour_semaine')
                                    ->label('Jour')
                                    ->options([
                                        1 => 'Lundi',
                                        2 => 'Mardi',
                                        3 => 'Mercredi',
                                        4 => 'Jeudi',
                                        5 => 'Vendredi',
                                        6 => 'Samedi',
                                        7 => 'Dimanche',
                                    ])
                                    ->required(),

                                TimePicker::make('heure_debut')
                                    ->label('Heure de début')
                                    ->required()
                                    ->seconds(false),

                                TimePicker::make('heure_fin')
                                    ->label('Heure de fin')
                                    ->required()
                                    ->seconds(false),

                                TextInput::make('salle')
                                    ->maxLength(255)
                                    ->placeholder('Ex: Dojo 1'),

                                Select::make('instructeur_id')
                                    ->label('Instructeur')
                                    ->relationship('instructeur', 'nom')
                                    ->searchable()
                                    ->preload(),

                                Toggle::make('actif')
                                    ->default(true),
                            ])
                            ->columns(3)
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => 
                                isset($state['jour_semaine']) && isset($state['heure_debut']) 
                                    ? ['', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'][$state['jour_semaine']] . ' ' . $state['heure_debut']
                                    : null
                            ),
                    ]),

                Forms\Components\Section::make('Options')
                    ->schema([
                        Toggle::make('inscription_requise')
                            ->label('Inscription requise')
                            ->default(true)
                            ->helperText('Les membres doivent s\'inscrire avant de participer'),

                        Toggle::make('actif')
                            ->default(true)
                            ->helperText('Cours visible et disponible pour inscription'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nom')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::SemiBold)
                    ->description(fn (Cours $record): ?string => $record->description),

                TextColumn::make('ecole.nom')
                    ->label('École')
                    ->sortable()
                    ->badge()
                    ->color('primary')
                    ->visible(fn () => auth()->user()?->hasRole('super-admin')),

                TextColumn::make('type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'regulier' => 'success',
                        'special' => 'warning',
                        'seminaire' => 'info',
                        default => 'gray',
                    }),

                TextColumn::make('capacite_max')
                    ->label('Capacité')
                    ->alignCenter()
                    ->suffix(' pers.')
                    ->sortable(),

                TextColumn::make('duree_minutes')
                    ->label('Durée')
                    ->alignCenter()
                    ->suffix(' min')
                    ->sortable(),

                TextColumn::make('prix_mensuel')
                    ->label('Prix/mois')
                    ->money('CAD')
                    ->sortable(),

                TextColumn::make('inscriptions_count')
                    ->counts('inscriptions')
                    ->label('Inscrits')
                    ->badge()
                    ->color('success')
                    ->sortable(),

                IconColumn::make('actif')
                    ->boolean()
                    ->sortable()
                    ->alignCenter(),

                TextColumn::make('created_at')
                    ->label('Créé le')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('ecole_id')
                    ->label('École')
                    ->relationship('ecole', 'nom')
                    ->visible(fn () => auth()->user()?->hasRole('super-admin')),

                SelectFilter::make('type')
                    ->options([
                        'regulier' => 'Régulier',
                        'special' => 'Spécial',
                        'seminaire' => 'Séminaire',
                    ]),

                SelectFilter::make('actif')
                    ->label('Statut')
                    ->options([
                        '1' => 'Actifs',
                        '0' => 'Inactifs',
                    ])
                    ->default('1'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->requiresConfirmation(),
                ]),
            ])
            ->defaultSort('nom')
            ->emptyStateHeading('Aucun cours')
            ->emptyStateDescription('Commencez par créer votre premier cours.')
            ->emptyStateIcon('heroicon-o-academic-cap');
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

    public static function getNavigationBadge(): ?string
    {
        if (auth()->user()?->hasRole('super-admin')) {
            return (string) Cours::count();
        }

        return (string) Cours::where('ecole_id', auth()->user()?->ecole_id)->count();
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'info';
    }
}
