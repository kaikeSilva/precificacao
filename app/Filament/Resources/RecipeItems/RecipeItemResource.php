<?php

namespace App\Filament\Resources\RecipeItems;

use App\Filament\Resources\RecipeItems\Pages\CreateRecipeItem;
use App\Filament\Resources\RecipeItems\Pages\EditRecipeItem;
use App\Filament\Resources\RecipeItems\Pages\ListRecipeItems;
use App\Filament\Resources\RecipeItems\Schemas\RecipeItemForm;
use App\Filament\Resources\RecipeItems\Tables\RecipeItemsTable;
use App\Models\RecipeItem;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RecipeItemResource extends Resource
{
    protected static ?string $model = RecipeItem::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return RecipeItemForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RecipeItemsTable::configure($table);
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
            'index' => ListRecipeItems::route('/'),
            'create' => CreateRecipeItem::route('/create'),
            'edit' => EditRecipeItem::route('/{record}/edit'),
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
