<?php

namespace App\Filament\Resources\RecipeItems\Schemas;

use App\Filament\Resources\Ingredients\Schemas\IngredientForm;
use App\Filament\Resources\Units\Schemas\UnitForm;
use App\Models\Ingredient;
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
                Select::make('ingredient_id')
                    ->label('Insumo')
                    ->relationship('ingredient', 'name')
                    ->createOptionUsing(function (array $data): int {
                        return Ingredient::create($data)->getKey();
                    })
                    ->options(Ingredient::all()->pluck('name', 'id'))
                    ->createOptionForm(IngredientForm::getFormFields())
                    ->searchable()
                    ->preload()
                    ->required(),
                UnitForm::getUnitDefaultSelect('unit_id'),
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
