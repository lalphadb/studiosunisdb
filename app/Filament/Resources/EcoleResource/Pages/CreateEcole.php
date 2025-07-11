<?php

namespace App\Filament\Resources\EcoleResource\Pages;

use App\Filament\Resources\EcoleResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateEcole extends CreateRecord
{
    protected static string $resource = EcoleResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('École créée')
            ->body('L\'école a été ajoutée avec succès au réseau StudiosUnisDB.');
    }

    public function getTitle(): string
    {
        return 'Nouvelle École StudiosUnisDB';
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // S'assurer que le code est en majuscules
        $data['code'] = strtoupper($data['code']);
        
        return $data;
    }
}
