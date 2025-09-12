<?php

namespace App\Services;

use App\Models\CompanySettings;
use App\Models\Cost;
use App\Models\PriceScenario;
use App\Models\Recipe;
use Carbon\Carbon;
use Illuminate\Support\Arr;

class PriceScenarioService
{
    public function computeAll(PriceScenario $scenario): array
    {
        $scenario->loadMissing([
            'company',
            'recipe.unit',
            'recipe.recipeItems.ingredient.ingredientCostHistoryItemsLatest',
            'recipe.recipeItems.ingredient.unit',
            'recipe.recipePackagings.packaging.packagingCostHistoryItemsLatest',
            'recipe.recipePackagings.packaging.unit',
            'recipe.recipeLaborRoles.laborRole',
        ]);

        /** @var Recipe $recipe */
        $recipe    = $scenario->recipe;
        $companyId = (int) $scenario->company_id;
        $settings  = $this->getCompanySettings($companyId);

        $overrides = (array) ($scenario->overrides_json ?? []);

        // Quebras detalhadas
        $ingredients = $this->ingredientsBreakdown($recipe, Arr::get($overrides, 'bases.ingredients', []));
        $packagings  = $this->packagingsBreakdown($recipe, Arr::get($overrides, 'bases.packagings', []));
        $labor       = $this->laborBreakdown($recipe, Arr::get($overrides, 'bases.labor', []));

        // Custos alocados detalhados
        $allocated = $this->allocatedBreakdown($companyId, $recipe, $settings);

        $subtotalRaw = $ingredients['total'] + $packagings['total'] + $labor['total'] + $allocated['total'];

        $marginPct  = (float) ($scenario->margin_pct ?? 0.0);
        $withMargin = $subtotalRaw * (1.0 + ($marginPct / 100.0));

        $yieldQty = (float) ($recipe->production_qty ?? 1);

        // valores pré-arredondamento
        $pricePerUnitRaw = $yieldQty > 0 ? ($withMargin / $yieldQty) : 0.0;
        $priceTotalRaw   = $withMargin;

        // arredondamentos
        [$pricePerUnit, $roundingPpu] = $this->applyRoundingWithMeta($settings, $pricePerUnitRaw);
        [$priceTotal,   $roundingTot] = $this->applyRoundingWithMeta($settings, $pricePerUnit * max(1.0, $yieldQty));

        return [
            // ==== Totais “brutos” por grupo (4 casas onde faz sentido) ====
            'totals' => [
                'ingredients' => round($ingredients['total'], 4),
                'packagings'  => round($packagings['total'], 4),
                'labor'       => round($labor['total'], 4),
                'allocated'   => round($allocated['total'], 4),
                'subtotal'    => round($subtotalRaw, 4),
                'margin_pct'  => round($marginPct, 2),
            ],

            // ==== Preços com e sem arredondamento ====
            'pricing' => [
                'price_total_raw'    => round($priceTotalRaw, 2),
                'price_per_unit_raw' => round($pricePerUnitRaw, 2),

                'price_total'        => round($priceTotal, 2),
                'price_per_unit'     => round($pricePerUnit, 2),

                'rounding' => [
                    'mode'         => $settings->rounding_mode ?? CompanySettings::ROUNDING_MODE_UP,
                    'step'         => (float) ($settings->rounding_step ?? 0.01),
                    'total_delta'  => round($priceTotal - $priceTotalRaw, 2),
                    'ppu_delta'    => round($pricePerUnit - $pricePerUnitRaw, 2),
                    'total_meta'   => $roundingTot,
                    'ppu_meta'     => $roundingPpu,
                ],
            ],

            'yield' => [
                'qty'  => $yieldQty,
                'unit' => $recipe->unit?->name,
            ],

            // ==== Quebras detalhadas para tabelas ====
            'breakdowns' => [
                'ingredients' => $ingredients, // items[], total
                'packagings'  => $packagings,  // items[], total
                'labor'       => $labor,       // roles[], minutes, cost_per_min_sum, total
                'allocated'   => $allocated,   // fixed, variable, per_minute_rate, minutes, total
            ],

            // útil para header da view
            'meta' => [
                'recipe_name'  => $recipe->name,
                'company_name' => $scenario->company?->name,
            ],
        ];
    }

