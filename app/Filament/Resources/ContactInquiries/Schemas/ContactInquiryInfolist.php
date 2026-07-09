<?php

namespace App\Filament\Resources\ContactInquiries\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ContactInquiryInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('customer_name'),
                TextEntry::make('email'),
                TextEntry::make('phone'),
                TextEntry::make('subject'),
                TextEntry::make('message')->columnSpanFull(),
                TextEntry::make('notification_sent_at')->dateTime(),
                TextEntry::make('created_at')->dateTime(),
            ]);
    }
}
