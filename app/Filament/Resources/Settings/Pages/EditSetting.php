<?php

namespace App\Filament\Resources\Settings\Pages;

use App\Filament\Resources\Settings\SettingResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSetting extends EditRecord
{
    protected static string $resource = SettingResource::class;

    public function mount(int | string $record = 1): void
    {
        \App\Models\Setting::firstOrCreate(['id' => 1]);
        parent::mount(1);
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}
