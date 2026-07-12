<?php

namespace App\Filament\Resources\GalleryImages\Schemas;

use App\Rules\SafeImageDimensions;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class GalleryImageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Image details')
                    ->description('Use a descriptive title and alt text so gallery content is easy to manage and accessible.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('title')
                            ->maxLength(180),
                        TextInput::make('alt_text')
                            ->maxLength(255),
                        Select::make('category')
                            ->options([
                                'interior' => 'Interior',
                                'dish' => 'Dish',
                                'event' => 'Event',
                                'ambiance' => 'Ambiance',
                                'banquet' => 'Banquet',
                            ])
                            ->searchable(),
                    ]),
                Section::make('Gallery image')
                    ->description('Upload a high-quality image that meets the restaurant image requirements.')
                    ->schema([
                        FileUpload::make('image_path')
                            ->image()
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->rules([new SafeImageDimensions])
                            ->required()
                            ->disk('public')
                            ->directory('gallery')
                            ->visibility('public')
                            ->maxSize(2048)
                            ->helperText('JPEG, PNG, or WebP up to 2 MB. Responsive derivatives are generated automatically.'),
                    ]),
                Section::make('Display settings')
                    ->description('Control the gallery order and public visibility of this image.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('sort_order')
                            ->numeric()
                            ->default(0)
                            ->required(),
                        Toggle::make('is_visible')
                            ->default(true),
                    ]),
            ]);
    }
}
