<?php

namespace App\Filament\Resources\PackagingCostHistoryItems\Schemas;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class PackagingCostHistoryItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('company_id')
                    ->default(fn () => function_exists('currentCompanyId') ? currentCompanyId() : null)
                    ->dehydrated(),
                Select::make('packaging_id')
                    ->label('Embalagem')
                    ->relationship('packaging', 'name')
                    ->searchable()
                    ->required(),
                Select::make('supplier_id')
                    ->label('Fornecedor')
                    ->relationship('supplier', 'name')
                    ->searchable()
                    ->required(),
                DatePicker::make('date')
                    ->label('Data')
                    ->required(),
                TextInput::make('pack_price')
                    ->label('Preço do pacote')
                    ->required()
                    ->numeric()
                    ->step('0.01'),
                TextInput::make('source')
                    ->label('Origem (NF, loja, link)')
                    ->nullable(),
                Textarea::make('notes')
                    ->label('Observações')
                    ->nullable()
                    ->columnSpanFull(),
            ]);
    }
}
