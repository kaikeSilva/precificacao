<?php

namespace App\Filament\Resources\Costs\Schemas;

use App\Filament\Resources\CostCategories\Schemas\CostCategoryForm;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use App\Models\Cost;

class CostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('company_id')
                    ->default(fn () => function_exists('currentCompanyId') ? currentCompanyId() : null)
                    ->dehydrated(),
                Select::make('type')
                    ->label('Tipo')
                    ->options(Cost::TYPES_FORMATTED)
                    ->required(),
                TextInput::make('name')
                    ->label('Nome')
                    ->required(),
                Select::make('category_id')
                    ->label('Categoria')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->optionsLimit(20)
                    ->preload()
                    ->createOptionForm(CostCategoryForm::configure(Schema::make())->getComponents()),
                TextInput::make('value')
                    ->label('Valor')
                    ->numeric()
                    ->step('0.01')
                    ->required(),
                DatePicker::make('date')
                    ->label('Data')
                    ->required(),
            ]);
    }
}
