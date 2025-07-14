<?php

namespace App\Filament\Admin\Resources\CoursResource\Pages;

use App\Filament\Admin\Resources\CoursResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewCours extends ViewRecord
{
    protected static string $resource = CoursResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('horaires')
                ->label('Gérer les horaires')
                ->icon('heroicon-o-clock')
                ->color('info')
                ->url(fn () => CoursResource::getUrl('horaires', ['record' => $this->getRecord()])),
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
