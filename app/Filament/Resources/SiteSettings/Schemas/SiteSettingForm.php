<?php

namespace App\Filament\Resources\SiteSettings\Schemas;

use App\Rules\ValidSiteSettingValue;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class SiteSettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Setting details')
                    ->description('Use a clear key and group so staff can find this setting later.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('key')
                            ->required()
                            ->maxLength(120)
                            ->unique(ignoreRecord: true),
                        TextInput::make('group')
                            ->maxLength(80),
                    ]),
                Section::make('Setting value')
                    ->description('Enter the value in the format required by the selected setting key.')
                    ->schema([
                        Textarea::make('value')
                            ->rows(4)
                            ->rules(static function (Get $get): array {
                                $key = $get('key');

                                return [
                                    new ValidSiteSettingValue(is_string($key) ? $key : null),
                                ];
                            })
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
