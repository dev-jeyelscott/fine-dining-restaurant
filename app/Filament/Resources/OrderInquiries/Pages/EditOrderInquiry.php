<?php

namespace App\Filament\Resources\OrderInquiries\Pages;

use App\Filament\Resources\OrderInquiries\OrderInquiryResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditOrderInquiry extends EditRecord
{
    protected static string $resource = OrderInquiryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
