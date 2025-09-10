<?php

namespace App\Filament\Resources\Companies\Schemas;

use App\Filament\Resources\CompanyUsers\Schemas\CompanyUserForm;
use App\Models\CompanyUser;
use App\Services\CompanyUserService;
use Filament\Forms\Components\Repeater;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Fieldset;
use Illuminate\Database\Eloquent\Model;
use Filament\Schemas\Schema;

class CompanyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nome da empresa')
                    ->required(),
                TextInput::make('document')
                    ->label('CPF/CNPJ')
                    ->required(),
                Repeater::make('Responsáveis')
                    ->defaultItems(1)
                    ->minItems(1)
                    ->statePath('owners')
                    ->schema(CompanyUserForm::getFormFields())
                    ->hidden(fn($operation) : bool => $operation !== 'create')
                    ->columnSpan('full')
            ]);
    }
}
