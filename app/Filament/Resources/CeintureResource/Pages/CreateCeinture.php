<?php

namespace App\Filament\Resources\CeintureResource\Pages;

use App\Filament\Resources\CeintureResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateCeinture extends CreateRecord
{
    protected static string $resource = CeintureResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Ceinture créée')
            ->body('La ceinture a été ajoutée au système.');
    }
}
