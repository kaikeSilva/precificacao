<?php

namespace App\Filament\Resources\RecipePackagings\Pages;

use App\Filament\Resources\RecipePackagings\RecipePackagingResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRecipePackagings extends ListRecords
{
    protected static string $resource = RecipePackagingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
