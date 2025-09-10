<?php

namespace App\Filament\Resources\IngredientCostHistoryItems\Pages;

use App\Filament\Resources\IngredientCostHistoryItems\IngredientCostHistoryItemResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditIngredientCostHistoryItem extends EditRecord
{
    protected static string $resource = IngredientCostHistoryItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }
}
