<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Générer le code utilisateur
        $lastUser = User::orderBy('id', 'desc')->first();
        $number = $lastUser ? intval(substr($lastUser->code_utilisateur, 1)) + 1 : 1;
        $data['code_utilisateur'] = 'U' . str_pad($number, 6, '0', STR_PAD_LEFT);
        
        // Si l'utilisateur n'est pas super-admin, forcer son école
        if (!auth()->user()->hasRole('super-admin')) {
            $data['ecole_id'] = auth()->user()->ecole_id;
        }
        
        // Définir email_verified_at si l'utilisateur est créé par un admin
        if (auth()->user()->hasAnyRole(['super-admin', 'admin'])) {
            $data['email_verified_at'] = now();
        }

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Utilisateur créé avec succès';
    }
}
