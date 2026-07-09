<?php

namespace App\Filament\Resources\ContactInquiries\Pages;

use App\Filament\Resources\ContactInquiries\ContactInquiryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateContactInquiry extends CreateRecord
{
    protected static string $resource = ContactInquiryResource::class;
}
