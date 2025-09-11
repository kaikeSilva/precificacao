<?php

namespace App\Filament\Resources\PurchaseItems\Schemas;

use App\Filament\Resources\Ingredients\Schemas\IngredientForm;
use App\Filament\Resources\Packagings\Schemas\PackagingForm;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use App\Models\Ingredient;
use App\Models\Packaging;
use App\Models\Unit;
use Filament\Actions\Action;
use Filament\Forms\Components\MorphToSelect;
use Filament\Forms\Components\MorphToSelect\Type;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class PurchaseItemForm
{
    private static function getItemTypeOptions(): array
    {
        return [
            Ingredient::class => 'Ingrediente',
            Packaging::class => 'Embalagem',
        ];
    }

    private static function getItemOptions(): array
    {
        return [
            Ingredient::class => Ingredient::query()->orderBy('name')->pluck('name', 'id'),
            Packaging::class => Packaging::query()->orderBy('name')->pluck('name', 'id'),
        ];
    }

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('company_id')
                    ->default(fn () => function_exists('currentCompanyId') ? currentCompanyId() : null)
                    ->dehydrated(),
                MorphToSelect::make('item')
                    ->types([
                        Type::make(Ingredient::class)
                            ->label('Ingrediente')
                            ->modifyOptionsQueryUsing(fn (Builder $query) => $query->where('company_id', currentCompanyId()))
                            ->getOptionLabelFromRecordUsing(fn (Ingredient $record) => "{$record->name} ({$record->unit->abbreviation})")
                            ->modifyKeySelectUsing(fn (Select $select): Select => $select
                            ->createOptionForm(IngredientForm::getFormFields())
                            ->createOptionUsing(function (array $data): int {
                                return Ingredient::create($data)->getKey();
                            })),
                        Type::make(Packaging::class)
                            ->label('Embalagem')
                            ->modifyOptionsQueryUsing(fn (Builder $query) => $query->where('company_id', currentCompanyId()))
                            ->getOptionLabelFromRecordUsing(fn (Packaging $record) => "{$record->name} ({$record->unit->abbreviation})")
                            ->modifyKeySelectUsing(fn (Select $select): Select => $select
                            ->createOptionForm(PackagingForm::getFormFields())
                            ->createOptionUsing(function (array $data): int {
                                return Packaging::create($data)->getKey();
                            })),
                    ])
                    ->label('Item')
                    ->searchable()
                    ->preload()
                    ->optionsLimit(20)
                    ->live()
                    ->required(),
                TextInput::make('qty')
                    ->label('Unidades compradas')
                    ->required()
                    ->numeric()
                    ->debounce(500)
                    ->afterStateUpdated(fn (Set $set, Get $get) => self::recalcSubtotal($set, $get))
                    ->step('0.01'),
                TextInput::make('unit_price')
                    ->label('Preço unitário')
                    ->required()
                    ->numeric()
                    ->debounce(500)
                    ->afterStateUpdated(fn (Set $set, Get $get) => self::recalcSubtotal($set, $get))
                    ->step('0.01'),
                TextInput::make('quantity_item_unity')
                    ->label(fn (Get $get) => self::getQuantityItemUnityLabel($get))
                    ->required()
                    ->numeric()
                    ->hintActions([
                        Action::make('ajuda_loss_pct_default')
                            ->label('Ajuda')
                            ->icon(Heroicon::InformationCircle) // ícone de informação
                            ->color('info')
                            ->modalHeading('Como preencher a “Perda padrão (%)”')
                            ->modalDescription(self::quantityItemUnityHtmlHint())
                            ->modalCancelActionLabel('Fechar')
                            ->modalIcon(Heroicon::InformationCircle),
                    ])
                    ->step('0.01'),
                TextInput::make('subtotal')
                    ->label('Valor total')
                    ->required()
                    ->readOnly()
                    ->dehydrated(true)
                    ->numeric()
                    ->step('0.01'),
            ]);
    }

    private static function getQuantityItemUnityLabel(Get $get): string
    {
        if (!($get('item_type') && $get('item_id'))) {
            return 'Quantidade na unidade de medida do item em UMA unidade comprada';
        }
        return match ($get('item_type')) {
            Ingredient::class => 'Quantidade na unidade de medida do ingrediente em UMA unidade comprada: '.Ingredient::find($get('item_id'))->unit->abbreviation,
            Packaging::class => 'Quantidade na unidade de medida do embalagem em UMA unidade comprada: '.Packaging::find($get('item_id'))->unit->abbreviation,
            default => 'Quantidade na unidade de medida',
        };
    }
    
    private static function quantityItemUnityHtmlHint(): HtmlString {
        return new HtmlString(<<<'HTML'
                <div class="space-y-3 text-sm leading-6">
                    <p>
                        Informe <strong>quantas unidades da <em>unidade base</em> do item</strong> existem em
                        <strong>1 (uma)</strong> unidade comprada na nota.
                        <br><span class="text-gray-500">Não é o total do pedido — é por unidade da compra.</span>
                    </p>
        
                    <div class="rounded-md border border-yellow-200 bg-yellow-50 p-3">
                        <p class="font-medium text-yellow-800">
                            Exemplos
                        </p>
                        <ul class="mt-1 list-disc pl-5 text-yellow-900">
                            <li><strong>Óleo de gergelim</strong> (base: <em>ml</em>) — garrafa de 500 ml → preencha <strong>500</strong>.</li>
                            <li><strong>Farinha</strong> (base: <em>g</em>) — saco de 1 kg → preencha <strong>1000</strong>.</li>
                            <li><strong>Ovos</strong> (base: <em>un</em>) — caixa com 30 ovos → preencha <strong>30</strong>.</li>
                            <li>Se a unidade comprada <em>já é</em> a base (ex.: compra em ml e a base é ml) → preencha <strong>1</strong>.</li>
                        </ul>
                    </div>
        
                    <div class="rounded-md border border-slate-200 bg-slate-50 p-3">
                        <p class="font-medium text-slate-800">Fórmulas úteis</p>
                        <ul class="mt-1 list-disc pl-5 text-slate-900">
                            <li><strong>Quantidade total (na base)</strong> = Unidades compradas × este campo</li>
                            <li><strong>Custo por unidade base</strong> = Preço unitário ÷ este campo</li>
                        </ul>
                    </div>
                </div>
            HTML
        );
    }

    private static function recalcSubtotal($set, $get): void
    {
        $qty  = (float) str_replace(',', '.', (string) ($get('qty') ?? 0));
        $unit = (float) str_replace(',', '.', (string) ($get('unit_price') ?? 0));

        $value = round($qty * $unit, 2);

        $set('subtotal', $value);
    }
}
