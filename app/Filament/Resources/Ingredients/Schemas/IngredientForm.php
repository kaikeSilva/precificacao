<?php

namespace App\Filament\Resources\Ingredients\Schemas;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class IngredientForm
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
                    ->searchable(),
                TextInput::make('name')
                    ->label('Nome')
                    ->required(),
                Select::make('unit')
                    ->label('Unidade')
                    ->options([
                        'g' => 'g',
                        'kg' => 'kg',
                        'ml' => 'ml',
                        'l' => 'l',
                        'un' => 'un',
                    ])
                    ->required(),
                TextInput::make('pack_qty')
                    ->label('Conteúdo da embalagem')
                    ->numeric()
                    ->step('0.001')
                    ->required(),
                Select::make('pack_unit')
                    ->label('Unidade da embalagem')
                    ->options([
                        'g' => 'g',
                        'kg' => 'kg',
                        'ml' => 'ml',
                        'l' => 'l',
                        'un' => 'un',
                    ])
                    ->required(),
                TextInput::make('pack_price')
                    ->label('Preço da embalagem')
                    ->numeric()
                    ->step('0.01')
                    ->required(),
                TextInput::make('loss_pct_default')
                    ->label('Perda padrão (%)')
                    ->numeric()
                    ->step('0.01')
                    ->default(0)
                    ->required(),
                Textarea::make('notes')
                    ->label('Observações')
                    ->columnSpanFull(),
            ]);
    }
}

