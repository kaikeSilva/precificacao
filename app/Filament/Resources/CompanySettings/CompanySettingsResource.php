<?php

namespace App\Filament\Resources\CompanySettings;

use App\Filament\Resources\CompanySettings\Pages\CreateCompanySettings;
use App\Filament\Resources\CompanySettings\Pages\EditCompanySettings;
use App\Filament\Resources\CompanySettings\Pages\ListCompanySettings;
use App\Filament\Resources\CompanySettings\Schemas\CompanySettingsForm;
use App\Filament\Resources\CompanySettings\Tables\CompanySettingsTable;
use App\Models\CompanySettings;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CompanySettingsResource extends Resource
{
    protected static ?string $model = CompanySettings::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Configuracoes da conta';

    public static bool $shouldRegisterNavigation = false;

    public static function form(Schema $schema): Schema
    {
        return CompanySettingsForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CompanySettingsTable::configure($table);
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
            'index' => ListCompanySettings::route('/'),
            'create' => CreateCompanySettings::route('/create'),
            'edit' => EditCompanySettings::route('/{record}/edit'),
        ];
    }
}
