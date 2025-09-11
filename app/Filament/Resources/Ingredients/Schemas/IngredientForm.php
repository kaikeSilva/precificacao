<?php

namespace App\Filament\Resources\Ingredients\Schemas;

use App\Filament\Resources\Suppliers\Schemas\SupplierForm;
use App\Filament\Resources\Units\Schemas\UnitForm;
use App\Models\Unit;
use Closure;
use Filament\Actions\Action;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class IngredientForm
{
    public static function getFormFields(): array
    {
        return [
            Hidden::make('company_id')
                ->default(fn () => function_exists('currentCompanyId') ? currentCompanyId() : null)
                ->dehydrated(),
            TextInput::make('name')
                ->label('Nome')
                ->required(),
            UnitForm::getUnitDefaultSelect('unit_id'),
            TextInput::make('loss_pct_default')
                ->label('Perda padrão (%)')
                ->numeric()
                ->step('0.01')
                ->hintActions([
                    Action::make('ajuda_loss_pct_default')
                        ->label('Ajuda')
                        ->icon(Heroicon::InformationCircle) // ícone de informação
                        ->color('info')
                        ->modalHeading('Como preencher a “Perda padrão (%)”')
                        ->modalDescription('Perda padrão em porcentagem, este valor será usado como valor base na criação de receitas. Serve para definir indices de desperdício para o calculo do preço final.')
                        ->modalSubmitActionLabel('Fechar')   // botão do modal
                        ->modalIcon(Heroicon::InformationCircle),
                ])
                ->default(0),
            Textarea::make('notes')
                ->label('Observações')
                ->columnSpanFull(),
        ];
    }

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components(self::getFormFields());
    }
}

