<?php

namespace App\Filament\Resources\Units\Schemas;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use App\Models\Unit;
use Closure;
class UnitForm
{

    public static function getUnitDefaultSelect($name): Select
    {
        return Select::make($name)
            ->createOptionUsing(function (array $data): int {
                return Unit::create($data)->getKey();
            })
            ->options(Unit::all()->pluck('name', 'id'))
            ->createOptionForm(UnitForm::getFormFields())
            ->label('Unidade')
            ->searchable()
            ->live()
            ->optionsLimit(20)
            ->preload();
    }

    public static function getFormFields(): array
    {
        return [
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
        ];
    }

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components(self::getFormFields($schema));
    }
}
