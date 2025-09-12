<?php

namespace App\Filament\Resources\PriceScenarios\Pages;

use App\Filament\Resources\PriceScenarios\PriceScenarioResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use App\Models\PriceScenario;
use App\Services\PriceScenarioService;

// se o computeAll estiver numa service class, importe-a:
// use App\Support\PriceScenarioCalculator;

class ViewPriceScenario extends ViewRecord
{
    protected static string $resource = PriceScenarioResource::class;

    /** Use o Blade custom, não precisa ser static aqui */
    protected string $view = 'filament.resources.price-scenarios.pages.view-price-scenario';

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
    /** Passa dados extras para o Blade */
    protected function getViewData(): array
    {
        /** @var PriceScenario $scenario */
        $scenario = $this->record;

        // Se computeAll for MÉTODO desta classe/trait:
        $calc = $this->computeAll($scenario);

        // Se computeAll for de um service dedicado, use:
        // $calc = app(PriceScenarioCalculator::class)->computeAll($scenario);

        return [
            'scenario' => $scenario,
            'calc'     => $calc,
        ];
    }

    /**
     * Se seu computeAll estiver em outro lugar, REMOVA este método daqui.
     * Mantive aqui apenas para ilustrar: você disse que já tem esse método.
     */
    public function computeAll(PriceScenario $scenario): array
    {
        return app(PriceScenarioService::class)->computeAll($scenario); 
    }
}
