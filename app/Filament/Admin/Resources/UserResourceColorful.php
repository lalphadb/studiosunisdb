<?php

namespace App\Filament\Admin\Resources;

use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\BadgeColumn;
use Illuminate\Database\Eloquent\Builder;

class UserResourceColorful extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = '🏫 Gestion École';
    protected static ?int $navigationSort = 1;
    protected static ?string $label = 'Utilisateur';
    protected static ?string $pluralLabel = 'Utilisateurs';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code_utilisateur')
                    ->label('🔢 Code')
                    ->badge()
                    ->color('primary')
                    ->searchable(),
                
                TextColumn::make('nom_complet')
                    ->label('👤 Nom complet')
                    ->searchable(['nom', 'prenom'])
                    ->sortable()
                    ->formatStateUsing(fn (string $state): string => "👤 {$state}"),
                
                TextColumn::make('email')
                    ->label('📧 Email')
                    ->searchable()
                    ->copyable()
                    ->color('info'),
                
                BadgeColumn::make('ecole.nom')
                    ->label('🏫 École')
                    ->colors([
                        'primary' => static fn ($state): bool => str_contains(strtolower($state), 'st-émile'),
                        'success' => static fn ($state): bool => str_contains(strtolower($state), 'dojo'),
                        'warning' => static fn ($state): bool => str_contains(strtolower($state), 'centre'),
                        'info' => static fn ($state): bool => !str_contains(strtolower($state), 'st-émile'),
                    ]),
                
                BadgeColumn::make('roles.name')
                    ->label('🎭 Rôles')
                    ->separator(',')
                    ->colors([
                        'danger' => ['superadmin'],
                        'warning' => ['admin', 'admin_ecole'],
                        'primary' => ['instructeur'],
                        'success' => ['membre'],
                    ])
                    ->formatStateUsing(fn (string $state): string => match($state) {
                        'superadmin' => '👑 Super Admin',
                        'admin' => '⚡ Admin',
                        'admin_ecole' => '🏫 Admin École',
                        'instructeur' => '🥋 Instructeur',
                        'membre' => '👨‍🎓 Membre',
                        default => $state,
                    }),
                
                IconColumn::make('actif')
                    ->label('📊 Statut')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->sortable(),
                
                TextColumn::make('created_at')
                    ->label('📅 Inscrit le')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->badge()
                    ->color('gray')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('nom')
            ->striped();
    }

    // Autres méthodes du Resource...
}
