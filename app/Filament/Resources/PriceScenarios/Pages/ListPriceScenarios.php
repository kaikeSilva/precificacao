<?php

namespace App\Filament\Resources\PriceScenarios\Pages;

use App\Filament\Resources\PriceScenarios\PriceScenarioResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPriceScenarios extends ListRecords
{
    protected static string $resource = PriceScenarioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
