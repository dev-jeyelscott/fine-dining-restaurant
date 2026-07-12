<?php

namespace App\Filament\Resources\MenuItems\Tables;

use App\Models\MenuItem;
use App\Services\ResponsiveImageManager;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class MenuItemsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('responsive_thumbnail')
                    ->label('Image')
                    ->getStateUsing(fn (MenuItem $record): ?string => $record->responsiveImagePath(
                        ResponsiveImageManager::VARIANT_THUMBNAIL,
                    ))
                    ->disk('public')
                    ->square()
                    ->hiddenFrom('md'),

                TextColumn::make('menuCategory.name')
                    ->label('Category')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('price')
                    ->sortable()
                    ->hiddenFrom('lg'),

                TextColumn::make('sort_order')
                    ->sortable()
                    ->hiddenFrom('xl'),

                IconColumn::make('is_visible')
                    ->boolean()
                    ->label('Visible'),

                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('is_visible')
                    ->label('Visibility')
                    ->trueLabel('Visible')
                    ->falseLabel('Hidden'),
            ])
            ->emptyStateHeading('No menu items yet')
            ->emptyStateDescription('Add a menu item to begin building the menu.')
            ->emptyStateIcon('heroicon-o-book-open')
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
