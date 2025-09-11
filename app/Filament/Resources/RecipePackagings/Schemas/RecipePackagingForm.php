<?php

namespace App\Filament\Resources\RecipePackagings\Schemas;

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
                Select::make('recipe_id')
                    ->label('Receita')
                    ->relationship('recipe', 'name')
                    ->searchable()
                    ->required(),
                Select::make('packaging_id')
                    ->label('Embalagem')
                    ->relationship('packaging', 'name')
                    ->searchable()
                    ->required(),
                Select::make('unit_id')
                    ->label('Unidade')
                    ->relationship('unit', 'name')
                    ->searchable()
                    ->required(),
                TextInput::make('qty')
                    ->label('Quantidade')
                    ->required()
                    ->numeric()
                    ->step('0.001'),
            ]);
    }
}
