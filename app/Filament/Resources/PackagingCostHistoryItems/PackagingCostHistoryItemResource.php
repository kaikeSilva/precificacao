<?php

namespace App\Filament\Resources\PackagingCostHistoryItems;

use App\Filament\Resources\PackagingCostHistoryItems\Pages\CreatePackagingCostHistoryItem;
use App\Filament\Resources\PackagingCostHistoryItems\Pages\EditPackagingCostHistoryItem;
use App\Filament\Resources\PackagingCostHistoryItems\Pages\ListPackagingCostHistoryItems;
use App\Filament\Resources\PackagingCostHistoryItems\Schemas\PackagingCostHistoryItemForm;
use App\Filament\Resources\PackagingCostHistoryItems\Tables\PackagingCostHistoryItemsTable;
use App\Models\PackagingCostHistoryItem;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PackagingCostHistoryItemResource extends Resource
{
    protected static ?string $model = PackagingCostHistoryItem::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    public static bool $shouldRegisterNavigation = false;

    public static function form(Schema $schema): Schema
    {
        return PackagingCostHistoryItemForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PackagingCostHistoryItemsTable::configure($table);
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
            'index' => ListPackagingCostHistoryItems::route('/'),
            'create' => CreatePackagingCostHistoryItem::route('/create'),
            'edit' => EditPackagingCostHistoryItem::route('/{record}/edit'),
        ];
    }

    public static function getModelLabel(): string
    {
        return 'Histórico de custo de embalagem';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Históricos de custo de embalagem';
    }
}
