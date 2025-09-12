<?php

namespace App\Filament\Resources\PriceScenarios\Pages;

use App\Filament\Resources\PriceScenarios\PriceScenarioResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Schemas\Components\UnorderedList;
use Filament\Schemas\Components\Text;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Utilities\Get;
use App\Models\RecipeItem;
use App\Models\RecipePackaging;
use App\Models\RecipeLaborRole;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class CreatePriceScenario extends CreateRecord
{
    protected static string $resource = PriceScenarioResource::class;


    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }

   /** >>> Leia de $data, não de $this->bases */
   protected function mutateFormDataBeforeCreate(array $data): array
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

    //    dd($data); // para inspecionar
       return $data;
   }

}
