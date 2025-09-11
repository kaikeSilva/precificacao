<?php

namespace App\Filament\Resources\PurchaseItems\Schemas;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use App\Models\Ingredient;
use App\Models\Packaging;

class PurchaseItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('company_id')
                    ->default(fn () => function_exists('currentCompanyId') ? currentCompanyId() : null)
                    ->dehydrated(),
                Select::make('purchase_id')
                    ->label('Compra')
                    ->relationship('purchase', 'invoice_number')
                    ->searchable()
                    ->required(),
                Select::make('item_type')
                    ->label('Tipo de item')
                    ->options([
                        Ingredient::class => 'Ingrediente',
                        Packaging::class => 'Embalagem',
                    ])
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(fn (callable $set) => $set('item_id', null)),
                Select::make('item_id')
                    ->label('Item')
                    ->options(function (callable $get) {
                        $type = $get('item_type');
                        if ($type === Ingredient::class) {
                            return Ingredient::query()
                                ->where('company_id', currentCompanyId())
                                ->orderBy('name')
                                ->pluck('name', 'id');
                        }
                        if ($type === Packaging::class) {
                            return Packaging::query()
                                ->where('company_id', currentCompanyId())
                                ->orderBy('name')
                                ->pluck('name', 'id');
                        }
                        return [];
                    })
                    ->searchable()
                    ->required()
                    ->disabled(fn (callable $get) => blank($get('item_type'))),
                TextInput::make('qty')
                    ->label('Quantidade')
                    ->required()
                    ->numeric()
                    ->step('0.01'),
                TextInput::make('unit_price')
                    ->label('Preço unitário')
                    ->required()
                    ->numeric()
                    ->step('0.01'),
                TextInput::make('subtotal')
                    ->label('Subtotal')
                    ->required()
                    ->numeric()
                    ->step('0.01'),
            ]);
    }
}
