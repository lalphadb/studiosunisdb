<?php

namespace App\Filament\Admin\Resources\SeminaireResource\Pages;

use App\Filament\Admin\Resources\SeminaireResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSeminaire extends EditRecord
{
    protected static string $resource = SeminaireResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
