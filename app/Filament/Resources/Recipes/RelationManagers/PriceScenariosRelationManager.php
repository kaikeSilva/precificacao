<?php

namespace App\Filament\Resources\Recipes\RelationManagers;

use App\Filament\Resources\PriceScenarios\PriceScenarioResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class PriceScenariosRelationManager extends RelationManager
{
    protected static string $relationship = 'priceScenarios';

    protected static ?string $relatedResource = PriceScenarioResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make()
                    ->url(fn () => PriceScenarioResource::getUrl('create', [
                        'recipe' => $this->getOwnerRecord()->getKey(),
                    ])),
            ]);
    }
}
