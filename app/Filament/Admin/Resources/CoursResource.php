<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\CoursResource\Pages;
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
    protected static ?string $navigationGroup = '🏫 Gestion École';
    protected static ?string $navigationLabel = 'Cours';
    protected static ?string $modelLabel = 'Cours';
    protected static ?string $pluralModelLabel = 'Cours';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Hidden::make('ecole_id')
                    ->default(fn () => auth()->user()->ecole_id),

                Forms\Components\Section::make('📚 Informations du Cours')
                    ->schema([
                        Forms\Components\TextInput::make('nom')
                            ->label('Nom du cours')
                            ->required()
                            ->placeholder('Ex: Karaté Parents-Enfants 14 ans et moins')
                            ->maxLength(255),

                        Forms\Components\Textarea::make('description')
                            ->label('Description')
                            ->rows(3)
                            ->placeholder('Description détaillée du cours...'),

                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\Select::make('niveau')
                                    ->label('Niveau requis')
                                    ->required()
                                    ->options([
                                        'tous' => '👥 Tous niveaux',
                                        'debutant' => '🟢 Débutant',
                                        'avance' => '🔴 Avancé', 
                                        'prive' => '👤 Privé',
                                        'a_la_carte' => '🎯 À la carte',
                                        'combat' => '🥊 Combat',
                                        'autres' => '📝 Autres',
                                    ])
                                    ->default('tous'),

                                Forms\Components\TextInput::make('type_cours')
                                    ->label('Type de cours')
                                    ->placeholder('Ex: Karaté, Judo, Aikido...')
                                    ->helperText('Libre pour l\'admin d\'école'),

                                Forms\Components\TextInput::make('age_minimum')
                                    ->label('Âge minimum')
                                    ->numeric()
                                    ->suffix('ans')
                                    ->minValue(3)
                                    ->maxValue(100),
                            ]),
                    ]),

                Forms\Components\Section::make('⏰ Horaires Intégrés')
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\CheckboxList::make('jours_semaine')
                                    ->label('Jours de la semaine')
                                    ->required()
                                    ->options([
                                        1 => 'Lundi',
                                        2 => 'Mardi', 
                                        3 => 'Mercredi',
                                        4 => 'Jeudi',
                                        5 => 'Vendredi',
                                        6 => 'Samedi',
                                        7 => 'Dimanche',
                                    ])
                                    ->columns(2)
                                    ->helperText('Sélectionnez un ou plusieurs jours'),

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

                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('duree_minutes')
                                    ->label('Durée')
                                    ->numeric()
                                    ->default(60)
                                    ->suffix('minutes')
                                    ->minValue(15)
                                    ->maxValue(300),

                                Forms\Components\TextInput::make('salle')
                                    ->label('Salle')
                                    ->placeholder('Ex: Dojo Principal, Salle 2'),

                                Forms\Components\TextInput::make('capacite_max')
                                    ->label('Capacité max')
                                    ->numeric()
                                    ->default(20)
                                    ->suffix('élèves')
                                    ->minValue(1),
                            ]),
                    ]),

                Forms\Components\Section::make('💰 Tarification')
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\Select::make('mode_paiement')
                                    ->label('Mode de paiement')
                                    ->required()
                                    ->options([
                                        'quotidien' => '📅 Quotidien',
                                        'mensuel' => '📆 Mensuel', 
                                        'trimestriel' => '📋 Trimestriel (3 mois)',
                                        'autre' => '💰 Autre',
                                    ])
                                    ->default('mensuel'),

                                Forms\Components\TextInput::make('prix')
                                    ->label('Prix')
                                    ->required()
                                    ->numeric()
                                    ->prefix('$')
                                    ->step(0.01),

                                Forms\Components\Select::make('devise')
                                    ->label('Devise')
                                    ->options([
                                        'CAD' => 'CAD ($)',
                                        'USD' => 'USD ($)',
                                        'EUR' => 'EUR (€)',
                                    ])
                                    ->default('CAD'),
                            ]),
                    ]),

                Forms\Components\Section::make('📅 Sessions et Saisons')
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\Select::make('saison')
                                    ->label('Saison')
                                    ->options([
                                        'automne' => '🍂 Automne',
                                        'hiver' => '❄️ Hiver',
                                        'printemps' => '🌸 Printemps',
                                        'ete' => '☀️ Été',
                                    ])
                                    ->placeholder('Toute l\'année'),

                                Forms\Components\DatePicker::make('date_debut')
                                    ->label('Date de début'),

                                Forms\Components\DatePicker::make('date_fin')
                                    ->label('Date de fin')
                                    ->after('date_debut'),
                            ]),
                    ]),

                Forms\Components\Section::make('🎨 Options')
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\Toggle::make('actif')
                                    ->label('Cours actif')
                                    ->default(true),

                                Forms\Components\Toggle::make('inscription_ouverte')
                                    ->label('Inscriptions ouvertes')
                                    ->default(true),

                                Forms\Components\ColorPicker::make('couleur')
                                    ->label('Couleur')
                                    ->default('#3B82F6'),
                            ]),

                        Forms\Components\Textarea::make('notes')
                            ->label('Notes')
                            ->rows(2),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nom')
                    ->label('Nom du cours')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('niveau')
                    ->label('Niveau')
                    ->badge()
                    ->color(fn (string $state): string => match($state) {
                        'tous' => 'gray',
                        'debutant' => 'success',
                        'avance' => 'danger',
                        'prive' => 'warning',
                        'combat' => 'danger',
                        default => 'info'
                    })
                    ->formatStateUsing(fn (string $state): string => match($state) {
                        'tous' => 'Tous',
                        'debutant' => 'Débutant',
                        'avance' => 'Avancé',
                        'prive' => 'Privé',
                        'a_la_carte' => 'À la carte',
                        'combat' => 'Combat',
                        'autres' => 'Autres',
                        default => $state
                    }),

                Tables\Columns\TextColumn::make('horaire_complet')
                    ->label('Horaires')
                    ->color('info'),

                Tables\Columns\TextColumn::make('prix')
                    ->label('Prix')
                    ->money('CAD')
                    ->sortable(),

                Tables\Columns\TextColumn::make('mode_paiement_label')
                    ->label('Paiement'),

                Tables\Columns\TextColumn::make('capacite_max')
                    ->label('Capacité')
                    ->suffix(' max'),

                Tables\Columns\TextColumn::make('saison_label')
                    ->label('Saison'),

                Tables\Columns\IconColumn::make('actif')
                    ->label('Actif')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('niveau')
                    ->options([
                        'tous' => 'Tous niveaux',
                        'debutant' => 'Débutant',
                        'avance' => 'Avancé',
                        'prive' => 'Privé',
                        'combat' => 'Combat',
                    ]),

                Tables\Filters\SelectFilter::make('saison')
                    ->options([
                        'automne' => 'Automne',
                        'hiver' => 'Hiver', 
                        'printemps' => 'Printemps',
                        'ete' => 'Été',
                    ]),

                Tables\Filters\TernaryFilter::make('actif')
                    ->label('Statut'),
            ])
            ->actions([
                Tables\Actions\Action::make('dupliquer')
                    ->label('Dupliquer')
                    ->icon('heroicon-o-document-duplicate')
                    ->color('info')
                    ->form([
                        Forms\Components\CheckboxList::make('nouveaux_jours')
                            ->label('Nouveaux jours')
                            ->options([
                                1 => 'Lundi', 2 => 'Mardi', 3 => 'Mercredi', 
                                4 => 'Jeudi', 5 => 'Vendredi', 6 => 'Samedi', 7 => 'Dimanche'
                            ])
                            ->required(),
                        Forms\Components\Select::make('nouvelle_saison')
                            ->label('Nouvelle saison (optionnel)')
                            ->options([
                                'automne' => 'Automne', 'hiver' => 'Hiver',
                                'printemps' => 'Printemps', 'ete' => 'Été'
                            ]),
                    ])
                    ->action(function (Cours $record, array $data): void {
                        $record->dupliquer($data['nouveaux_jours'], $data['nouvelle_saison']);
                    }),

                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = auth()->user();
        
        if ($user && $user->hasRole('super-admin')) {
            return $query;
        }
        
        if ($user && $user->ecole_id) {
            return $query->where('ecole_id', $user->ecole_id);
        }
        
        return $query->whereRaw('1 = 0');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCours::route('/'),
            'create' => Pages\CreateCours::route('/create'),
            'edit' => Pages\EditCours::route('/{record}/edit'),
        ];
    }
}
