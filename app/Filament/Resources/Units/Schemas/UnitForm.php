<?php

namespace App\Filament\Resources\Units\Schemas;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use App\Models\Unit;
use Closure;
class UnitForm
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
                    ->rules([
                        fn (): Closure => function (string $attribute, $value, Closure $fail) {
                            $unit = Unit::similarName($value)->first();
                            if ($unit) {
                                $fail('Já existe uma unidade com o nome: ' . $unit->name);
                            }
                        },
                    ]),
                TextInput::make('abbreviation')
                    ->label('Abreviação')
                    ->maxLength(10)
            ]);
    }
}
