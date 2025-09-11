<?php

namespace App\Filament\Resources\Recipes;

use App\Filament\Resources\Recipes\Pages\CreateRecipe;
use App\Filament\Resources\Recipes\Pages\EditRecipe;
use App\Filament\Resources\Recipes\Pages\ListRecipes;
use App\Filament\Resources\Recipes\RelationManagers\PriceScenariosRelationManager;
use App\Filament\Resources\Recipes\Schemas\RecipeForm;
use App\Filament\Resources\Recipes\Tables\RecipesTable;
use App\Filament\Resources\Recipes\RelationManagers\RecipeItemsRelationManager;
use App\Filament\Resources\Recipes\RelationManagers\RecipeLaborRolesRelationManager;
use App\Filament\Resources\Recipes\RelationManagers\RecipePackagingsRelationManager;
use App\Models\Recipe;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RecipeResource extends Resource
{
    protected static ?string $model = Recipe::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return RecipeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RecipesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            RecipeItemsRelationManager::class,
            RecipePackagingsRelationManager::class,
            RecipeLaborRolesRelationManager::class,
            PriceScenariosRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRecipes::route('/'),
            'create' => CreateRecipe::route('/create'),
            'edit' => EditRecipe::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getModelLabel(): string
    {
        return 'Receita';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Receitas';
    }
}
