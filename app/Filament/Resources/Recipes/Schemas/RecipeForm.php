<?php

namespace App\Filament\Resources\Recipes\Schemas;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
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
                Select::make('unit_id')
                    ->label('Unidade')
                    ->relationship('unit', 'name')
                    ->searchable()
                    ->required(),
                Select::make('old_version_id')
                    ->label('Versão anterior')
                    ->relationship('oldVersion', 'name')
                    ->searchable()
                    ->nullable(),
                TextInput::make('name')
                    ->label('Nome')
                    ->required(),
                TextInput::make('category')
                    ->label('Categoria')
                    ->nullable(),
                TextInput::make('production_qty')
                    ->label('Qtd produzida')
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
                TextInput::make('finishing_min')
                    ->label('Acabamento (min)')
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
                TextInput::make('version')
                    ->label('Versão')
                    ->required()
                    ->numeric()
                    ->default(1),
                Textarea::make('notes')
                    ->label('Observações')
                    ->nullable()
                    ->columnSpanFull(),
            ]);
    }
}
