<?php

namespace App\Filament\Resources\Pages\Schemas;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class PageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('slug')
                    ->required()
                    ->maxLength(160)
                    ->unique(ignoreRecord: true),

                TextInput::make('title')
                    ->required()
                    ->maxLength(180),

                Textarea::make('excerpt')
                    ->rows(3)
                    ->columnSpanFull(),

                RichEditor::make('content')
                    ->columnSpanFull(),

                TextInput::make('meta_title')
                    ->maxLength(180),

                Textarea::make('meta_description')
                    ->rows(3)
                    ->maxLength(255)
                    ->columnSpanFull(),

                Toggle::make('is_published')
                    ->default(true),
            ]);
    }
}
