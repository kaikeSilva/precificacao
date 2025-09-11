<?php

namespace App\Filament\Resources\RecipeLaborRoles\Schemas;

use App\Filament\Resources\LaborRoles\Schemas\LaborRoleForm;
use App\Models\LaborRole;
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
                Select::make('labor_role_id')
                    ->label('Papel de mão de obra')
                    ->relationship('laborRole', 'name')
                    ->createOptionUsing(function (array $data): int {
                        return LaborRole::create($data)->getKey();
                    })
                    ->options(LaborRole::all()->pluck('name', 'id'))
                    ->createOptionForm(LaborRoleForm::getFormFields())
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('working_min')
                    ->label('Tempo trabalhado na receita (min)')
                    ->required()
                    ->numeric()
                    ->step('0.001'),
            ]);
    }
}
