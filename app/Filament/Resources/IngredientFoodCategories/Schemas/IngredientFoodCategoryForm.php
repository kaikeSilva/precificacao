<?php

namespace App\Filament\Resources\IngredientFoodCategories\Schemas;

use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class IngredientFoodCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('ingredient_id')
                    ->label('Ingrediente')
                    ->relationship('ingredient', 'name')
                    ->searchable()
                    ->required(),
                Select::make('food_category_id')
                    ->label('Categoria de alimento')
                    ->relationship('foodCategory', 'name')
                    ->searchable()
                    ->required(),
            ]);
    }
}
