<?php

namespace App\Filament\Resources\OrderInquiries\Schemas;

use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class OrderInquiryInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Customer contact')
                    ->description('Use these details to follow up about this order inquiry.')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('customer_name')->label('Customer name'),
                        TextEntry::make('phone')->copyable(),
                        TextEntry::make('email')->copyable(),
                    ]),
                Section::make('Order inquiry')
                    ->description('This is an inquiry for staff review and is not a paid or fulfilled order.')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('fulfillment_type')
                            ->label('Preference')
                            ->badge()
                            ->formatStateUsing(fn (string $state): string => ucfirst($state)),
                        TextEntry::make('preferred_time')->label('Preferred time'),
                        TextEntry::make('delivery_address')
                            ->visible(fn (string $state): bool => filled($state))
                            ->columnSpanFull(),
                        TextEntry::make('order_details')
                            ->label('Order details')
                            ->placeholder('No additional order details were provided.')
                            ->columnSpanFull(),
                        TextEntry::make('special_instructions')
                            ->placeholder('No special instructions were provided.')
                            ->columnSpanFull(),
                    ]),
                Section::make('Requested items')
                    ->description('Items provided by the customer. No totals or fulfillment controls are shown.')
                    ->visible(fn ($record): bool => $record->items()->exists())
                    ->schema([
                        RepeatableEntry::make('items')
                            ->contained(false)
                            ->columns(3)
                            ->schema([
                                TextEntry::make('item_name_snapshot')->label('Item'),
                                TextEntry::make('quantity')->label('Quantity'),
                                TextEntry::make('notes')
                                    ->placeholder('No item notes')
                                    ->columnSpanFull(),
                            ]),
                    ]),
                Section::make('Review details')
                    ->description('Review markers support internal staff follow-up only.')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('is_read')
                            ->label('Review status')
                            ->badge()
                            ->formatStateUsing(fn (bool $state): string => $state ? 'Reviewed' : 'New')
                            ->color(fn (bool $state): string => $state ? 'success' : 'warning'),
                        TextEntry::make('created_at')->label('Submitted')->dateTime(),
                        TextEntry::make('notification_sent_at')
                            ->label('Notification sent')
                            ->placeholder('Not recorded'),
                    ]),
            ]);
    }
}
