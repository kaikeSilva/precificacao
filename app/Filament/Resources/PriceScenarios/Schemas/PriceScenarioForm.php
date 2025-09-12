<?php

namespace App\Filament\Resources\PriceScenarios\Schemas;

use App\Filament\Schemas\Components\RowCard;
use App\Models\PriceScenario;
use App\Models\Recipe;
use App\Models\RecipeItem;
use App\Models\RecipePackaging;
use App\Models\RecipeLaborRole;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Text;
use Filament\Schemas\Schema;

final class PriceScenarioForm
{
    /** memo por request (não persiste entre requests) */
    private static array $memo = [];

    /** Busca TUDO de uma vez e devolve arrays mínimos */
    private static function fetchRecipeData(int $recipeId): array
    {
        if (isset(self::$memo[$recipeId])) {
            return self::$memo[$recipeId];
        }

        // public function recipeItems(): HasMany
        // {
        //     return $this->hasMany(RecipeItem::class);
        // }
    
        // public function recipePackagings(): HasMany
        // {
        //     return $this->hasMany(RecipePackaging::class);
        // }
    
        // public function recipeLaborRoles(): HasMany
        // {
        //     return $this->hasMany(RecipeLaborRole::class);
        // }

        $recipe = Recipe::query()
            ->with(
                'recipeItems.ingredient.ingredientCostHistoryItemsLatest', 
                'recipeItems.ingredient.unit',
                'recipePackagings.packaging.packagingCostHistoryItemsLatest',
                'recipePackagings.packaging.unit',
                'recipeLaborRoles.laborRole'
            )
            ->where('id', $recipeId)
            ->first();

            // Ingredientes (id, name, unit, current_unit_price)
        $ingredients = $recipe->recipeItems->map(function ($r) {
            $unit = $r->ingredient->unit->name ?? '-';
            $price = $r->ingredient->ingredientCostHistoryItemsLatest->current_unit_price ?? null;

            return [
                'id' => (int) $r->ingredient->id,
                'name' => (string) $r->ingredient->name,
                'unit' => (string) $unit,
                'current_unit_price' => $price !== null ? (float) $price : null,
            ];
        })->all();

        // Embalagens (id, name, unit, current_unit_price)
        $packagings = $recipe->recipePackagings->map(function ($r) {
            $unit = $r->packaging->unit->name ?? '-';
            $price = $r->packaging->packagingCostHistoryItemsLatest->current_unit_price ?? null;

            return [
                'id' => (int) $r->packaging->id,
                'name' => (string) $r->packaging->name,
                'unit' => (string) $unit,
                'current_unit_price' => $price !== null ? (float) $price : null,
            ];
        })->all();

        // Mão de obra (id, name)
        $labor = $recipe->recipeLaborRoles->map(fn ($r) => [
            'id' => (int) $r->laborRole->id,
            'name' => (string) $r->laborRole->name,
            'hour_cost_cached' => (float) $r->laborRole->hour_cost_cached,
        ])->all();
        return self::$memo[$recipeId] = [
            'ingredients' => $ingredients,
            'packagings'  => $packagings,
            'labor'       => $labor,
        ];
    }

