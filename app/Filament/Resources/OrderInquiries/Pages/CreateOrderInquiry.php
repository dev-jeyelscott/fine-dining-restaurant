<?php

namespace App\Filament\Resources\OrderInquiries\Pages;

use App\Filament\Resources\OrderInquiries\OrderInquiryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateOrderInquiry extends CreateRecord
{
    protected static string $resource = OrderInquiryResource::class;
}
