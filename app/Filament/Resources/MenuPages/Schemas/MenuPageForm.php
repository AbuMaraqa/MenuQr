<?php

namespace App\Filament\Resources\MenuPages\Schemas;

use Filament\Forms\Components\Select;
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

                Select::make('category_id')
                    ->label('التصنيف')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

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
