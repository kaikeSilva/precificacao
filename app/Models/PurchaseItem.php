<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use App\Models\Traits\BelongsToCompany;

class PurchaseItem extends Model
{
    use SoftDeletes, BelongsToCompany;

    protected $fillable = [
        'company_id',
        'purchase_id',
        'item_type',
        'item_id',
        'qty',
        'unit_price',
        'subtotal',
        'quantity_item_unity',
    ];

    protected $casts = [
        'company_id' => 'integer',
        'purchase_id' => 'integer',
        'item_type' => 'string',
        'item_id' => 'integer',
        'qty' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'quantity_item_unity' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function purchase(): BelongsTo
    {
        return $this->belongsTo(Purchase::class);
    }

    public function item(): MorphTo
    {
        return $this->morphTo();
    }
}
