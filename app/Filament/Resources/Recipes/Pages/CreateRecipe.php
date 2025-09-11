<?php

namespace App\Filament\Resources\Recipes\Pages;

use App\Filament\Resources\Recipes\RecipeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateRecipe extends CreateRecord
{
    protected static string $resource = RecipeResource::class;

    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('edit', ['record' => $this->record->getKey()]);
    }
}
