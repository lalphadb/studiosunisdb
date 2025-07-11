<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\Ecole;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    
    protected static ?string $navigationGroup = 'Gestion École';
    
    protected static ?int $navigationSort = 1;
    
    protected static ?string $modelLabel = 'Utilisateur';
    
    protected static ?string $pluralModelLabel = 'Utilisateurs';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informations personnelles')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('prenom')
                                    ->label('Prénom')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('nom')
                                    ->label('Nom')
                                    ->required()
                                    ->maxLength(255),
                            ]),
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('email')
                                    ->label('Courriel')
                                    ->email()
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('telephone')
                                    ->label('Téléphone')
                                    ->tel()
                                    ->maxLength(20),
                            ]),
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\DatePicker::make('date_naissance')
                                    ->label('Date de naissance')
                                    ->displayFormat('d/m/Y')
                                    ->native(false),
                                Forms\Components\Select::make('sexe')
                                    ->label('Sexe')
                                    ->options([
                                        'M' => 'Masculin',
                                        'F' => 'Féminin',
                                        'A' => 'Autre',
                                    ]),
                            ]),
                    ])
                    ->columns(2),
                
                Forms\Components\Section::make('Adresse')
                    ->schema([
                        Forms\Components\TextInput::make('adresse')
                            ->label('Adresse')
                            ->maxLength(255),
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('ville')
                                    ->label('Ville')
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('code_postal')
                                    ->label('Code postal')
                                    ->maxLength(10),
                                Forms\Components\Select::make('province')
                                    ->label('Province')
                                    ->options([
                                        'QC' => 'Québec',
                                        'ON' => 'Ontario',
                                        'NB' => 'Nouveau-Brunswick',
                                        'AB' => 'Alberta',
                                        'BC' => 'Colombie-Britannique',
                                        'MB' => 'Manitoba',
                                        'NS' => 'Nouvelle-Écosse',
                                        'PE' => 'Île-du-Prince-Édouard',
                                        'SK' => 'Saskatchewan',
                                        'NL' => 'Terre-Neuve-et-Labrador',
                                    ])
                                    ->default('QC'),
                            ]),
                    ])
                    ->collapsible(),
                
                Forms\Components\Section::make('Compte et accès')
                    ->schema([
                        Forms\Components\Select::make('ecole_id')
                            ->label('École')
                            ->relationship('ecole', 'nom')
                            ->required()
                            ->visible(fn () => auth()->user()->hasRole('super-admin'))
                            ->default(fn () => auth()->user()->hasRole('super-admin') ? null : auth()->user()->ecole_id),
                        Forms\Components\TextInput::make('code_utilisateur')
                            ->label('Code utilisateur')
                            ->disabled()
                            ->default(fn () => 'U' . str_pad(User::max('id') + 1, 6, '0', STR_PAD_LEFT)),
                        Forms\Components\TextInput::make('password')
                            ->label('Mot de passe')
                            ->password()
                            ->required(fn (string $context): bool => $context === 'create')
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->dehydrated(fn ($state) => filled($state)),
                        Forms\Components\Select::make('roles')
                            ->label('Rôles')
                            ->multiple()
                            ->relationship('roles', 'name')
                            ->preload()
                            ->visible(fn () => auth()->user()->hasAnyRole(['super-admin', 'admin'])),
                        Forms\Components\Toggle::make('actif')
                            ->label('Compte actif')
                            ->default(true)
                            ->columnSpan('full'),
                    ]),
                
                Forms\Components\Section::make('Contact d\'urgence')
                    ->schema([
                        Forms\Components\TextInput::make('contact_urgence_nom')
                            ->label('Nom du contact d\'urgence')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('contact_urgence_telephone')
                            ->label('Téléphone d\'urgence')
                            ->tel()
                            ->maxLength(20),
                    ])
                    ->collapsible()
                    ->collapsed(),
                
                Forms\Components\Section::make('Notes')
                    ->schema([
                        Forms\Components\Textarea::make('notes')
                            ->label('Notes internes')
                            ->rows(3)
                            ->columnSpan('full'),
                    ])
                    ->collapsible()
                    ->collapsed(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code_utilisateur')
                    ->label('Code')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nom_complet')
                    ->label('Nom complet')
                    ->searchable(['nom', 'prenom'])
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Courriel')
                    ->searchable(),
                Tables\Columns\TextColumn::make('ecole.nom')
                    ->label('École')
                    ->sortable()
                    ->visible(fn () => auth()->user()->hasRole('super-admin')),
                Tables\Columns\TextColumn::make('roles.name')
                    ->label('Rôles')
                    ->badge()
                    ->separator(','),
                Tables\Columns\TextColumn::make('telephone')
                    ->label('Téléphone')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('actif')
                    ->label('Actif')
                    ->boolean(),
                Tables\Columns\TextColumn::make('derniere_connexion')
                    ->label('Dernière connexion')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Créé le')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('ecole')
                    ->relationship('ecole', 'nom')
                    ->visible(fn () => auth()->user()->hasRole('super-admin')),
                Tables\Filters\SelectFilter::make('roles')
                    ->relationship('roles', 'name')
                    ->multiple()
                    ->preload(),
                Tables\Filters\TernaryFilter::make('actif')
                    ->label('Statut')
                    ->boolean()
                    ->trueLabel('Actifs seulement')
                    ->falseLabel('Inactifs seulement')
                    ->native(false),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->requiresConfirmation()
                    ->modalHeading('Supprimer l\'utilisateur')
                    ->modalDescription('Êtes-vous sûr de vouloir supprimer cet utilisateur? Cette action est irréversible.')
                    ->modalSubmitActionLabel('Oui, supprimer'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->requiresConfirmation()
                        ->modalHeading('Supprimer les utilisateurs sélectionnés')
                        ->modalDescription('Êtes-vous sûr de vouloir supprimer ces utilisateurs? Cette action est irréversible.')
                        ->modalSubmitActionLabel('Oui, supprimer'),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    // Appliquer le filtre multi-tenant
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        
        // Si l'utilisateur n'est pas super-admin, filtrer par école
        if (!auth()->user()->hasRole('super-admin')) {
            $query->where('ecole_id', auth()->user()->ecole_id);
        }
        
        return $query;
    }

    // Définir les permissions
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
        // Un utilisateur ne peut pas s'éditer lui-même sauf s'il est super-admin
        if ($record->id === auth()->id() && !auth()->user()->hasRole('super-admin')) {
            return false;
        }
        
        return auth()->user()->hasAnyRole(['super-admin', 'admin']);
    }

    public static function canDelete($record): bool
    {
        // Un utilisateur ne peut pas se supprimer lui-même
        if ($record->id === auth()->id()) {
            return false;
        }
        
        return auth()->user()->hasAnyRole(['super-admin', 'admin']);
    }
}
