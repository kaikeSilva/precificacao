<?php

namespace App\Filament\Resources\RecipePackagings\Pages;

use App\Filament\Resources\RecipePackagings\RecipePackagingResource;
use Filament\Resources\Pages\CreateRecord;

class CreateRecipePackaging extends CreateRecord
{
    protected static string $resource = RecipePackagingResource::class;

    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }
}
