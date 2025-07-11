<?php

namespace App\Filament\Resources\CeintureResource\Pages;

use App\Filament\Resources\CeintureResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditCeinture extends EditRecord
{
    protected static string $resource = CeintureResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make()
                ->requiresConfirmation(),
        ];
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Ceinture mise à jour')
            ->body('La ceinture a été mise à jour avec succès.');
    }
}
