<?php

namespace App\Filament\Resources\RecipePackagings\Schemas;

use App\Filament\Resources\Packagings\Schemas\PackagingForm;
use App\Filament\Resources\Units\Schemas\UnitForm;
use App\Models\Packaging;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class RecipePackagingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('company_id')
                    ->default(fn () => function_exists('currentCompanyId') ? currentCompanyId() : null)
                    ->dehydrated(),
                Select::make('packaging_id')
                    ->label('Embalagem')
                    ->relationship('packaging', 'name')
                    ->createOptionUsing(function (array $data): int {
                        return Packaging::create($data)->getKey();
                    })
                    ->options(Packaging::all()->pluck('name', 'id'))
                    ->createOptionForm(PackagingForm::getFormFields())
                    ->searchable()
                    ->preload()
                    ->required(),
                UnitForm::getUnitDefaultSelect('unit_id'),
                TextInput::make('qty')
                    ->label('Quantidade')
                    ->required()
                    ->numeric()
                    ->step('0.001'),
            ]);
    }
}
