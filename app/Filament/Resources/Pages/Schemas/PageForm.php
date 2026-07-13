<?php

namespace App\Filament\Resources\Pages\Schemas;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class PageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Page editor')
                    ->tabs([
                        Tab::make('Page details')
                            ->schema([
                                Section::make('Page content')
                                    ->description(
                                        'Create the page content guests will read on the restaurant website.'
                                    )
                                    ->columns([
                                        'default' => 1,
                                        'md' => 2,
                                    ])
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
                                    ])
                                    ->columnSpanFull(),

                                Section::make('Publication')
                                    ->description(
                                        'Choose whether this page is visible on the public website.'
                                    )
                                    ->schema([
                                        Toggle::make('is_published')
                                            ->label('Published')
                                            ->default(true),
                                    ])
                                    ->columnSpanFull(),
                            ]),

                        Tab::make('SEO details')
                            ->schema([
                                Section::make('Search preview')
                                    ->description(
                                        'Use concise metadata to help guests understand this page in search results.'
                                    )
                                    ->columns([
                                        'default' => 1,
                                        'md' => 2,
                                    ])
                                    ->schema([
                                        TextInput::make('meta_title')
                                            ->label('Meta title')
                                            ->maxLength(180)
                                            ->columnSpanFull(),

                                        Textarea::make('meta_description')
                                            ->label('Meta description')
                                            ->rows(4)
                                            ->maxLength(255)
                                            ->columnSpanFull(),
                                    ])
                                    ->columnSpanFull(),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
