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

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['overrides_json'] = [
            'bases' => $data['bases'] ?? [
                'ingredients' => [],
                'packagings'  => [],
                'labor'       => [],
            ],
        ];

        if (empty($data['recipe_id'])) {
            $data['recipe_id'] = request()->has('recipe') ? (int) request()->query('recipe') : null;
        }

        return $data;
    }
}
