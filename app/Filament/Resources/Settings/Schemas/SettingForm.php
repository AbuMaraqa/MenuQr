<?php

namespace App\Filament\Resources\Settings\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
class SettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                SpatieMediaLibraryFileUpload::make('background')
                    ->collection('background')
                    ->disk('public')
                    ->label('Background Image')
                    ->required(),
            ]);
    }
}
