<?php

namespace App\Filament\Resources\PriceScenarios\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Text;
use Filament\Schemas\Schema;

final class PriceScenarioInfoList
{
        
    public static function configure(Schema $schema): Schema
    {
        dd($schema->getRecord());
        return $schema->components([
          TextEntry::make('name')->label('Nome do cenário'),
          TextEntry::make('calculation')->label('Cálculo')->state($calculation),
        ]);
    }
}
