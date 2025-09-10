<?php

namespace App\Filament\Resources\CompanySettings\Schemas;

use App\Models\CompanySettings;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CompanySettingsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('work_hours_per_month')
                    ->label('Horas por mês')
                    ->required()
                    ->numeric()
                    ->default(220),
                Select::make('rounding_mode')
                    ->label('Arredondamento')
                    ->required()
                    ->default(CompanySettings::ROUNDING_MODE_UP)
                    ->options(CompanySettings::ROUNDING_MODES),
                TextInput::make('rounding_step')
                    ->label('Passo de arredondamento')
                    ->required()
                    ->numeric()
                    ->default(0.05),
                TextInput::make('currency')
                    ->label('Moeda')
                    ->required()
                    ->default('BRL'),
                Toggle::make('active_time_only')
                    ->label('Ativo apenas no horário')
                    ->required(),
                TextInput::make('pricing_min_margin_pct')
                    ->label('Margem mínima')
                    ->required()
                    ->numeric()
                    ->default(30),
                TextInput::make('pricing_max_margin_pct')
                    ->label('Margem máxima')
                    ->required()
                    ->numeric()
                    ->default(50),
            ]);
    }
}
