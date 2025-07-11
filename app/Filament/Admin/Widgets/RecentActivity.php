<?php

namespace App\Filament\Admin\Widgets;

use App\Models\User;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class RecentActivity extends BaseWidget
{
    protected static ?string $heading = 'Nouveaux utilisateurs';
    
    protected static ?int $sort = 3;
    
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                User::query()
                    ->when(
                        !auth()->user()->hasRole('super-admin'),
                        fn (Builder $query) => $query->where('ecole_id', auth()->user()->ecole_id)
                    )
                    ->latest()
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('nom_complet')
                    ->label('Nom')
                    ->searchable(['nom', 'prenom']),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('ecole.nom')
                    ->label('École')
                    ->visible(auth()->user()->hasRole('super-admin')),
                Tables\Columns\IconColumn::make('actif')
                    ->label('Actif')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Inscrit le')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->paginated(false);
    }
}
