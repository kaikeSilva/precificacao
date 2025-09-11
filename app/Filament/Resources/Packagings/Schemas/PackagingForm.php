<?php

namespace App\Filament\Resources\Packagings\Schemas;

use App\Filament\Resources\Units\Schemas\UnitForm;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PackagingForm
{

    public static function getFormFields(): array
    {
        return [
            Hidden::make('company_id')
                ->default(fn () => function_exists('currentCompanyId') ? currentCompanyId() : null)
                ->dehydrated(),
            TextInput::make('name')
                ->label('Nome')
                ->required(),
            UnitForm::getUnitDefaultSelect('unit_id'),
        ];
    }
    
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components(self::getFormFields());
    }
}
