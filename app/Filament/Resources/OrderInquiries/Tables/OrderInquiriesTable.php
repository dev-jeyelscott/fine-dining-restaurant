<?php

namespace App\Filament\Resources\OrderInquiries\Tables;

use App\Models\OrderInquiry;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class OrderInquiriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                IconColumn::make('is_read')
                    ->boolean()
                    ->label('Reviewed'),

                TextColumn::make('customer_name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('phone')
                    ->searchable(),

                TextColumn::make('email')
                    ->searchable(),

                TextColumn::make('fulfillment_type')
                    ->badge()
                    ->sortable(),

                TextColumn::make('preferred_time')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
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
