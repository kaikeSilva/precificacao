<?php

namespace App\Filament\Resources\PriceScenarios\Schemas;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class PriceScenarioForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('company_id')
                    ->default(fn () => function_exists('currentCompanyId') ? currentCompanyId() : null)
                    ->dehydrated(),
                TextInput::make('name')
                    ->label('Nome do cenário')
                    ->required(),
                TextInput::make('margin_pct')
                    ->label('Margem (%)')
                    ->required()
                    ->numeric()
                    ->step('0.01')
                    ->default(30.00),
                Textarea::make('overrides_json')
                    ->label('Ajustes (JSON opcional)')
                    ->columnSpanFull(),
            ]);
    }
}
