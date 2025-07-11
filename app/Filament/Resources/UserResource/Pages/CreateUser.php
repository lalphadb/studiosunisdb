<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Utilisateur créé')
            ->body('L\'utilisateur a été ajouté avec succès.');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Si pas super-admin, forcer l'école de l'utilisateur connecté
        if (!auth()->user()?->hasRole('super-admin')) {
            $data['ecole_id'] = auth()->user()?->ecole_id;
        }

        // Générer le code utilisateur automatiquement
        $data['code_utilisateur'] = $this->generateUserCode();

        return $data;
    }

    private function generateUserCode(): string
    {
        $lastUser = \App\Models\User::orderBy('id', 'desc')->first();
        $nextNumber = $lastUser ? $lastUser->id + 1 : 1;
        return 'U' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
    }
}
