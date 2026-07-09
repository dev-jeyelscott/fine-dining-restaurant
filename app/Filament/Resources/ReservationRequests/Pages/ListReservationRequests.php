<?php

namespace App\Filament\Resources\ReservationRequests\Pages;

use App\Filament\Resources\ReservationRequests\ReservationRequestResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListReservationRequests extends ListRecords
{
    protected static string $resource = ReservationRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
