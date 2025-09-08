<?php

namespace App\Filament\Resources\CompanyUsers\Schemas;

use App\Models\CompanyUser;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CompanyUserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('role')
                    ->label('Função')
                    ->required()
                    ->default('owner')
                    ->options(CompanyUser::ROLE_LABELS),
                TextInput::make('name')
                    ->label('Nome Completo')
                    ->required(),
                TextInput::make('email')
                    ->label('E-mail')
                    ->email()
                    ->unique('users', 'email')
                    ->required(),
                Toggle::make('changePassword')
                    ->label('Alterar Senha')
                    ->hiddenOn('create')
                    ->live(),
                TextInput::make('password')
                    ->confirmed()
                    ->label('Senha')
                    ->minLength(8)
                    ->hidden(fn($operation, $get) : bool => $operation === 'edit' ? !$get('changePassword') : false)
                    ->required(fn($operation, $get) : bool => $operation === 'create' || $get('changePassword')),
                TextInput::make('password_confirmation')
                    ->label('Confirmar Senha')  
                    ->minLength(8)
                    ->hidden(fn($operation, $get) : bool => $operation === 'edit' ? !$get('changePassword') : false)
                    ->required(fn($operation, $get) : bool => $operation === 'create' || $get('changePassword')),
            ]);
    }
}
