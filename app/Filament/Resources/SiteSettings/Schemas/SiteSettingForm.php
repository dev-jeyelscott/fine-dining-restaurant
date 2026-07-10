<?php

namespace App\Filament\Resources\SiteSettings\Schemas;

use App\Rules\ValidSiteSettingValue;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class SiteSettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('key')
                    ->required()
                    ->maxLength(120)
                    ->unique(ignoreRecord: true),

                Textarea::make('value')
                    ->rows(4)
                    ->rules(static function (Get $get): array {
                        $key = $get('key');

                        return [
                            new ValidSiteSettingValue(is_string($key) ? $key : null),
                        ];
                    })
                    ->columnSpanFull(),

                TextInput::make('group')
                    ->maxLength(80),
            ]);
    }
}
