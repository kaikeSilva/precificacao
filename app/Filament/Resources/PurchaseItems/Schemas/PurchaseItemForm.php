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
use Filament\Forms\Components\Textarea;
use Filament\Support\Icons\Heroicon;

class PurchaseItemForm
{
    public static function getItemTypeOptions(): array
    {
        return [
            Ingredient::class => 'Ingrediente',
            Packaging::class => 'Embalagem',
        ];
    }

    public static function getItemOptions(): array
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
                Hidden::make('item')
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
                    ->label(function ($get) {
                        if (!($get('item_type') && $get('item_id'))) {
                            return 'Quantidade';
                        }
                        return match ($get('item_type')) {
                            Ingredient::class => 'Quantidade em: '.Ingredient::find($get('item_id'))->unit->abbreviation,
                            Packaging::class => 'Quantidade em: '.Packaging::find($get('item_id'))->unit->abbreviation,
                            default => 'Quantidade',
                        };
                    })
                    ->required()
                    ->numeric()
                    ->step('0.01'),
                TextInput::make('unit_price')
                    ->label('Preço unitário')
                    ->required()
                    ->numeric()
                    ->step('0.01'),
                TextInput::make('subtotal')
                    ->label('Valor total')
                    ->required()
                    ->numeric()
                    ->step('0.01'),
            ]);
    }
}
