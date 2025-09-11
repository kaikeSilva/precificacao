<?php

namespace App\Filament\Resources\PriceScenarios;

use App\Filament\Resources\PriceScenarios\Pages\CreatePriceScenario;
use App\Filament\Resources\PriceScenarios\Pages\EditPriceScenario;
use App\Filament\Resources\PriceScenarios\Pages\ListPriceScenarios;
use App\Filament\Resources\PriceScenarios\Schemas\PriceScenarioForm;
use App\Filament\Resources\PriceScenarios\Tables\PriceScenariosTable;
use App\Models\PriceScenario;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PriceScenarioResource extends Resource
{
    protected static ?string $model = PriceScenario::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    public static bool $shouldRegisterNavigation = false;

    public static function form(Schema $schema): Schema
    {
        return PriceScenarioForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PriceScenariosTable::configure($table);
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
            'index' => ListPriceScenarios::route('/'),
            'create' => CreatePriceScenario::route('/create'),
            'edit' => EditPriceScenario::route('/{record}/edit'),
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
        return 'Cenário de Preço';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Cenários de Preço';
    }
    
}
