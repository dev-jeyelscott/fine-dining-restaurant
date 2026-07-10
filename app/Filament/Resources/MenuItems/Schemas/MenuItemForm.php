<?php

namespace App\Filament\Resources\MenuItems\Schemas;

use App\Rules\SafeImageDimensions;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class MenuItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('menu_category_id')
                    ->relationship('menuCategory', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),

                TextInput::make('name')
                    ->required()
                    ->maxLength(180),

                TextInput::make('slug')
                    ->maxLength(200)
                    ->unique(ignoreRecord: true),

                Textarea::make('description')
                    ->rows(3)
                    ->columnSpanFull(),

                TextInput::make('price')
                    ->numeric()
                    ->prefix('$'),

                FileUpload::make('image_path')
                    ->image()
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                    ->rules([new SafeImageDimensions])
                    ->disk('public')
                    ->directory('menu-items')
                    ->visibility('public')
                    ->maxSize(2048)
                    ->helperText('JPEG, PNG, or WebP up to 2 MB. Responsive derivatives are generated automatically.'),

                TextInput::make('sort_order')
                    ->numeric()
                    ->default(0)
                    ->required(),

                Toggle::make('is_visible')
                    ->default(true),
            ]);
    }
}
