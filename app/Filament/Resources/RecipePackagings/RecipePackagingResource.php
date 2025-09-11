<?php

namespace App\Filament\Resources\RecipePackagings;

use App\Filament\Resources\RecipePackagings\Pages\CreateRecipePackaging;
use App\Filament\Resources\RecipePackagings\Pages\EditRecipePackaging;
use App\Filament\Resources\RecipePackagings\Pages\ListRecipePackagings;
use App\Filament\Resources\RecipePackagings\Schemas\RecipePackagingForm;
use App\Filament\Resources\RecipePackagings\Tables\RecipePackagingsTable;
use App\Models\RecipePackaging;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RecipePackagingResource extends Resource
{
    protected static ?string $model = RecipePackaging::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    public static bool $shouldRegisterNavigation = false;

    public static function form(Schema $schema): Schema
    {
        return RecipePackagingForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RecipePackagingsTable::configure($table);
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
            'index' => ListRecipePackagings::route('/'),
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
        return 'Embalagem da receita';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Embalagens da receita';
    }
}
