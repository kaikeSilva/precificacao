<?php

namespace App\Filament\Resources\PriceScenarios\Pages;

use App\Filament\Resources\PriceScenarios\PriceScenarioResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditPriceScenario extends EditRecord
{
    protected static string $resource = PriceScenarioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
