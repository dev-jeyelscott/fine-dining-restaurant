<?php

namespace App\Filament\Resources\ContactInquiries\Tables;

use App\Models\ContactInquiry;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ContactInquiriesTable
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

                TextColumn::make('email')
                    ->searchable()
                    ->visibleFrom('md'),

                TextColumn::make('phone')
                    ->searchable()
                    ->visibleFrom('lg'),

                TextColumn::make('subject')
                    ->searchable()
                    ->visibleFrom('lg'),

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
            ->emptyStateHeading('No contact inquiries yet')
            ->emptyStateDescription('New contact inquiries will appear here for staff review.')
            ->emptyStateIcon('heroicon-o-envelope')
            ->recordActions([
                ViewAction::make(),
                Action::make('markAsReviewed')
                    ->label('Mark as reviewed')
                    ->visible(fn (ContactInquiry $record): bool => ! $record->is_read)
                    ->action(fn (ContactInquiry $record): bool => $record->update(['is_read' => true])),
                Action::make('markAsUnreviewed')
                    ->label('Mark as unreviewed')
                    ->visible(fn (ContactInquiry $record): bool => $record->is_read)
                    ->action(fn (ContactInquiry $record): bool => $record->update(['is_read' => false])),
            ]);
    }
}
