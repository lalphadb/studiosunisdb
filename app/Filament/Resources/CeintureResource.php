<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CeintureResource\Pages;
use App\Models\Ceinture;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ColorColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\KeyValue;

class CeintureResource extends Resource
{
    protected static ?string $model = Ceinture::class;

    protected static ?string $navigationIcon = 'heroicon-o-trophy';
    
    protected static ?string $navigationLabel = 'Ceintures';
    
    protected static ?string $modelLabel = 'Ceinture';
    
    protected static ?string $pluralModelLabel = 'Ceintures';
    
    protected static ?string $navigationGroup = 'Configuration';
    
    protected static ?int $navigationSort = 20;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informations de base')
                    ->schema([
                        TextInput::make('nom')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Ex: Ceinture Blanche'),

                        TextInput::make('nom_court')
                            ->label('Nom court')
                            ->maxLength(50)
                            ->placeholder('Ex: Blanche'),

                        ColorPicker::make('couleur')
                            ->required()
                            ->default('#FFFFFF'),

                        TextInput::make('ordre')
                            ->required()
                            ->numeric()
                            ->unique(Ceinture::class, 'ordre', ignoreRecord: true)
                            ->minValue(1)
                            ->maxValue(100)
                            ->helperText('Ordre de progression (1 = première ceinture)'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Progression')
                    ->schema([
                        TextInput::make('mois_minimum')
                            ->label('Mois minimum')
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->maxValue(120)
                            ->helperText('Temps minimum avant passage suivant'),

                        Textarea::make('description')
                            ->maxLength(1000)
                            ->rows(3)
                            ->placeholder('Description de la ceinture...'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Compétences requises')
                    ->schema([
                        KeyValue::make('competences_requises')
                            ->label('Compétences')
                            ->keyLabel('Compétence')
                            ->valueLabel('Niveau requis')
                            ->addActionLabel('Ajouter une compétence')
                            ->helperText('Ex: Kata → Heian Shodan, Techniques → Mae-geri, Casse → 1 planche'),
                    ]),

                Forms\Components\Section::make('Options')
                    ->schema([
                        Toggle::make('actif')
                            ->default(true)
                            ->helperText('Ceinture active dans le système'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('ordre')
                    ->sortable()
                    ->alignCenter()
                    ->badge()
                    ->color('gray'),

                ColorColumn::make('couleur')
                    ->sortable(),

                TextColumn::make('nom')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::SemiBold)
                    ->description(fn (Ceinture $record): ?string => $record->nom_court),

                TextColumn::make('mois_minimum')
                    ->label('Temps min.')
                    ->suffix(' mois')
                    ->alignCenter()
                    ->sortable(),

                TextColumn::make('userCeintures_count')
                    ->counts('userCeintures')
                    ->label('Détenteurs')
                    ->badge()
                    ->color('success')
                    ->sortable(),

                IconColumn::make('actif')
                    ->boolean()
                    ->sortable()
                    ->alignCenter(),

                TextColumn::make('created_at')
                    ->label('Créée le')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('actif')
                    ->label('Statut')
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
            ->defaultSort('ordre')
            ->emptyStateHeading('Aucune ceinture')
            ->emptyStateDescription('Le système de ceintures n\'est pas encore configuré.')
            ->emptyStateIcon('heroicon-o-trophy')
            ->reorderable('ordre');
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
            'index' => Pages\ListCeintures::route('/'),
            'create' => Pages\CreateCeinture::route('/create'),
            'view' => Pages\ViewCeinture::route('/{record}'),
            'edit' => Pages\EditCeinture::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) Ceinture::where('actif', true)->count();
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'warning';
    }
}
