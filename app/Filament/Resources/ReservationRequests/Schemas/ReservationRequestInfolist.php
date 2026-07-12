<?php

namespace App\Filament\Resources\ReservationRequests\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ReservationRequestInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Guest contact')
                    ->description('Use these details to follow up with the guest about this reservation request.')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('customer_name')->label('Guest name'),
                        TextEntry::make('phone')->copyable(),
                        TextEntry::make('email')->copyable(),
                    ]),
                Section::make('Reservation request')
                    ->description('This is a request for staff review and is not a confirmed reservation.')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('preferred_date')->date(),
                        TextEntry::make('preferred_time')->label('Preferred time'),
                        TextEntry::make('guest_count')->label('Guests'),
                        TextEntry::make('is_banquet_or_event')
                            ->label('Request type')
                            ->badge()
                            ->formatStateUsing(fn (bool $state): string => $state ? 'Banquet or event' : 'Dining reservation')
                            ->color(fn (bool $state): string => $state ? 'info' : 'gray'),
                        TextEntry::make('special_requests')
                            ->placeholder('No special requests were provided.')
                            ->columnSpanFull(),
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
