<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use App\Models\Traits\BelongsToCompany;

/**
 * Ingredient
 * 
 * @property int $id
 * @property int $company_id
 * @property int|null $supplier_id
 * @property string $name
 * @property string $unit
 * @property string $pack_unit
 * @property float $pack_qty
 * @property string $pack_price
 * @property string $loss_pct_default
 * @property string|null $notes
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $deleted_at
 * @property-read float|null $unit_cost_cached
 * @property Supplier|null $supplier
 */
class Ingredient extends Model
{
    use SoftDeletes, BelongsToCompany;

    /**
     * Mass assignable attributes.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'company_id',
        'supplier_id',
        'name',
        'unit',
        'pack_qty',
        'pack_unit',
        'pack_price',
        'loss_pct_default',
        'notes',
    ];

    /**
     * Attribute casting.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'company_id' => 'integer',
        'supplier_id' => 'integer',
        'name' => 'string',
        'unit' => 'string',
        'pack_qty' => 'decimal:3',
        'pack_unit' => 'string',
        'pack_price' => 'decimal:2',
        'loss_pct_default' => 'decimal:2',
        'notes' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Relations
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Inverse relation: Ingredient belongs to a Company.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function foodCategories(): BelongsToMany
    {
        return $this->belongsToMany(FoodCategory::class)
            ->withPivot('loss_pct');
    }

    /**
     * Costs history items for this ingredient.
     */
    public function costs(): HasMany
    {
        return $this->hasMany(IngredientCostHistoryItem::class);
    }

    /**
     * Business rule: cached unit cost = pack_price / convert(pack_qty, pack_unit -> unit)
     */
    public function getUnitCostCachedAttribute(): ?float
    {
        $qtyInTargetUnit = self::convertQuantity(
            quantity: (float) $this->pack_qty,
            fromUnit: (string) $this->pack_unit,
            toUnit: (string) $this->unit,
        );

        if ($qtyInTargetUnit === null || $qtyInTargetUnit <= 0) {
            return null;
        }

        return (float) $this->pack_price / $qtyInTargetUnit;
    }

    /**
     * Convert quantity between supported units keeping dimensions consistent.
     * Supports mass: g <-> kg, volume: ml <-> l, count: un.
     * Returns null for incompatible dimensions.
     */
    protected static function convertQuantity(float $quantity, string $fromUnit, string $toUnit): ?float
    {
        $fromUnit = strtolower($fromUnit);
        $toUnit = strtolower($toUnit);

        if ($fromUnit === $toUnit) {
            return $quantity;
        }

        $mass = ['g' => 1.0, 'kg' => 1000.0];
        $volume = ['ml' => 1.0, 'l' => 1000.0];
        $count = ['un' => 1.0];

        $groups = [
            'mass' => $mass,
            'volume' => $volume,
            'count' => $count,
        ];

        $fromGroup = null; $toGroup = null;
        foreach ($groups as $name => $map) {
            if (array_key_exists($fromUnit, $map)) $fromGroup = $name;
            if (array_key_exists($toUnit, $map)) $toGroup = $name;
        }

        if ($fromGroup === null || $toGroup === null || $fromGroup !== $toGroup) {
            return null; // incompatible dimensions
        }

        $map = $groups[$fromGroup];
        // Convert from source to base
        $inBase = $quantity * $map[$fromUnit];
        // Convert from base to target
        return $inBase / $map[$toUnit];
    }
}
