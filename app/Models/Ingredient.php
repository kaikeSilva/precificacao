<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use App\Models\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Ingredient
 * 
 * @property int $id
 * @property int $company_id
 * @property string $name
 * @property int $unit_id
 * @property string $loss_pct_default
 * @property string|null $notes
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $deleted_at
 * @property-read float|null $unit_cost_cached
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
        'name',
        'unit_id',
        'loss_pct_default',
        'current_price',
        'notes',
    ];

    /**
     * Attribute casting.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'company_id' => 'integer',
        'name' => 'string',
        'unit_id' => 'integer',
        'loss_pct_default' => 'decimal:2',
        'current_price' => 'decimal:2',
        'notes' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Relations
     */
    

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

    public function recipeItems(): HasMany
    {
        return $this->hasMany(RecipeItem::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function ingredientCostHistoryItems(): HasMany
    {
        return $this->hasMany(IngredientCostHistoryItem::class);
    }

    public function ingredientCostHistoryItemsLatest(): HasOne
    {
        return $this->hasOne(IngredientCostHistoryItem::class)
            ->ofMany(
                ['date' => 'max', 'id' => 'max'],
                function ($query) {
                    $query->where('date', '>=', now()->subMonth());
                }
            );
    }
}
