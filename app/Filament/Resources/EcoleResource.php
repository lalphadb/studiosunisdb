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
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;

class EcoleResource extends Resource
{
    protected static ?string $model = Ecole::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    
    protected static ?string $navigationLabel = 'Écoles';
    
    protected static ?string $modelLabel = 'École';
    
    protected static ?string $pluralModelLabel = 'Écoles';
    
    protected static ?string $navigationGroup = 'Configuration';
    
    protected static ?int $navigationSort = 10;

    /**
     * Restriction : Seuls les super-admin peuvent accéder aux écoles
     */
    public static function canViewAny(): bool
    {
        return auth()->user()?->hasRole('super-admin') ?? false;
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->hasRole('super-admin') ?? false;
    }

    public static function canEdit($record): bool
    {
        return auth()->user()?->hasRole('super-admin') ?? false;
    }

    public static function canDelete($record): bool
    {
        return auth()->user()?->hasRole('super-admin') ?? false;
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
                            ->placeholder('Ex: École St-Émile')
                            ->helperText('Nom complet de l\'école'),

                        TextInput::make('code')
                            ->required()
                            ->maxLength(4)
                            ->unique(Ecole::class, 'code', ignoreRecord: true)
                            ->placeholder('Ex: SEM')
                            ->helperText('Code unique 3-4 caractères')
                            ->uppercase(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Adresse')
                    ->schema([
                        TextInput::make('adresse')
                            ->maxLength(255)
                            ->placeholder('Ex: 2200 Rue de la Faune'),

                        TextInput::make('ville')
                            ->maxLength(255)
                            ->placeholder('Ex: St-Émile'),

                        TextInput::make('code_postal')
                            ->maxLength(7)
                            ->placeholder('Ex: G3E 1K6')
                            ->mask('A9A 9A9'),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('Contact')
                    ->schema([
                        TextInput::make('telephone')
                            ->tel()
                            ->maxLength(20)
                            ->placeholder('Ex: (418) 555-1234'),

                        TextInput::make('email')
                            ->email()
                            ->maxLength(255)
                            ->placeholder('Ex: ecole@studiosunis.com'),

                        Toggle::make('actif')
                            ->default(true)
                            ->helperText('École active dans le système'),
                    ])
                    ->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nom')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::SemiBold),

                TextColumn::make('code')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('primary'),

                TextColumn::make('ville')
                    ->searchable()
                    ->sortable()
                    ->description(fn (Ecole $record): string => $record->code_postal ?? ''),

                TextColumn::make('users_count')
                    ->counts('users')
                    ->label('Utilisateurs')
                    ->sortable()
                    ->alignCenter()
                    ->badge()
                    ->color('success'),

                TextColumn::make('cours_count')
                    ->counts('cours')
                    ->label('Cours')
                    ->sortable()
                    ->alignCenter()
                    ->badge()
                    ->color('info'),

                IconColumn::make('actif')
                    ->boolean()
                    ->sortable()
                    ->alignCenter(),

                TextColumn::make('created_at')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('actif')
                    ->options([
                        '1' => 'Actives',
                        '0' => 'Inactives',
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
            ->emptyStateHeading('Aucune école')
            ->emptyStateDescription('Commencez par ajouter votre première école StudiosUnisDB.')
            ->emptyStateIcon('heroicon-o-building-office');
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
}
