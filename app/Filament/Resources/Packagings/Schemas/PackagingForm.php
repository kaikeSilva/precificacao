<?php

namespace App\Filament\Resources\Packagings\Schemas;

use App\Filament\Resources\Units\Schemas\UnitForm;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PackagingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('company_id')
                    ->default(fn () => function_exists('currentCompanyId') ? currentCompanyId() : null)
                    ->dehydrated(),
                TextInput::make('name')
                    ->label('Nome')
                    ->required(),
                Select::make('unit_id')
                    ->label('Unidade')
                    ->relationship('unit', 'name')
                    ->searchable()
                    ->optionsLimit(20)
                    ->preload()
                    ->createOptionForm(UnitForm::configure($schema)->getComponents())
                    ->required(),
            ]);
    }
}
