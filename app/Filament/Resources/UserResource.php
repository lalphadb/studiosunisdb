<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use App\Models\Ecole;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    
    protected static ?string $navigationLabel = 'Utilisateurs';
    
    protected static ?string $modelLabel = 'Utilisateur';
    
    protected static ?string $pluralModelLabel = 'Utilisateurs';
    
    protected static ?string $navigationGroup = 'Gestion École';
    
    protected static ?int $navigationSort = 1;

    /**
     * Scope multi-tenant : filtrer selon l'école de l'utilisateur connecté
     */
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        // Super-admin peut voir tous les utilisateurs
        if (auth()->user()?->hasRole('super-admin')) {
            return $query;
        }

        // Autres utilisateurs voient seulement leur école
        return $query->where('ecole_id', auth()->user()?->ecole_id);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informations personnelles')
                    ->schema([
                        TextInput::make('prenom')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Ex: Jean'),

                        TextInput::make('nom')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Ex: Dupont'),

                        TextInput::make('email')
                            ->email()
                            ->required()
                            ->unique(User::class, 'email', ignoreRecord: true)
                            ->maxLength(255),

                        DatePicker::make('date_naissance')
                            ->label('Date de naissance')
                            ->displayFormat('d/m/Y')
                            ->native(false),

                        Select::make('sexe')
                            ->options([
                                'M' => 'Masculin',
                                'F' => 'Féminin',
                                'A' => 'Autre',
                            ])
                            ->placeholder('Sélectionner'),

                        FileUpload::make('photo')
                            ->label('Photo de profil')
                            ->image()
                            ->disk('public')
                            ->directory('photos')
                            ->maxSize(2048)
                            ->imageResizeMode('cover')
                            ->imageCropAspectRatio('1:1')
                            ->imageResizeTargetWidth('300')
                            ->imageResizeTargetHeight('300'),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('École et rôles')
                    ->schema([
                        Select::make('ecole_id')
                            ->label('École')
                            ->relationship('ecole', 'nom')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->visible(fn () => auth()->user()?->hasRole('super-admin'))
                            ->default(fn () => auth()->user()?->ecole_id),

                        Select::make('roles')
                            ->label('Rôles')
                            ->relationship('roles', 'name')
                            ->multiple()
                            ->preload()
                            ->searchable()
                            ->helperText('Sélectionner un ou plusieurs rôles'),

                        TextInput::make('password')
                            ->label('Mot de passe')
                            ->password()
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $context): bool => $context === 'create')
                            ->minLength(8)
                            ->placeholder('Minimum 8 caractères'),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('Adresse')
                    ->schema([
                        TextInput::make('adresse')
                            ->maxLength(255)
                            ->placeholder('Ex: 123 Rue de la Paix'),

                        TextInput::make('ville')
                            ->maxLength(255)
                            ->placeholder('Ex: Québec'),

                        TextInput::make('province')
                            ->maxLength(255)
                            ->default('QC'),

                        TextInput::make('code_postal')
                            ->maxLength(7)
                            ->placeholder('Ex: G1A 1A1')
                            ->mask('A9A 9A9'),

                        TextInput::make('pays')
                            ->maxLength(255)
                            ->default('Canada'),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('Contact et urgence')
                    ->schema([
                        TextInput::make('telephone')
                            ->tel()
                            ->maxLength(20)
                            ->placeholder('Ex: (418) 555-1234'),

                        TextInput::make('contact_urgence_nom')
                            ->label('Contact d\'urgence - Nom')
                            ->maxLength(255)
                            ->placeholder('Ex: Marie Dupont'),

                        TextInput::make('contact_urgence_telephone')
                            ->label('Contact d\'urgence - Téléphone')
                            ->tel()
                            ->maxLength(20)
                            ->placeholder('Ex: (418) 555-5678'),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('Famille et notes')
                    ->schema([
                        Select::make('membre_famille_id')
                            ->label('Membre de famille')
                            ->relationship('membreFamille', 'nom')
                            ->searchable()
                            ->preload()
                            ->helperText('Lier à un autre membre de la famille'),

                        Textarea::make('notes')
                            ->maxLength(1000)
                            ->rows(3)
                            ->placeholder('Notes particulières...'),

                        Toggle::make('actif')
                            ->default(true)
                            ->helperText('Utilisateur actif dans le système'),
                    ])
                    ->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('photo')
                    ->circular()
                    ->defaultImageUrl(fn ($record) => 'https://ui-avatars.com/api/?name=' . urlencode($record->prenom . ' ' . $record->nom) . '&color=7F9CF5&background=EBF4FF')
                    ->size(40),

                TextColumn::make('nom_complet')
                    ->label('Nom complet')
                    ->searchable(['nom', 'prenom'])
                    ->sortable(['nom', 'prenom'])
                    ->weight(FontWeight::SemiBold)
                    ->description(fn (User $record): ?string => $record->email),

                TextColumn::make('code_utilisateur')
                    ->label('Code')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('gray'),

                TextColumn::make('ecole.nom')
                    ->label('École')
                    ->sortable()
                    ->searchable()
                    ->badge()
                    ->color('primary')
                    ->visible(fn () => auth()->user()?->hasRole('super-admin')),

                TextColumn::make('roles.name')
                    ->label('Rôles')
                    ->badge()
                    ->separator(', ')
                    ->color('success'),

                TextColumn::make('age')
                    ->label('Âge')
                    ->sortable()
                    ->alignCenter()
                    ->placeholder('N/A'),

                TextColumn::make('ceinture_actuelle.nom')
                    ->label('Ceinture')
                    ->badge()
                    ->color(fn ($record) => $record->ceinture_actuelle?->couleur ?? 'gray')
                    ->placeholder('Aucune'),

                IconColumn::make('actif')
                    ->boolean()
                    ->sortable()
                    ->alignCenter(),

                TextColumn::make('derniere_connexion')
                    ->label('Dernière connexion')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->placeholder('Jamais')
                    ->toggleable(isToggledHiddenByDefault: true),

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

                SelectFilter::make('roles')
                    ->label('Rôle')
                    ->relationship('roles', 'name')
                    ->multiple()
                    ->preload(),

                SelectFilter::make('actif')
                    ->label('Statut')
                    ->options([
                        '1' => 'Actifs',
                        '0' => 'Inactifs',
                    ])
                    ->default('1'),

                Filter::make('avec_ceinture')
                    ->label('Avec ceinture')
                    ->query(fn (Builder $query): Builder => $query->has('ceintures')),

                Filter::make('sans_ceinture')
                    ->label('Sans ceinture')
                    ->query(fn (Builder $query): Builder => $query->doesntHave('ceintures')),
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
            ->searchOnBlur()
            ->emptyStateHeading('Aucun utilisateur')
            ->emptyStateDescription('Commencez par ajouter des utilisateurs à votre école.')
            ->emptyStateIcon('heroicon-o-users');
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

    /**
     * Badge de navigation avec compteur
     */
    public static function getNavigationBadge(): ?string
    {
        if (auth()->user()?->hasRole('super-admin')) {
            return (string) User::count();
        }

        return (string) User::where('ecole_id', auth()->user()?->ecole_id)->count();
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'primary';
    }
}
