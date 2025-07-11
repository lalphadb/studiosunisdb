<?php

namespace App\Filament\Admin\Resources\SeminaireResource\Pages;

use App\Filament\Admin\Resources\SeminaireResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSeminaires extends ListRecords
{
    protected static string $resource = SeminaireResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
