<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

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
            ->title('Utilisateur mis à jour')
            ->body('Les informations ont été mises à jour avec succès.');
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
