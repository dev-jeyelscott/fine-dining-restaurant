<?php

namespace App\Filament\Resources\ContactInquiries\Tables;

use App\Models\ContactInquiry;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ContactInquiriesTable
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

                TextColumn::make('email')
                    ->searchable(),

                TextColumn::make('phone')
                    ->searchable(),

                TextColumn::make('subject')
                    ->searchable(),

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
                    ->visible(fn (ContactInquiry $record): bool => ! $record->is_read)
                    ->action(fn (ContactInquiry $record): bool => $record->update(['is_read' => true])),
                Action::make('markAsUnreviewed')
                    ->label('Mark as unreviewed')
                    ->visible(fn (ContactInquiry $record): bool => $record->is_read)
                    ->action(fn (ContactInquiry $record): bool => $record->update(['is_read' => false])),
            ]);
    }
}
