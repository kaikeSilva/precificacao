<?php

namespace App\Filament\Resources\Suppliers\Schemas;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use App\Models\Supplier;
use Closure;

class SupplierForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('company_id')
                    ->default(function () {
                        return function_exists('currentCompanyId') ? currentCompanyId() : null;
                    })
                    ->dehydrated(),
                TextInput::make('name')
                    ->label('Nome')
                    ->rules([
                        fn (): Closure => function (string $attribute, $value, Closure $fail) {
                            $supplier = Supplier::similarName($value)->first();
                            if ($supplier) {
                                $fail('Já existe um fornecedor com o nome: ' . $supplier->name);
                            }
                        },
                    ])
                    ->required(),
                TextInput::make('contact_email')
                    ->label('E-mail')
                    ->email(),
                TextInput::make('phone')
                    ->label('Telefone')
                    ->tel(),
                Textarea::make('notes')
                    ->label('Observações')
                    ->columnSpanFull(),
            ]);
    }
}
