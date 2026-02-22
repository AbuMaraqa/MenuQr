<?php

namespace App\Filament\Resources\MenuPages\Pages;

use App\Filament\Resources\MenuPages\MenuPageResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditMenuPage extends EditRecord
{
    protected static string $resource = MenuPageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
