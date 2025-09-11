<?php

namespace App\Filament\Resources\CostCategories;

use App\Filament\Resources\CostCategories\Pages\CreateCostCategory;
use App\Filament\Resources\CostCategories\Pages\EditCostCategory;
use App\Filament\Resources\CostCategories\Pages\ListCostCategories;
use App\Filament\Resources\CostCategories\Schemas\CostCategoryForm;
use App\Filament\Resources\CostCategories\Tables\CostCategoriesTable;
use App\Models\CostCategory;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CostCategoryResource extends Resource
{
    protected static ?string $model = CostCategory::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    public static bool $shouldRegisterNavigation = false;

    public static function form(Schema $schema): Schema
    {
        return CostCategoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CostCategoriesTable::configure($table);
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
            'index' => ListCostCategories::route('/'),
            'create' => CreateCostCategory::route('/create'),
            'edit' => EditCostCategory::route('/{record}/edit'),
        ];
    }

    public static function getModelLabel(): string
    {
        return 'Categoria de custo';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Categorias de custo';
    }
}
