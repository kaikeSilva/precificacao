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
                    ->required()
                    ->numeric()
                    ->default(220),
                Select::make('rounding_mode')
                    ->required()
                    ->default(CompanySettings::ROUNDING_MODE_UP)
                    ->options(CompanySettings::ROUNDING_MODES),
                TextInput::make('rounding_step')
                    ->required()
                    ->numeric()
                    ->default(0.05),
                TextInput::make('currency')
                    ->required()
                    ->default('BRL'),
                Toggle::make('active_time_only')
                    ->required(),
                TextInput::make('pricing_min_margin_pct')
                    ->required()
                    ->numeric()
                    ->default(30),
                TextInput::make('pricing_max_margin_pct')
                    ->required()
                    ->numeric()
                    ->default(50),
            ]);
    }
}
