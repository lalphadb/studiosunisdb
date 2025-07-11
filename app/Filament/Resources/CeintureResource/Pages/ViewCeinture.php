<?php

namespace App\Filament\Resources\CeintureResource\Pages;

use App\Filament\Resources\CeintureResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewCeinture extends ViewRecord
{
    protected static string $resource = CeintureResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
