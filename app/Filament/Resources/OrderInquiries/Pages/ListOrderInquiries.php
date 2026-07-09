<?php

namespace App\Filament\Resources\OrderInquiries\Pages;

use App\Filament\Resources\OrderInquiries\OrderInquiryResource;
use Filament\Resources\Pages\ListRecords;

class ListOrderInquiries extends ListRecords
{
    protected static string $resource = OrderInquiryResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
