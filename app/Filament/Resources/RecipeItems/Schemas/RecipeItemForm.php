<?php

namespace App\Filament\Resources\RecipeItems\Schemas;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class RecipeItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('company_id')
                    ->default(fn () => function_exists('currentCompanyId') ? currentCompanyId() : null)
                    ->dehydrated(),
                Select::make('recipe_id')
                    ->label('Receita')
                    ->relationship('recipe', 'name')
                    ->searchable()
                    ->required(),
                Select::make('ingredient_id')
                    ->label('Insumo')
                    ->relationship('ingredient', 'name')
                    ->searchable()
                    ->required(),
                Select::make('unit_id')
                    ->label('Unidade')
                    ->relationship('unit', 'name')
                    ->searchable()
                    ->required(),
                TextInput::make('qty')
                    ->label('Quantidade')
                    ->required()
                    ->numeric()
                    ->step('0.001'),
                TextInput::make('loss_pct')
                    ->label('Perda (%)')
                    ->required()
                    ->numeric()
                    ->step('0.01')
                    ->default(0),
            ]);
    }
}
