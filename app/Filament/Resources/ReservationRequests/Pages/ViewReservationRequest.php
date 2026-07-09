<?php

namespace App\Filament\Resources\ReservationRequests\Pages;

use App\Filament\Resources\ReservationRequests\ReservationRequestResource;
use Filament\Resources\Pages\ViewRecord;

class ViewReservationRequest extends ViewRecord
{
    protected static string $resource = ReservationRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
