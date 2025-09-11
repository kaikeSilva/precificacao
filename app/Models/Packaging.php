<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Traits\BelongsToCompany;

class Packaging extends Model
{
    use SoftDeletes, BelongsToCompany;

    protected $fillable = [
        'company_id',
        'name',
        'unit_id',
        'pack_qty',
        'pack_price',
        'unit_cost_cached',
        'supplier_id',
    ];

    protected $casts = [
        'company_id' => 'integer',
        'name' => 'string',
        'unit_id' => 'integer',
        'pack_qty' => 'integer',
        'pack_price' => 'decimal:2',
        'unit_cost_cached' => 'decimal:4',
        'supplier_id' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $model) {
            $qty = (int) ($model->pack_qty ?: 0);
            $price = (float) ($model->pack_price ?: 0);
            if ($qty > 0) {
                $model->unit_cost_cached = round($price / $qty, 4);
            }
        });
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function historyItems(): HasMany
    {
        return $this->hasMany(PackagingCostHistoryItem::class);
    }
}
