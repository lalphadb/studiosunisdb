<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\CeintureResource\Pages;
use App\Filament\Admin\Resources\CeintureResource\RelationManagers;
use App\Models\Ceinture;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CeintureResource extends Resource
{
    protected static ?string $model = Ceinture::class;

    protected static ?string $navigationGroup = 'Gestion École';

    protected static ?string $navigationIcon = 'heroicon-o-star';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('ecole_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('nom')
                    ->required()
                    ->maxLength(100),
                Forms\Components\TextInput::make('nom_anglais')
                    ->maxLength(100),
                Forms\Components\TextInput::make('couleur_principale')
                    ->required()
                    ->maxLength(50),
                Forms\Components\TextInput::make('couleur_secondaire')
                    ->maxLength(50),
                Forms\Components\TextInput::make('ordre')
                    ->required()
                    ->numeric(),
                Forms\Components\Textarea::make('description')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('mois_minimum')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('cours_minimum')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\Toggle::make('actif')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('ecole_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nom')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nom_anglais')
                    ->searchable(),
                Tables\Columns\TextColumn::make('couleur_principale')
                    ->searchable(),
                Tables\Columns\TextColumn::make('couleur_secondaire')
                    ->searchable(),
                Tables\Columns\TextColumn::make('ordre')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('mois_minimum')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('cours_minimum')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('actif')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListCeintures::route('/'),
            'create' => Pages\CreateCeinture::route('/create'),
            'edit' => Pages\EditCeinture::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->when(
                !auth()->user()->hasRole('super-admin'),
                fn (Builder $query) => $query->where('ecole_id', auth()->user()->ecole_id)
            );
    }
}