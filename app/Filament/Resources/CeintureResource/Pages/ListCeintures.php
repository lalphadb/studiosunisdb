<?php

namespace App\Filament\Resources\CeintureResource\Pages;

use App\Filament\Resources\CeintureResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCeintures extends ListRecords
{
    protected static string $resource = CeintureResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Nouvelle ceinture')
                ->icon('heroicon-o-plus'),
        ];
    }

    public function getTitle(): string
    {
        return 'Système de Ceintures';
    }
}
