<?php

namespace App\Filament\Resources\PackagingCostHistoryItems\Pages;

use App\Filament\Resources\PackagingCostHistoryItems\PackagingCostHistoryItemResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPackagingCostHistoryItems extends ListRecords
{
    protected static string $resource = PackagingCostHistoryItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
