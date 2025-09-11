<?php

namespace App\Filament\Resources\Recipes\Schemas;

use App\Filament\Resources\Units\Schemas\UnitForm;
use App\Models\Unit;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class RecipeForm
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
                UnitForm::getUnitDefaultSelect('unit_id'),
                TextInput::make('production_qty')
                    ->label(function ($get) {
                        if (!$get('unit_id')) {
                            return 'Rendimento da receita';
                        }
                        $unit = Unit::find($get('unit_id'));
                        return 'Rendimento da receita (' . $unit->name . ')';
                    })
                    ->required()
                    ->numeric()
                    ->step('0.001'),
                TextInput::make('preparation_min')
                    ->label('Tempo de preparo (min)')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('resting_min')
                    ->label('Descanso (min)')
                    ->required()
                    ->numeric()
                    ->default(0),
                Toggle::make('active_time_only')
                    ->label('Contar apenas tempo ativo')
                    ->required(),
                TextInput::make('loss_pct')
                    ->label('Perda (%)')
                    ->required()
                    ->numeric()
                    ->step('0.01')
                    ->default(0),
                Textarea::make('notes')
                    ->label('Observações')
                    ->nullable()
                    ->columnSpanFull(),
            ]);
    }
}
