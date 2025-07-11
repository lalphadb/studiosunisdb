<?php

namespace App\Filament\Resources\EcoleResource\Pages;

use App\Filament\Resources\EcoleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditEcole extends EditRecord
{
    protected static string $resource = EcoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make()
                ->label('Voir')
                ->icon('heroicon-o-eye'),
            Actions\DeleteAction::make()
                ->requiresConfirmation()
                ->modalHeading('Supprimer l\'école')
                ->modalDescription('Êtes-vous sûr de vouloir supprimer cette école ? Cette action est irréversible et supprimera toutes les données associées.')
                ->modalSubmitActionLabel('Oui, supprimer'),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('École mise à jour')
            ->body('Les informations de l\'école ont été mises à jour avec succès.');
    }

    public function getTitle(): string
    {
        return 'Modifier - ' . $this->record->nom;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // S'assurer que le code est en majuscules
        $data['code'] = strtoupper($data['code']);
        
        return $data;
    }
}
