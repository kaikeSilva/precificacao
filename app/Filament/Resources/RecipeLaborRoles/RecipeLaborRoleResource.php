<?php

namespace App\Filament\Resources\RecipeLaborRoles;

use App\Filament\Resources\RecipeLaborRoles\Pages\CreateRecipeLaborRole;
use App\Filament\Resources\RecipeLaborRoles\Pages\EditRecipeLaborRole;
use App\Filament\Resources\RecipeLaborRoles\Pages\ListRecipeLaborRoles;
use App\Filament\Resources\RecipeLaborRoles\Schemas\RecipeLaborRoleForm;
use App\Filament\Resources\RecipeLaborRoles\Tables\RecipeLaborRolesTable;
use App\Models\RecipeLaborRole;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RecipeLaborRoleResource extends Resource
{
    protected static ?string $model = RecipeLaborRole::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    public static bool $shouldRegisterNavigation = false;

    public static function form(Schema $schema): Schema
    {
        return RecipeLaborRoleForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RecipeLaborRolesTable::configure($table);
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
            'index' => ListRecipeLaborRoles::route('/'),
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
        return 'Função de Trabalho';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Funções de Trabalho';
    }
}
