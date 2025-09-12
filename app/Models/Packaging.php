<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property int $company_id
 * @property string $name
 * @property int $unit_id
 * @property string|null $current_price
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $deleted_at
 */
class Packaging extends Model
{
    use SoftDeletes, BelongsToCompany;

    protected $fillable = [
        'company_id',
        'name',
        'unit_id',
        'current_price',
    ];

    protected $casts = [
        'company_id' => 'integer',
        'name' => 'string',
        'unit_id' => 'integer',
        'current_price' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function packagingCostHistoryItem(): HasMany
    {
        return $this->hasMany(PackagingCostHistoryItem::class);
    }

    public function packagingCostHistoryItemsLatest(): HasOne
    {
        return $this->hasOne(PackagingCostHistoryItem::class)
            ->ofMany(
                ['date' => 'max', 'id' => 'max'],
                function ($query) {
                    $query->where('date', '>=', now()->subMonth());
                }
            );
    }
}
