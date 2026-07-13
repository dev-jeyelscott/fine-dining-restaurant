<?php

namespace App\Filament\Resources\GalleryImages\Schemas;

use App\Rules\SafeImageDimensions;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class GalleryImageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make()
                    ->columns([
                        'default' => 1,
                        'xl' => 3,
                    ])
                    ->schema([
                        Grid::make(1)
                            ->schema([
                                Section::make('Image details')
                                    ->description(
                                        'Use a descriptive title and alt text so gallery content is easy to manage and accessible.'
                                    )
                                    ->columns([
                                        'default' => 1,
                                        'md' => 2,
                                    ])
                                    ->schema([
                                        TextInput::make('title')
                                            ->maxLength(180),

                                        TextInput::make('alt_text')
                                            ->label('Alt text')
                                            ->maxLength(255),

                                        Select::make('category')
                                            ->options([
                                                'interior' => 'Interior',
                                                'dish' => 'Dish',
                                                'event' => 'Event',
                                                'ambiance' => 'Ambiance',
                                                'banquet' => 'Banquet',
                                            ])
                                            ->searchable()
                                            ->columnSpanFull(),
                                    ])
                                    ->columnSpanFull(),

                                Section::make('Display settings')
                                    ->description(
                                        'Control the gallery order and public visibility of this image.'
                                    )
                                    ->columns([
                                        'default' => 1,
                                        'md' => 2,
                                    ])
                                    ->schema([
                                        TextInput::make('sort_order')
                                            ->label('Sort order')
                                            ->numeric()
                                            ->default(0)
                                            ->required(),

                                        Toggle::make('is_visible')
                                            ->label('Visible publicly')
                                            ->default(true),
                                    ])
                                    ->columnSpanFull(),
                            ])
                            ->columnSpan([
                                'default' => 1,
                                'xl' => 2,
                            ]),

                        Section::make('Gallery image')
                            ->description(
                                'Upload a high-quality image that meets the restaurant image requirements.'
                            )
                            ->schema([
                                FileUpload::make('image_path')
                                    ->label('Image')
                                    ->image()
                                    ->acceptedFileTypes([
                                        'image/jpeg',
                                        'image/png',
                                        'image/webp',
                                    ])
                                    ->rules([
                                        new SafeImageDimensions,
                                    ])
                                    ->required()
                                    ->disk('public')
                                    ->directory('gallery')
                                    ->visibility('public')
                                    ->maxSize(2048)
                                    ->helperText(
                                        'JPEG, PNG, or WebP up to 2 MB. Responsive derivatives are generated automatically.'
                                    )
                                    ->columnSpanFull(),
                            ])
                            ->columnSpan([
                                'default' => 1,
                                'xl' => 1,
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
