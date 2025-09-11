<?php

namespace App\Filament\Resources\RecipeItems\Pages;

use App\Filament\Resources\RecipeItems\RecipeItemResource;
use Filament\Resources\Pages\CreateRecord;

class CreateRecipeItem extends CreateRecord
{
    protected static string $resource = RecipeItemResource::class;

    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }
}
