<?php

namespace App\Filament\Resources\IngredientCostHistoryItems;

use App\Filament\Resources\IngredientCostHistoryItems\Pages\CreateIngredientCostHistoryItem;
use App\Filament\Resources\IngredientCostHistoryItems\Pages\EditIngredientCostHistoryItem;
use App\Filament\Resources\IngredientCostHistoryItems\Pages\ListIngredientCostHistoryItems;
use App\Filament\Resources\IngredientCostHistoryItems\Schemas\IngredientCostHistoryItemForm;
use App\Filament\Resources\IngredientCostHistoryItems\Tables\IngredientCostHistoryItemsTable;
use App\Models\IngredientCostHistoryItem;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class IngredientCostHistoryItemResource extends Resource
{
    protected static ?string $model = IngredientCostHistoryItem::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    public static bool $shouldRegisterNavigation = false;

    public static function form(Schema $schema): Schema
    {
        return IngredientCostHistoryItemForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return IngredientCostHistoryItemsTable::configure($table);
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
            'index' => ListIngredientCostHistoryItems::route('/'),
            'create' => CreateIngredientCostHistoryItem::route('/create'),
            'edit' => EditIngredientCostHistoryItem::route('/{record}/edit'),
        ];
    }

    public static function getModelLabel(): string
    {
        return 'Histórico de custo de insumo';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Históricos de custo de insumo';
    }
}

