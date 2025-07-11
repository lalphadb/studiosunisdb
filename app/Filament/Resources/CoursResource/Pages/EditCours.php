<?php

namespace App\Filament\Resources\CoursResource\Pages;

use App\Filament\Resources\CoursResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditCours extends EditRecord
{
    protected static string $resource = CoursResource::class;

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
            ->title('Cours mis à jour')
            ->body('Le cours a été mis à jour avec succès.');
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Si pas super-admin, ne pas permettre de changer l'école
        if (!auth()->user()?->hasRole('super-admin')) {
            unset($data['ecole_id']);
        }

        return $data;
    }
}
