<?php

namespace App\Filament\Resources\Purchases\Schemas;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PurchaseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('company_id')
                    ->default(fn () => function_exists('currentCompanyId') ? currentCompanyId() : null)
                    ->dehydrated(),
                Select::make('supplier_id')
                    ->label('Fornecedor')
                    ->relationship('supplier', 'name')
                    ->searchable()
                    ->required(),
                TextInput::make('invoice_number')
                    ->label('Número da nota')
                    ->required(),
                DatePicker::make('invoice_date')
                    ->label('Data da nota')
                    ->required(),
                TextInput::make('total_value')
                    ->label('Valor total')
                    ->required()
                    ->numeric()
                    ->step('0.01'),
            ]);
    }
}
