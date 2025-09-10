<?php

namespace App\Filament\Resources\IngredientFoodCategories\Pages;

use App\Filament\Resources\IngredientFoodCategories\IngredientFoodCategoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListIngredientFoodCategories extends ListRecords
{
    protected static string $resource = IngredientFoodCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
