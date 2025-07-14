<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\CoursResource\Pages;

use App\Filament\Admin\Resources\CoursResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;

class ListCours extends ListRecords
{
    protected static string $resource = CoursResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Nouveau cours')
                ->icon('heroicon-o-plus'),
        ];
    }

    public function getTabs(): array
    {
        return [
            'tous' => Tab::make('Tous les cours'),
            'automne' => Tab::make('🍂 Automne')
                ->modifyQueryUsing(fn ($query) => $query->where('saison', 'automne')),
            'hiver' => Tab::make('❄️ Hiver')
                ->modifyQueryUsing(fn ($query) => $query->where('saison', 'hiver')),
            'printemps' => Tab::make('🌸 Printemps')
                ->modifyQueryUsing(fn ($query) => $query->where('saison', 'printemps')),
            'ete' => Tab::make('☀️ Été')
                ->modifyQueryUsing(fn ($query) => $query->where('saison', 'ete')),
        ];
    }
}
