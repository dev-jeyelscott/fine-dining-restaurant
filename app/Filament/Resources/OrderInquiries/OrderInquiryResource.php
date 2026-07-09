<?php

namespace App\Filament\Resources\OrderInquiries;

use App\Filament\Resources\OrderInquiries\Pages\ListOrderInquiries;
use App\Filament\Resources\OrderInquiries\Pages\ViewOrderInquiry;
use App\Filament\Resources\OrderInquiries\Schemas\OrderInquiryForm;
use App\Filament\Resources\OrderInquiries\Schemas\OrderInquiryInfolist;
use App\Filament\Resources\OrderInquiries\Tables\OrderInquiriesTable;
use App\Models\OrderInquiry;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class OrderInquiryResource extends Resource
{
    protected static ?string $model = OrderInquiry::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return OrderInquiryForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return OrderInquiryInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return OrderInquiriesTable::configure($table);
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
            'index' => ListOrderInquiries::route('/'),
            'view' => ViewOrderInquiry::route('/{record}'),
        ];
    }
}
