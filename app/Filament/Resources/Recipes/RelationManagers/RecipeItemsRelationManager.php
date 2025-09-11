<?php

namespace App\Filament\Resources\Recipes\RelationManagers;

use App\Filament\Resources\RecipeItems\RecipeItemResource;
use App\Filament\Resources\RecipeItems\Schemas\RecipeItemForm;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Schemas\Schema;

class RecipeItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'recipeItems';

    protected static ?string $relatedResource = RecipeItemResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make()
                ->slideOver(),
            ]);
    }

    public function form(Schema $schema): Schema
    {
        return RecipeItemForm::configure($schema);
    }
}
