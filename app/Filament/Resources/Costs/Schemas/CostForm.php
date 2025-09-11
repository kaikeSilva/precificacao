<?php

namespace App\Filament\Resources\Costs\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('company_id')
                    ->default(fn () => function_exists('currentCompanyId') ? currentCompanyId() : null)
                    ->dehydrated(),
                Select::make('type')
                    ->label('Tipo')
                    ->options([
                        'fixed' => 'Fixo',
                        'variable' => 'Variável',
                    ])
                    ->required(),
                TextInput::make('name')
                    ->label('Nome')
                    ->required(),
                TextInput::make('category')
                    ->label('Categoria')
                    ->nullable(),
                TextInput::make('value')
                    ->label('Valor')
                    ->numeric()
                    ->step('0.01')
                    ->required(),
                DatePicker::make('date')
                    ->label('Data')
                    ->required(),
            ]);
    }
}
