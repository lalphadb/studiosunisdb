<?php

namespace App\Filament\Admin\Resources\PresenceResource\Pages;

use App\Filament\Admin\Resources\PresenceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPresence extends EditRecord
{
    protected static string $resource = PresenceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
