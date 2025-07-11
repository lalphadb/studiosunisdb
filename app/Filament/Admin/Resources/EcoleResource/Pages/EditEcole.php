<?php

namespace App\Filament\Admin\Resources\EcoleResource\Pages;

use App\Filament\Admin\Resources\EcoleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEcole extends EditRecord
{
    protected static string $resource = EcoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
