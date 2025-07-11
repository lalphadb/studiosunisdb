<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PaiementResource\Pages;
use App\Filament\Admin\Resources\PaiementResource\RelationManagers;
use App\Models\Paiement;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PaiementResource extends Resource
{
    protected static ?string $model = Paiement::class;

    protected static ?string $navigationGroup = 'Finances';

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('ecole_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('montant')
                    ->required()
                    ->numeric(),
                Forms\Components\DatePicker::make('date_paiement')
                    ->required(),
                Forms\Components\TextInput::make('type_paiement')
                    ->required(),
                Forms\Components\TextInput::make('methode_paiement')
                    ->required(),
                Forms\Components\TextInput::make('reference_paiement')
                    ->maxLength(100),
                Forms\Components\TextInput::make('statut')
                    ->required(),
                Forms\Components\Textarea::make('description')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ecole_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('montant')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('date_paiement')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type_paiement'),
                Tables\Columns\TextColumn::make('methode_paiement'),
                Tables\Columns\TextColumn::make('reference_paiement')
                    ->searchable(),
                Tables\Columns\TextColumn::make('statut'),
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
            'index' => Pages\ListPaiements::route('/'),
            'create' => Pages\CreatePaiement::route('/create'),
            'edit' => Pages\EditPaiement::route('/{record}/edit'),
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