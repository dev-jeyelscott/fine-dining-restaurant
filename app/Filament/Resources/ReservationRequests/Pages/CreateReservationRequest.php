<?php

namespace App\Filament\Resources\ReservationRequests\Pages;

use App\Filament\Resources\ReservationRequests\ReservationRequestResource;
use Filament\Resources\Pages\CreateRecord;

class CreateReservationRequest extends CreateRecord
{
    protected static string $resource = ReservationRequestResource::class;
}
