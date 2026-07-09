<?php

namespace App\Filament\Resources\GalleryImages\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class GalleryImageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->maxLength(180),

                TextInput::make('alt_text')
                    ->maxLength(255),

                FileUpload::make('image_path')
                    ->image()
                    ->required()
                    ->disk('public')
                    ->directory('gallery')
                    ->visibility('public')
                    ->maxSize(2048),

                Select::make('category')
                    ->options([
                        'interior' => 'Interior',
                        'dish' => 'Dish',
                        'event' => 'Event',
                        'ambiance' => 'Ambiance',
                        'banquet' => 'Banquet',
                    ])
                    ->searchable(),

                TextInput::make('sort_order')
                    ->numeric()
                    ->default(0)
                    ->required(),

                Toggle::make('is_visible')
                    ->default(true),
            ]);
    }
}
