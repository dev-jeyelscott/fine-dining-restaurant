<?php

namespace App\Filament\Resources\Pages\Schemas;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Page content')
                    ->description('Create the page content guests will read on the restaurant website.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->maxLength(180),
                        TextInput::make('slug')
                            ->required()
                            ->maxLength(160)
                            ->unique(ignoreRecord: true),
                        Textarea::make('excerpt')
                            ->rows(3)
                            ->columnSpanFull(),
                        RichEditor::make('content')
                            ->columnSpanFull(),
                    ]),
                Section::make('Search preview')
                    ->description('Use concise metadata to help guests understand this page in search results.')
                    ->schema([
                        TextInput::make('meta_title')
                            ->maxLength(180),
                        Textarea::make('meta_description')
                            ->rows(3)
                            ->maxLength(255)
                            ->columnSpanFull(),
                    ]),
                Section::make('Publication')
                    ->description('Choose whether this page is visible on the public website.')
                    ->schema([
                        Toggle::make('is_published')
                            ->default(true),
                    ]),
            ]);
    }
}
