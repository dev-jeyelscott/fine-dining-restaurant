<?php

namespace App\Filament\Resources\OrderInquiries\Tables;

use App\Models\OrderInquiry;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class OrderInquiriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('is_read')
                    ->label('Review status')
                    ->badge()
                    ->formatStateUsing(fn (bool $state): string => $state ? 'Reviewed' : 'New')
                    ->color(fn (bool $state): string => $state ? 'success' : 'warning'),

                TextColumn::make('customer_name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('phone')
                    ->searchable()
                    ->hiddenFrom('md'),

                TextColumn::make('email')
                    ->searchable()
                    ->hiddenFrom('lg'),

                TextColumn::make('fulfillment_type')
                    ->badge()
                    ->sortable(),

                TextColumn::make('preferred_time')
                    ->sortable()
                    ->hiddenFrom('lg'),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('is_read')
                    ->label('Review status')
                    ->trueLabel('Reviewed')
                    ->falseLabel('New'),
            ])
            ->emptyStateHeading('No order inquiries yet')
            ->emptyStateDescription('New order inquiries will appear here for staff review.')
            ->emptyStateIcon('heroicon-o-shopping-bag')
            ->recordActions([
                ViewAction::make(),
                Action::make('markAsReviewed')
                    ->label('Mark as reviewed')
                    ->visible(fn (OrderInquiry $record): bool => ! $record->is_read)
                    ->action(fn (OrderInquiry $record): bool => $record->update(['is_read' => true])),
                Action::make('markAsUnreviewed')
                    ->label('Mark as unreviewed')
                    ->visible(fn (OrderInquiry $record): bool => $record->is_read)
                    ->action(fn (OrderInquiry $record): bool => $record->update(['is_read' => false])),
            ]);
    }
}
