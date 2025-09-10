<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * IngredientFoodCategory (pivot)
 * 
 * @property int $id
 * @property int $ingredient_id
 * @property int $food_category_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $deleted_at
 * @property Ingredient $ingredient
 * @property FoodCategory $foodCategory
 */
class IngredientFoodCategory extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'ingredient_id',
        'food_category_id',
    ];

    protected $casts = [
        'ingredient_id' => 'integer',
        'food_category_id' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function ingredient(): BelongsTo
    {
        return $this->belongsTo(Ingredient::class);
    }

    public function foodCategory(): BelongsTo
    {
        return $this->belongsTo(FoodCategory::class);
    }
}
