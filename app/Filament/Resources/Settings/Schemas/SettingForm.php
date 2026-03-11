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
                \Filament\Forms\Components\TextInput::make('site_title')
                    ->label(__('messages.site_title'))
                    ->nullable(),
                \Filament\Forms\Components\TextInput::make('categories_title')
                    ->label(__('messages.categories_title'))
                    ->nullable(),
                \Filament\Forms\Components\Select::make('swiper_effect')
                    ->label(__('messages.swiper_effect'))
                    ->options([
                        'slide' => 'Slide (سحب عادي)',
                        'fade' => 'Fade (تلاشي)',
                        'cube' => 'Cube (مكعب 3D)',
                        'coverflow' => 'Coverflow (تغطية متتالية)',
                        'flip' => 'Flip (تقليب صفحات 3D)',
                        'cards' => 'Cards (بطاقات متتالية)',
                        'creative' => 'Creative (حركة إبداعية مخصصة)',
                    ])
                    ->default('flip')
                    ->required(),
                SpatieMediaLibraryFileUpload::make('background')
                    ->collection('background')
                    ->disk('public')
                    ->label(__('messages.background_image'))
                    ,
            ]);
    }
}
