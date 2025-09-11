<?php

namespace App\Filament\Resources\Packagings\RelationManagers;

use App\Filament\Resources\PackagingCostHistoryItems\PackagingCostHistoryItemResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class PackagingCostHistoryItemRelationManager extends RelationManager
{
    protected static string $relationship = 'packagingCostHistoryItem';

    protected static ?string $relatedResource = PackagingCostHistoryItemResource::class;

    public function table(Table $table): Table
    {
        return $table;
    }
}
