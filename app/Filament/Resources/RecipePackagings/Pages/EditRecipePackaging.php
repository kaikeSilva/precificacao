<?php

namespace App\Filament\Resources\RecipePackagings\Pages;

use App\Filament\Resources\RecipePackagings\RecipePackagingResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditRecipePackaging extends EditRecord
{
    protected static string $resource = RecipePackagingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
