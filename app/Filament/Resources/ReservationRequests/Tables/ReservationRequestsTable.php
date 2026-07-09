<?php

namespace App\Filament\Resources\ReservationRequests\Tables;

use App\Models\ReservationRequest;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ReservationRequestsTable
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

                TextColumn::make('preferred_date')
                    ->date()
                    ->sortable(),

                TextColumn::make('preferred_time')
                    ->sortable(),

                TextColumn::make('guest_count')
                    ->sortable(),

                IconColumn::make('is_banquet_or_event')
                    ->boolean()
                    ->label('Banquet/Event'),

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
                    ->visible(fn (ReservationRequest $record): bool => ! $record->is_read)
                    ->action(fn (ReservationRequest $record): bool => $record->update(['is_read' => true])),
                Action::make('markAsUnreviewed')
                    ->label('Mark as unreviewed')
                    ->visible(fn (ReservationRequest $record): bool => $record->is_read)
                    ->action(fn (ReservationRequest $record): bool => $record->update(['is_read' => false])),
            ]);
    }
}
