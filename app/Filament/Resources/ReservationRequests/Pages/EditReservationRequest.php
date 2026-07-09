<?php

namespace App\Filament\Resources\ReservationRequests\Pages;

use App\Filament\Resources\ReservationRequests\ReservationRequestResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditReservationRequest extends EditRecord
{
    protected static string $resource = ReservationRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
