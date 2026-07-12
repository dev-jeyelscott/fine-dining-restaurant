<?php

namespace App\Filament\Resources\ContactInquiries\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ContactInquiryInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Customer contact')
                    ->description('Use these details to follow up about this contact inquiry.')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('customer_name')->label('Customer name'),
                        TextEntry::make('email')->copyable(),
                        TextEntry::make('phone')
                            ->copyable()
                            ->placeholder('No phone number was provided.'),
                    ]),
                Section::make('Message')
                    ->description('This inquiry is awaiting manual restaurant review.')
                    ->schema([
                        TextEntry::make('subject')->placeholder('No subject was provided.'),
                        TextEntry::make('message')->columnSpanFull(),
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
