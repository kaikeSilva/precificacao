<?php

namespace App\Filament\Resources\LaborRoles;

use App\Filament\Resources\LaborRoles\Pages\CreateLaborRole;
use App\Filament\Resources\LaborRoles\Pages\EditLaborRole;
use App\Filament\Resources\LaborRoles\Pages\ListLaborRoles;
use App\Filament\Resources\LaborRoles\Schemas\LaborRoleForm;
use App\Filament\Resources\LaborRoles\Tables\LaborRolesTable;
use App\Models\LaborRole;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LaborRoleResource extends Resource
{
    protected static ?string $model = LaborRole::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return LaborRoleForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LaborRolesTable::configure($table);
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
            'index' => ListLaborRoles::route('/'),
            'create' => CreateLaborRole::route('/create'),
            'edit' => EditLaborRole::route('/{record}/edit'),
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
        return 'Perfil de mão de obra';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Perfis de mão de obra';
    }
}
