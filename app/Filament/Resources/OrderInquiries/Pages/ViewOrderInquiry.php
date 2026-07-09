<?php

namespace App\Filament\Resources\OrderInquiries\Pages;

use App\Filament\Resources\OrderInquiries\OrderInquiryResource;
use Filament\Resources\Pages\ViewRecord;

class ViewOrderInquiry extends ViewRecord
{
    protected static string $resource = OrderInquiryResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
