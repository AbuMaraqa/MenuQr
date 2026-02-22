<?php

namespace App\Filament\Resources\MenuPages;

use App\Filament\Resources\MenuPages\Pages\CreateMenuPage;
use App\Filament\Resources\MenuPages\Pages\EditMenuPage;
use App\Filament\Resources\MenuPages\Pages\ListMenuPages;
use App\Filament\Resources\MenuPages\Schemas\MenuPageForm;
use App\Filament\Resources\MenuPages\Tables\MenuPagesTable;
use App\Models\MenuPage;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class MenuPageResource extends Resource
{
    protected static ?string $model = MenuPage::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'MenuPage';

    public static function form(Schema $schema): Schema
    {
        return MenuPageForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MenuPagesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMenuPages::route('/'),
            'create' => CreateMenuPage::route('/create'),
            'edit' => EditMenuPage::route('/{record}/edit'),
        ];
    }
}
