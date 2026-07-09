<?php

namespace App\Filament\Resources\ReservationRequests\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ReservationRequestInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('customer_name'),
                TextEntry::make('phone'),
                TextEntry::make('email'),
                TextEntry::make('preferred_date')->date(),
                TextEntry::make('preferred_time'),
                TextEntry::make('guest_count'),
                TextEntry::make('special_requests')->columnSpanFull(),
                TextEntry::make('is_banquet_or_event')->badge(),
                TextEntry::make('notification_sent_at')->dateTime(),
                TextEntry::make('created_at')->dateTime(),
            ]);
    }
}
