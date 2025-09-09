<?php

namespace App\Filament\Resources\CompanyUsers;

use App\Filament\Resources\CompanyUsers\Pages\CreateCompanyUser;
use App\Filament\Resources\CompanyUsers\Pages\EditCompanyUser;
use App\Filament\Resources\CompanyUsers\Pages\ListCompanyUsers;
use App\Filament\Resources\CompanyUsers\Schemas\CompanyUserForm;
use App\Filament\Resources\CompanyUsers\Tables\CompanyUsersTable;
use App\Models\CompanyUser;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CompanyUserResource extends Resource
{
    protected static ?string $model = CompanyUser::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static ?string $pluralModelLabel = CompanyUser::PLURAL_MODEL_LABEL;
    protected static ?string $modelLabel = CompanyUser::SINGULAR_MODEL_LABEL;
    public static bool $shouldRegisterNavigation = false;
    
    public static function form(Schema $schema): Schema
    {
        return CompanyUserForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CompanyUsersTable::configure($table);
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
            'index' => ListCompanyUsers::route('/'),
        ];
    }
}