    private static function buildRows(array $items, string $pathPrefix, string $keyPrefix, ?PriceScenario $record): array
    {
        $fmt = fn ($v) => 'R$ ' . number_format((float) $v, 4, ',', '.');
    
        $rows = [];
        foreach ($items as $row) {
            $id   = $row['id'];
            $name = $row['name'];
            $unit = $row['unit'] ?? '-';
    
            // --- meta (igual ao seu, só reorganizado) ---
            $meta = '';
            if (\array_key_exists('current_unit_price', $row)) {
                $meta = $row['current_unit_price'] !== null && $row['current_unit_price'] !== ''
                    ? "{$unit} : {$fmt($row['current_unit_price'])}"
                    : "{$unit} : sem preço considerado 0";
            }
            if (\array_key_exists('hour_cost_cached', $row)) {
                $meta .= $row['hour_cost_cached'] !== null && $row['hour_cost_cached'] !== ''
                    ? " : Custo por hora {$fmt($row['hour_cost_cached'])}"
                    : " : sem custo por hora considerado 0";
            }
    
            // --- pega override salvo com segurança ---
            $override = data_get($record, "overrides_json.bases.$pathPrefix.$id", null);
    
            // Trata strings vazias como null para a coalescência
            $curPrice = \array_key_exists('current_unit_price', $row)
                ? ($row['current_unit_price'] === '' ? null : $row['current_unit_price'])
                : null;
    
            $hourCost = \array_key_exists('hour_cost_cached', $row)
                ? ($row['hour_cost_cached'] === '' ? null : $row['hour_cost_cached'])
                : null;
    
            // --- ordem de preferência: override > current_unit_price > hour_cost_cached > 0 ---
            $default = $override ?? $curPrice ?? $hourCost ?? 0;
            $isUsingOverride = $pathPrefix == 'ingredients' || $pathPrefix == 'packagings' ? $override !== $curPrice : $override !== $hourCost;
            $rows[] = RowCard::make()
                ->key("$keyPrefix-row-$id")
                ->gridGap(4)
                ->nameProperty($name)
                ->metaProperty($meta)
                ->columnSpan('full')
                ->schema([
                    TextInput::make("bases.$pathPrefix.$id")
                        ->key("$keyPrefix-input-$id")
                        ->label('Mudar valor base')
                        ->numeric()->step('0.0001')
                        ->placeholder('0,00')
                        ->lazy()
                        // default cobre "create"
                        ->default($default)
                        // e aqui cobre "edit" quando não há valor salvo
                        ->afterStateHydrated(function (TextInput $component, $state) use ($default) {
                            if ($state === null || $state === '') {
                                $component->state($default);
                            }
                        })
                        ->helperText(
                            $isUsingOverride
                                ? 'Usando valor diferente do padrão'
                                : null
                        )
                        ->columnSpan('full'),
                ]);
        }
    
        return $rows;
    }
        
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Hidden::make('company_id')
                ->default(fn () => function_exists('currentCompanyId') ? currentCompanyId() : null)
                ->dehydrated(),

            Hidden::make('recipe_id')
                ->default(fn () => request()->has('recipe') ? (int) request()->query('recipe') : null)
                ->dehydrated(),

            TextInput::make('name')->label('Nome do cenário')->required(),

            TextInput::make('margin_pct')
                ->label('Margem (%)')
                ->required()->numeric()->step('0.01')->default(30.00),

            Hidden::make('overrides_json')
                ->dehydrated(),

            // ----- Tabs: tudo montado, sem remount server-side -----
            Tabs::make('visao_receita')
                ->tabs(function ($get, $record) {
                    $recipeId = (int) ($get('recipe_id') ?? 0);
                    if (!$recipeId) {
                        return [
                            Tab::make('Ingredientes (0)')->schema([Text::make('Nenhum ingrediente cadastrado.')]),
                            Tab::make('Embalagens (0)')->schema([Text::make('Nenhuma embalagem cadastrada.')]),
                            Tab::make('Mão de obra (0)')->schema([Text::make('Nenhum perfil de mão de obra cadastrado.')]),
                        ];
                    }

                    $all = self::fetchRecipeData($recipeId);

                    $ingRows = self::buildRows($all['ingredients'], 'ingredients', 'ing', $record);
                    $pkgRows = self::buildRows($all['packagings'],  'packagings',  'pkg', $record);
                    $labRows = self::buildRows($all['labor'],       'labor',       'lab', $record);

                    return [
                        Tab::make('Ingredientes (' . count($ingRows) . ')')
                            ->schema(!empty($ingRows) ? $ingRows : [Text::make('Nenhum ingrediente cadastrado.')]),

                        Tab::make('Embalagens (' . count($pkgRows) . ')')
                            ->schema(!empty($pkgRows) ? $pkgRows : [Text::make('Nenhuma embalagem cadastrada.')]),

                        Tab::make('Mão de obra (' . count($labRows) . ')')
                            ->schema(!empty($labRows) ? $labRows : [Text::make('Nenhum perfil de mão de obra cadastrado.')]),
                    ];
                })
                ->columnSpan('full')
                ->persistTabInQueryString(), // opcional
        ]);
    }
}
