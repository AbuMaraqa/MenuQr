<?php

namespace App\Filament\Resources\MenuPages\Schemas;

use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class MenuPageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                TextInput::make('name'),

                Toggle::make('is_active')
                    ->label('مفعل (إظهار الصفحة)')
                    ->default(true),

                SpatieMediaLibraryFileUpload::make('image')
                    ->label('صورة المنيو')
                    ->image()
                    ->disk('public')
                    ->collection('image')
                    ->imageEditor()
                    ->required()
                    ->columnSpanFull(),
            ]);
    }
}
