<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\SeminaireResource\Pages;
use App\Filament\Admin\Resources\SeminaireResource\RelationManagers;
use App\Models\Seminaire;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SeminaireResource extends Resource
{
    protected static ?string $model = Seminaire::class;

    protected static ?string $navigationGroup = 'Événements';

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('ecole_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('nom')
                    ->required()
                    ->maxLength(200),
                Forms\Components\Textarea::make('description')
                    ->columnSpanFull(),
                Forms\Components\DateTimePicker::make('date_debut')
                    ->required(),
                Forms\Components\DateTimePicker::make('date_fin')
                    ->required(),
                Forms\Components\TextInput::make('lieu')
                    ->maxLength(255),
                Forms\Components\TextInput::make('instructeur_principal_id')
                    ->numeric(),
                Forms\Components\TextInput::make('prix')
                    ->numeric(),
                Forms\Components\TextInput::make('capacite_max')
                    ->numeric(),
                Forms\Components\TextInput::make('statut')
                    ->required(),
                Forms\Components\Toggle::make('ouvert_externe')
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
                Tables\Columns\TextColumn::make('date_debut')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('date_fin')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('lieu')
                    ->searchable(),
                Tables\Columns\TextColumn::make('instructeur_principal_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('prix')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('capacite_max')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('statut'),
                Tables\Columns\IconColumn::make('ouvert_externe')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
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
            'index' => Pages\ListSeminaires::route('/'),
            'create' => Pages\CreateSeminaire::route('/create'),
            'edit' => Pages\EditSeminaire::route('/{record}/edit'),
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