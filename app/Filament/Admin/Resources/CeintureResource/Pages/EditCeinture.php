<?php

namespace App\Filament\Admin\Resources\CeintureResource\Pages;

use App\Filament\Admin\Resources\CeintureResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCeinture extends EditRecord
{
    protected static string $resource = CeintureResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
