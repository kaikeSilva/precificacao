<?php

namespace App\Filament\Resources\Packagings;

use App\Filament\Resources\Packagings\Pages\CreatePackaging;
use App\Filament\Resources\Packagings\Pages\EditPackaging;
use App\Filament\Resources\Packagings\Pages\ListPackagings;
use App\Filament\Resources\Packagings\Schemas\PackagingForm;
use App\Filament\Resources\Packagings\Tables\PackagingsTable;
use App\Models\Packaging;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PackagingResource extends Resource
{
    protected static ?string $model = Packaging::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return PackagingForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PackagingsTable::configure($table);
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
            'index' => ListPackagings::route('/'),
            'create' => CreatePackaging::route('/create'),
            'edit' => EditPackaging::route('/{record}/edit'),
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
        return 'Embalagem';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Embalagens';
    }
}
