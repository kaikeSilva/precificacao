<?php

namespace App\Filament\Resources\Costs;

use App\Filament\Resources\Costs\Pages\CreateCost;
use App\Filament\Resources\Costs\Pages\EditCost;
use App\Filament\Resources\Costs\Pages\ListCosts;
use App\Filament\Resources\Costs\Schemas\CostForm;
use App\Filament\Resources\Costs\Tables\CostsTable;
use App\Models\Cost;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CostResource extends Resource
{
    protected static ?string $model = Cost::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return CostForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CostsTable::configure($table);
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
            'index' => ListCosts::route('/'),
            'create' => CreateCost::route('/create'),
            'edit' => EditCost::route('/{record}/edit'),
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
        return 'Custo';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Custos';
    }
}

