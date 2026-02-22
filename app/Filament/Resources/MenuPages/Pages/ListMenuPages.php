<?php

namespace App\Filament\Resources\MenuPages\Pages;

use App\Filament\Resources\MenuPages\MenuPageResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMenuPages extends ListRecords
{
    protected static string $resource = MenuPageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
