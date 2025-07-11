<?php

namespace App\Filament\Resources\CoursResource\Pages;

use App\Filament\Resources\CoursResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateCours extends CreateRecord
{
    protected static string $resource = CoursResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Cours créé')
            ->body('Le cours a été créé avec succès.');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Si pas super-admin, forcer l'école de l'utilisateur connecté
        if (!auth()->user()?->hasRole('super-admin')) {
            $data['ecole_id'] = auth()->user()?->ecole_id;
        }

        return $data;
    }
}
