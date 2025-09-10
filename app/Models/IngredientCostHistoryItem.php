<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use App\Models\Traits\BelongsToCompany;

/**
 * IngredientCostHistoryItem
 * 
 * @property int $id
 * @property int $company_id
 * @property int $ingredient_id
 * @property int $supplier_id
 * @property string $date
 * @property string $pack_price
 * @property string|null $source
 * @property string|null $notes
 * @property Carbon $created_at
 * @property Company $company
 * @property Ingredient $ingredient
 * @property Supplier $supplier
 */
class IngredientCostHistoryItem extends Model
{
    use BelongsToCompany;

    /**
     * This model does not have an updated_at column.
     */
    public const UPDATED_AT = null;

    protected $fillable = [
        'company_id',
        'ingredient_id',
        'supplier_id',
        'date',
        'pack_price',
        'source',
        'notes',
    ];

    protected $casts = [
        'company_id' => 'integer',
        'ingredient_id' => 'integer',
        'supplier_id' => 'integer',
        'date' => 'date',
        'pack_price' => 'decimal:2',
        'source' => 'string',
        'notes' => 'string',
        'created_at' => 'datetime',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function ingredient(): BelongsTo
    {
        return $this->belongsTo(Ingredient::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }
}
