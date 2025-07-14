<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\CoursResource\Pages;

use App\Filament\Admin\Resources\CoursResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCours extends EditRecord
{
    protected static string $resource = CoursResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
