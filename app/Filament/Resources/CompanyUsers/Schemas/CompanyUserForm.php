<?php

namespace App\Filament\Resources\CompanyUsers\Schemas;

use App\Models\CompanyUser;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Schema;

/**
 * Formulário de usuário da empresa
 * 
 * gerencia a criacao e edicao de usuarios da empresa em diferentes contextos e se adapta 
 * para as necessidades de cada um.
 */
class CompanyUserForm
{
    private static $companyUserContexts = [
        'company-users-relation-manager',
    ];

    private static $isEditing = false;

    private static function isOnCompanyUserContext($livewire): bool
    {
        $livewireName = explode('.', $livewire->getName());
        $livewireName = end($livewireName);
        if (in_array($livewireName, self::$companyUserContexts)) {
            return true;
        }
        return false;
    }

    private static function getFieldSet(): Fieldset
    {
        return self::$isEditing ? 
        Fieldset::make()
            ->relationship("user") : 
        Fieldset::make();
    }

    public static function getFormFields(): array
    {
        return [
            Select::make('role')
                ->label('Função')
                ->required()
                ->default('owner')
                ->hidden(fn ($livewire) : bool => !self::isOnCompanyUserContext($livewire))
                ->options(CompanyUser::ROLE_LABELS),
            self::getFieldSet()
                ->columnSpan('full')
                ->schema([
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
                        ->hidden(function ($livewire, $operation) : bool {
                        if (!self::isOnCompanyUserContext($livewire)) {
                            return true;
                        }
                        return $operation !== 'edit';
                        })
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
                ]),
        ];
    }
    
    public static function configure(Schema $schema): Schema
    {
        self::$isEditing = $schema->getOperation() === 'edit';
        return $schema
            ->components(self::getFormFields());
    }
}