    /** ========== INGREDIENTES ========== */
    private function ingredientsBreakdown(Recipe $recipe, array $overrides = []): array
    {
        $items = [];
        $total = 0.0;

        foreach ($recipe->recipeItems as $ri) {
            $ingredient = $ri->ingredient;
            if (!$ingredient) {
                continue;
            }

            $qty     = (float) $ri->qty;
            $lossPct = (float) ($ri->loss_pct ?? 0.0);
            $factor  = 1.0 + ($lossPct / 100.0);

            $cached  = optional($ingredient->ingredientCostHistoryItemsLatest)->current_unit_price;
            $ovr     = Arr::get($overrides, $ingredient->id);

            // prioridade: override -> cached -> 0
            $unitCost = (float) ($ovr ?? ($cached ?? 0.0));

            $line = $qty * $unitCost * $factor;
            $total += $line;

            $items[] = [
                'ingredient_id'   => (int) $ingredient->id,
                'name'            => (string) $ingredient->name,
                'unit'            => $ingredient->unit?->name,
                'qty'             => $qty,
                'loss_pct'        => $lossPct,
                'factor'          => round($factor, 4),

                'price' => [
                    'cached'     => $cached !== null ? (float) $cached : null,
                    'override'   => $ovr    !== null ? (float) $ovr    : null,
                    'effective'  => (float) $unitCost, // usado no cálculo
                    'source'     => $ovr !== null ? 'override' : ($cached !== null ? 'cached' : 'zero'),
                ],

                'line_total'      => round($line, 4),
            ];
        }

        return [
            'items' => $items,
            'total' => $total,
        ];
    }

    /** ========== EMBALAGENS ========== */
    private function packagingsBreakdown(Recipe $recipe, array $overrides = []): array
    {
        $items = [];
        $total = 0.0;

        foreach ($recipe->recipePackagings as $rp) {
            $packaging = $rp->packaging;
            if (!$packaging) {
                continue;
            }

            $qty    = (float) $rp->qty;
            $cached = optional($packaging->packagingCostHistoryItemsLatest)->current_unit_price;
            $ovr    = Arr::get($overrides, $packaging->id);

            $unitCost = (float) ($ovr ?? ($cached ?? 0.0));
            $line     = $qty * $unitCost;
            $total   += $line;

            $items[] = [
                'packaging_id'    => (int) $packaging->id,
                'name'            => (string) $packaging->name,
                'unit'            => $packaging->unit?->name,
                'qty'             => $qty,

                'price' => [
                    'cached'     => $cached !== null ? (float) $cached : null,
                    'override'   => $ovr    !== null ? (float) $ovr    : null,
                    'effective'  => (float) $unitCost,
                    'source'     => $ovr !== null ? 'override' : ($cached !== null ? 'cached' : 'zero'),
                ],

                'line_total'      => round($line, 4),
            ];
        }

        return [
            'items' => $items,
            'total' => $total,
        ];
    }

    /** ========== MÃO DE OBRA ========== */
    private function laborBreakdown(Recipe $recipe, array $overrides = []): array
    {
        $activeOnly   = (bool) ($recipe->active_time_only ?? true);
        $activeMinutes = (int) ($recipe->preparation_min ?? 0);
        if (!$activeOnly) {
            $activeMinutes += (int) ($recipe->resting_min ?? 0);
        }

        $roles = [];
        $costPerMinuteSum = 0.0;

        foreach ($recipe->recipeLaborRoles as $rlr) {
            $role = $rlr->laborRole;
            if (!$role) {
                continue;
            }

            $ovr      = Arr::get($overrides, $role->id);
            $hourCost = (float) ($ovr ?? ($role->hour_cost_cached ?? 0.0));
            $perMin   = $hourCost / 60.0;
            $costPerMinuteSum += $perMin;

            $roles[] = [
                'labor_role_id'  => (int) $role->id,
                'name'           => (string) $role->name,

                'hour_cost' => [
                    'cached'     => $role->hour_cost_cached !== null ? (float) $role->hour_cost_cached : null,
                    'override'   => $ovr !== null ? (float) $ovr : null,
                    'effective'  => (float) $hourCost,
                    'source'     => $ovr !== null ? 'override' : ($role->hour_cost_cached !== null ? 'cached' : 'zero'),
                ],

                'per_minute'     => round($perMin, 6),
                'minutes_used'   => (int) $activeMinutes,
                'line_total'     => round($perMin * $activeMinutes, 4),
            ];
        }

        return [
            'roles'             => $roles,
            'minutes'           => (int) $activeMinutes,
            'cost_per_min_sum'  => round($costPerMinuteSum, 6),
            'total'             => round($activeMinutes * $costPerMinuteSum, 4),
        ];
    }

