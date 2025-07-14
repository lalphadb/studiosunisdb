<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\CoursResource\Pages;

use App\Filament\Admin\Resources\CoursResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateCours extends CreateRecord
{
    protected static string $resource = CoursResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['ecole_id'] = auth()->user()->ecole_id;
        return $data;
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Cours créé avec succès')
            ->body('Le cours "' . $this->getRecord()->nom . '" est maintenant disponible.');
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
