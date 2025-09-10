<?php

namespace App\Filament\Resources\Units\Schemas;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UnitForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('company_id')
                    ->default(fn () => function_exists('currentCompanyId') ? currentCompanyId() : null)
                    ->dehydrated(),
                TextInput::make('name')
                    ->label('Nome')
                    ->required(),
                TextInput::make('abbreviation')
                    ->label('Abreviação')
                    ->maxLength(10),
            ]);
    }
}