    /** ========== ALOCADOS ========== */
    private function allocatedBreakdown(int $companyId, Recipe $recipe, CompanySettings $settings): array
    {
        $workMinutesPerMonth = ((int) ($settings->work_hours_per_month ?? 0)) * 60;

        $periodStart = Carbon::now()->startOfMonth();
        $periodEnd   = Carbon::now()->endOfMonth();

        $fixed = (float) Cost::query()
            ->where('company_id', $companyId)
            ->where('type', Cost::TYPE_FIXED)
            ->whereBetween('date', [$periodStart, $periodEnd])
            ->sum('value');

        $variable = (float) Cost::query()
            ->where('company_id', $companyId)
            ->where('type', Cost::TYPE_VARIABLE)
            ->whereBetween('date', [$periodStart, $periodEnd])
            ->sum('value');

        $totalMonthly = $fixed + $variable;

        $activeOnly = (bool) ($recipe->active_time_only ?? true);
        $minutes = (int) ($recipe->preparation_min ?? 0);
        if (!$activeOnly) {
            $minutes += (int) ($recipe->resting_min ?? 0);
        }

        $perMinuteRate = $workMinutesPerMonth > 0 ? ($totalMonthly / $workMinutesPerMonth) : 0.0;
        $total = $perMinuteRate * $minutes;

        return [
            'fixed_monthly'     => round($fixed, 2),
            'variable_monthly'  => round($variable, 2),
            'total_monthly'     => round($totalMonthly, 2),

            'work_minutes_month'=> (int) $workMinutesPerMonth,
            'minutes_recipe'    => (int) $minutes,
            'per_minute_rate'   => round($perMinuteRate, 6),

            'total'             => round($total, 4),
            'period'            => [
                'start' => $periodStart->toDateString(),
                'end'   => $periodEnd->toDateString(),
            ],
        ];
    }

    /** arredondamento com metadados (delta, step, mode já retornados em computeAll) */
    private function applyRoundingWithMeta(CompanySettings $settings, float $value): array
    {
        $mode = $settings->rounding_mode ?? CompanySettings::ROUNDING_MODE_UP;
        $step = (float) ($settings->rounding_step ?? 0.01);

        if ($step <= 0) {
            return [$value, ['mode' => $mode, 'step' => $step, 'delta' => 0.0, 'original' => $value]];
        }

        $quotient = $value / $step;
        switch ($mode) {
            case CompanySettings::ROUNDING_MODE_DOWN:
                $rounded = floor($quotient) * $step;
                break;
            case CompanySettings::ROUNDING_MODE_NEAREST:
                $rounded = round($quotient) * $step;
                break;
            case CompanySettings::ROUNDING_MODE_UP:
            default:
                $rounded = ceil($quotient) * $step;
                break;
        }

        $rounded = round($rounded, 2);

        return [
            $rounded,
            [
                'mode'     => $mode,
                'step'     => $step,
                'delta'    => round($rounded - $value, 2),
                'original' => round($value, 2),
            ],
        ];
    }

    private function getCompanySettings(int $companyId): CompanySettings
    {
        return CompanySettings::query()
            ->where('company_id', $companyId)
            ->first() ?? new CompanySettings();
    }
}
