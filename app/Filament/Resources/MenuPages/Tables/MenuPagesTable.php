<?php

namespace App\Filament\Resources\MenuPages\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class MenuPagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('image')
                    ->label(__('messages.menu_image'))
                    ->collection('image')
                    ->conversion('image'),
                TextColumn::make('name')
                    ->label(__('messages.menu_name')),
                TextColumn::make('category.name')
                    ->label(__('messages.category'))
            ])
            ->filters([
                SelectFilter::make('category_id')
                    ->label(__('messages.category'))
                    ->relationship('category', 'name')
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->reorderable('sort_order');
    }
}
