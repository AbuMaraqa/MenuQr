<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                SpatieMediaLibraryFileUpload::make('image')
                    ->label(__('messages.category_image'))
                    ->collection('thumb')
                    ->disk('public')
                    ->columnSpanFull(),

                TextInput::make('name')
                    ->label(__('messages.category_name'))
                    ->required()
                    ->maxLength(255),

                Textarea::make('description')
                    ->label(__('messages.category_description'))
                    ->maxLength(255)
                    ->columnSpanFull(),

                TextInput::make('sort_order')
                    ->label(__('messages.category_sort_order'))
                    ->numeric()
                    ->default(fn () => \App\Models\Category::max('sort_order') + 1),

                Toggle::make('is_active')
                    ->label(__('messages.category_is_active'))
                    ->default(true),
            ]);
    }
}
