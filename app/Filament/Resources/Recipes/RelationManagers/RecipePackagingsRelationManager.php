<?php

namespace App\Filament\Resources\Recipes\RelationManagers;

use App\Filament\Resources\RecipePackagings\RecipePackagingResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class RecipePackagingsRelationManager extends RelationManager
{
    protected static string $relationship = 'recipePackagings';

    protected static ?string $relatedResource = RecipePackagingResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make()
                ->slideOver(),
            ]);
    }
}
