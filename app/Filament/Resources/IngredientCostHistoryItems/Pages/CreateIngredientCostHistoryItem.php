<?php

namespace App\Filament\Resources\IngredientCostHistoryItems\Pages;

use App\Filament\Resources\IngredientCostHistoryItems\IngredientCostHistoryItemResource;
use Filament\Resources\Pages\CreateRecord;

class CreateIngredientCostHistoryItem extends CreateRecord
{
    protected static string $resource = IngredientCostHistoryItemResource::class;

    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }
}
