<?php

namespace App\Filament\Resources\OrderInquiries\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class OrderInquiryInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('customer_name'),
                TextEntry::make('phone'),
                TextEntry::make('email'),
                TextEntry::make('fulfillment_type')->badge(),
                TextEntry::make('preferred_time'),
                TextEntry::make('order_details')->columnSpanFull(),
                TextEntry::make('special_instructions')->columnSpanFull(),
                TextEntry::make('delivery_address')->columnSpanFull(),
                TextEntry::make('notification_sent_at')->dateTime(),
                TextEntry::make('created_at')->dateTime(),
            ]);
    }
}
