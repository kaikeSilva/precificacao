<?php

namespace App\Filament\Resources\Companies\Schemas;

use App\Filament\Resources\CompanyUsers\Schemas\CompanyUserForm;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
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
                Repeater::make('companyUsers')
                    ->relationship('companyUsers')
                    ->saveRelationshipsBeforeChildrenUsing(function ($data, $livewire, $component) : void {
                        dump('saveRelationshipsBeforeChildrenUsing', $data);
                    })
                    ->mutateRelationshipDataBeforeCreateUsing(function ($data, $livewire, $component) : void {
                        dump('mutateRelationshipDataBeforeCreateUsing', $data);
                        $data['user'] = $livewire->data['user'];
                    })
                    ->defaultItems(1)
                    ->addable(false)
                    ->deletable(false)
                    ->minItems(1)
                    ->maxItems(1)
                    ->schema(CompanyUserForm::configure($schema)->getComponents())
                    ->hidden(fn($operation) : bool => $operation !== 'create')
                    ->columnSpan('full')
            ]);
    }
}
