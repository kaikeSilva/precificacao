<?php

namespace App\Filament\Resources\Packagings\Schemas;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PackagingForm
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
                Select::make('unit_id')
                    ->label('Unidade')
                    ->relationship('unit', 'name')
                    ->required(),
                TextInput::make('pack_qty')
                    ->label('Qtd por pacote')
                    ->required()
                    ->numeric()
                    ->default(100),
                TextInput::make('pack_price')
                    ->label('Preço do pacote')
                    ->required()
                    ->numeric()
                    ->step('0.01'),
                TextInput::make('unit_cost_cached')
                    ->label('Custo por unidade')
                    ->numeric()
                    ->step('0.0001')
                    ->disabled()
                    ->dehydrated(false)
                    ->helperText('Calculado automaticamente: preço do pacote ÷ qtd por pacote'),
                Select::make('supplier_id')
                    ->label('Fornecedor')
                    ->relationship('supplier', 'name')
                    ->searchable()
                    ->nullable(),
            ]);
    }
}
