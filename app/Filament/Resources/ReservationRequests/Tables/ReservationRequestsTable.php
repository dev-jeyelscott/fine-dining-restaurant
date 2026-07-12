<?php

namespace App\Filament\Resources\ReservationRequests\Tables;

use App\Models\ReservationRequest;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ReservationRequestsTable
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

                TextColumn::make('preferred_date')
                    ->date()
                    ->sortable(),

                TextColumn::make('preferred_time')
                    ->sortable()
                    ->hiddenFrom('lg'),

                TextColumn::make('guest_count')
                    ->sortable()
                    ->hiddenFrom('xl'),

                IconColumn::make('is_banquet_or_event')
                    ->boolean()
                    ->label('Event')
                    ->hiddenFrom('xl'),

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
            ->emptyStateHeading('No reservation requests yet')
            ->emptyStateDescription('New reservation requests will appear here for staff review.')
            ->emptyStateIcon('heroicon-o-calendar-days')
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
