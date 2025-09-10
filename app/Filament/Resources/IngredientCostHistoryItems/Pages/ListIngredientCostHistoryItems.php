<?php

namespace App\Filament\Resources\IngredientCostHistoryItems\Pages;

use App\Filament\Resources\IngredientCostHistoryItems\IngredientCostHistoryItemResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListIngredientCostHistoryItems extends ListRecords
{
    protected static string $resource = IngredientCostHistoryItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
