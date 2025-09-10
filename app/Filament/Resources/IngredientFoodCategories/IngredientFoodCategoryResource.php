<?php

namespace App\Filament\Resources\IngredientFoodCategories;

use App\Filament\Resources\IngredientFoodCategories\Pages\CreateIngredientFoodCategory;
use App\Filament\Resources\IngredientFoodCategories\Pages\EditIngredientFoodCategory;
use App\Filament\Resources\IngredientFoodCategories\Pages\ListIngredientFoodCategories;
use App\Filament\Resources\IngredientFoodCategories\Schemas\IngredientFoodCategoryForm;
use App\Filament\Resources\IngredientFoodCategories\Tables\IngredientFoodCategoriesTable;
use App\Models\IngredientFoodCategory;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class IngredientFoodCategoryResource extends Resource
{
    protected static ?string $model = IngredientFoodCategory::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return IngredientFoodCategoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return IngredientFoodCategoriesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListIngredientFoodCategories::route('/'),
            'create' => CreateIngredientFoodCategory::route('/create'),
            'edit' => EditIngredientFoodCategory::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
