<?php

namespace App\Filament\Resources\IngredientCostHistoryItems\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class IngredientCostHistoryItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('company_id')
                    ->default(fn () => function_exists('currentCompanyId') ? currentCompanyId() : null)
                    ->dehydrated(),
                Select::make('ingredient_id')
                    ->label('Ingrediente')
                    ->relationship('ingredient', 'name')
                    ->required(),
                Select::make('supplier_id')
                    ->label('Fornecedor')
                    ->relationship('supplier', 'name')
                    ->searchable(),
                DatePicker::make('date')
                    ->label('Data')
                    ->required(),
                TextInput::make('pack_price')
                    ->label('Preço da embalagem')
                    ->required()
                    ->numeric(),
                TextInput::make('source')
                    ->label('Fonte'),
                Textarea::make('notes')
                    ->label('Observações')
                    ->columnSpanFull(),
            ]);
    }
}

