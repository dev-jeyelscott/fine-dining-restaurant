<?php

namespace App\Filament\Resources\GalleryImages\Tables;

use App\Models\GalleryImage;
use App\Services\ResponsiveImageManager;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class GalleryImagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('responsive_thumbnail')
                    ->label('Image')
                    ->getStateUsing(fn (GalleryImage $record): ?string => $record->responsiveImagePath(
                        ResponsiveImageManager::VARIANT_THUMBNAIL,
                    ))
                    ->disk('public')
                    ->square()
                    ->visibleFrom('md'),

                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('category')
                    ->badge()
                    ->sortable()
                    ->visibleFrom('lg'),

                TextColumn::make('sort_order')
                    ->sortable()
                    ->visibleFrom('xl'),

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
            ->emptyStateHeading('No gallery images yet')
            ->emptyStateDescription('Add an image to begin building the gallery.')
            ->emptyStateIcon('heroicon-o-photo')
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
