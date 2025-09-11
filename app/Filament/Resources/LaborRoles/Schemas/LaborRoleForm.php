<?php

namespace App\Filament\Resources\LaborRoles\Schemas;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class LaborRoleForm
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
                TextInput::make('monthly_salary')
                    ->label('Salário mensal')
                    ->required()
                    ->numeric()
                    ->step('0.01'),
                TextInput::make('hours_per_month')
                    ->label('Horas por mês')
                    ->required()
                    ->numeric()
                    ->default(220),
                TextInput::make('hour_cost_cached')
                    ->label('Custo por hora')
                    ->numeric()
                    ->step('0.0001')
                    ->disabled()
                    ->dehydrated(false)
                    ->helperText('Calculado automaticamente: salário mensal ÷ horas por mês'),
            ]);
    }
}
