<?php

namespace App\Filament\Resources\PackagingCostHistoryItems\Pages;

use App\Filament\Resources\PackagingCostHistoryItems\PackagingCostHistoryItemResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePackagingCostHistoryItem extends CreateRecord
{
    protected static string $resource = PackagingCostHistoryItemResource::class;

    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }
}
