<?php

namespace App\Filament\Resources\ReservationRequests;

use App\Filament\Resources\ReservationRequests\Pages\ListReservationRequests;
use App\Filament\Resources\ReservationRequests\Pages\ViewReservationRequest;
use App\Filament\Resources\ReservationRequests\Schemas\ReservationRequestForm;
use App\Filament\Resources\ReservationRequests\Schemas\ReservationRequestInfolist;
use App\Filament\Resources\ReservationRequests\Tables\ReservationRequestsTable;
use App\Models\ReservationRequest;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ReservationRequestResource extends Resource
{
    protected static ?string $model = ReservationRequest::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Customer Inquiries';

    protected static ?string $navigationLabel = 'Reservation Requests';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;

    protected static ?int $navigationSort = 10;

    public static function form(Schema $schema): Schema
    {
        return ReservationRequestForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ReservationRequestInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ReservationRequestsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListReservationRequests::route('/'),
            'view' => ViewReservationRequest::route('/{record}'),
        ];
    }
}
