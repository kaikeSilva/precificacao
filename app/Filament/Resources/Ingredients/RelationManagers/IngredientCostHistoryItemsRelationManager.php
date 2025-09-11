<?php

namespace App\Filament\Resources\Ingredients\RelationManagers;

use App\Filament\Resources\IngredientCostHistoryItems\IngredientCostHistoryItemResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class IngredientCostHistoryItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'ingredientCostHistoryItems';

    protected static ?string $relatedResource = IngredientCostHistoryItemResource::class;

    public function table(Table $table): Table
    {
        return $table;
    }
}
