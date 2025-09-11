<?php

namespace App\Filament\Resources\RecipeLaborRoles\Schemas;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class RecipeLaborRoleForm
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
                Select::make('labor_role_id')
                    ->label('Papel de mão de obra')
                    ->relationship('laborRole', 'name')
                    ->searchable()
                    ->required(),
                TextInput::make('working_min')
                    ->label('Tempo trabalhado (min)')
                    ->required()
                    ->numeric()
                    ->step('0.001'),
            ]);
    }
}
