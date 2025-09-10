<?php

namespace App\Filament\Resources\IngredientFoodCategories\Pages;

use App\Filament\Resources\IngredientFoodCategories\IngredientFoodCategoryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateIngredientFoodCategory extends CreateRecord
{
    protected static string $resource = IngredientFoodCategoryResource::class;

    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }
}
