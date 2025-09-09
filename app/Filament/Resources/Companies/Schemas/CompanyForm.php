<?php

namespace App\Filament\Resources\Companies\Schemas;

use App\Filament\Resources\CompanyUsers\Schemas\CompanyUserForm;
use App\Models\Company;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Livewire;
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
                Fieldset::make('Responsável')
                    ->relationship('owner')
                    ->schema(CompanyUserForm::getFormFields())
                    ->hidden(fn($operation) : bool => $operation !== 'create')
                    ->columnSpan('full')
            ]);
    }
}
