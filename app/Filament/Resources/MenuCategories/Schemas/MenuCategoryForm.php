<?php

namespace App\Filament\Resources\MenuCategories\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class MenuCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Category details')
                    ->description('Describe the category guests will see on the menu.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(160),
                        TextInput::make('slug')
                            ->required()
                            ->maxLength(180)
                            ->unique(ignoreRecord: true),
                        Textarea::make('description')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
                Section::make('Display settings')
                    ->description('Control the menu order and whether this category is shown publicly.')
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
