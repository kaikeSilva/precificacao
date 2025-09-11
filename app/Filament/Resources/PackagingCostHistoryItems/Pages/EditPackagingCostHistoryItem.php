<?php

namespace App\Filament\Resources\PackagingCostHistoryItems\Pages;

use App\Filament\Resources\PackagingCostHistoryItems\PackagingCostHistoryItemResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPackagingCostHistoryItem extends EditRecord
{
    protected static string $resource = PackagingCostHistoryItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }
}
