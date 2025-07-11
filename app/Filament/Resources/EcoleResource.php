<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EcoleResource\Pages;
use App\Models\Ecole;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class EcoleResource extends Resource
{
    protected static ?string $model = Ecole::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    
    protected static ?string $navigationGroup = 'Configuration';
    
    protected static ?int $navigationSort = 10;
    
    protected static ?string $modelLabel = 'École';
    
    protected static ?string $pluralModelLabel = 'Écoles';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informations générales')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('nom')
                                    ->label('Nom de l\'école')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('code')
                                    ->label('Code école')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(10)
                                    ->disabled(fn () => !auth()->user()->hasRole('super-admin'))
                                    ->helperText('Code unique de 3-4 caractères'),
                            ]),
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('email')
                                    ->label('Courriel')
                                    ->email()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('telephone')
                                    ->label('Téléphone')
                                    ->tel()
                                    ->maxLength(20),
                            ]),
                    ]),
                
                Forms\Components\Section::make('Adresse')
                    ->schema([
                        Forms\Components\TextInput::make('adresse')
                            ->label('Adresse')
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('ville')
                                    ->label('Ville')
                                    ->maxLength(100),
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
                    ]),
                
                Forms\Components\Section::make('Configuration')
                    ->schema([
                        Forms\Components\Toggle::make('actif')
                            ->label('École active')
                            ->default(true)
                            ->helperText('Une école désactivée empêche la connexion de tous ses utilisateurs')
                            ->disabled(fn ($record) => !auth()->user()->hasRole('super-admin') && $record?->id === auth()->user()->ecole_id),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Code')
                    ->badge()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nom')
                    ->label('Nom')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ville')
                    ->label('Ville')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Courriel')
                    ->icon('heroicon-m-envelope')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('telephone')
                    ->label('Téléphone')
                    ->icon('heroicon-m-phone')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('users_count')
                    ->label('Utilisateurs')
                    ->counts('users')
                    ->badge()
                    ->color('primary'),
                Tables\Columns\TextColumn::make('cours_count')
                    ->label('Cours')
                    ->counts('cours')
                    ->badge()
                    ->color('success'),
                Tables\Columns\IconColumn::make('actif')
                    ->label('Active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Créée le')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('actif')
                    ->label('Statut')
                    ->boolean()
                    ->trueLabel('Actives seulement')
                    ->falseLabel('Inactives seulement')
                    ->native(false),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()
                    ->visible(fn ($record) => 
                        auth()->user()->hasRole('super-admin') || 
                        $record->id === auth()->user()->ecole_id
                    ),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn () => auth()->user()->hasRole('super-admin')),
                ]),
            ]);
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
            'index' => Pages\ListEcoles::route('/'),
            'create' => Pages\CreateEcole::route('/create'),
            'view' => Pages\ViewEcole::route('/{record}'),
            'edit' => Pages\EditEcole::route('/{record}/edit'),
        ];
    }

    // Filtre multi-tenant : admin voit seulement son école
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        
        // Si l'utilisateur n'est pas super-admin, montrer seulement son école
        if (!auth()->user()->hasRole('super-admin')) {
            $query->where('id', auth()->user()->ecole_id);
        }
        
        return $query;
    }

    // Permissions
    public static function canViewAny(): bool
    {
        return auth()->user()->hasAnyRole(['super-admin', 'admin']);
    }

    public static function canCreate(): bool
    {
        // Seul le super-admin peut créer de nouvelles écoles
        return auth()->user()->hasRole('super-admin');
    }

    public static function canEdit($record): bool
    {
        // Super-admin peut tout éditer
        // Admin peut éditer seulement son école
        return auth()->user()->hasRole('super-admin') || 
               (auth()->user()->hasRole('admin') && $record->id === auth()->user()->ecole_id);
    }

    public static function canDelete($record): bool
    {
        // Seul le super-admin peut supprimer des écoles
        return auth()->user()->hasRole('super-admin');
    }

    public static function canDeleteAny(): bool
    {
        return auth()->user()->hasRole('super-admin');
    }
}
