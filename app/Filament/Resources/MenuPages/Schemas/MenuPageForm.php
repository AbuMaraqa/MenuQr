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

                TextInput::make('name')
                    ->label(__('messages.name')),

                Select::make('category_id')
                    ->label(__('messages.category'))
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                TextInput::make('sort_order')
                    ->label(__('messages.sort_order'))
                    ->numeric()
                    ->default(fn () => \App\Models\MenuPage::max('sort_order') + 1),

                Toggle::make('is_active')
                    ->label(__('messages.is_active'))
                    ->default(true),

                SpatieMediaLibraryFileUpload::make('image')
                    ->label(__('messages.menu_image'))
                    ->image()
                    ->disk('public')
                    ->collection('image')
                    ->conversion('image')
                    ->imageEditor()
                    ->required()
                    ->columnSpanFull(),
            ]);
    }
}
