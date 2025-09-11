<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Traits\BelongsToCompany;
/**
 * @property int $id
 * @property int $company_id
 * @property int $packaging_id
 * @property int $supplier_id
 * @property Carbon $date
 * @property decimal $pack_price
 * @property decimal $current_unit_price
 * @property string|null $source
 * @property string|null $notes
 */
class PackagingCostHistoryItem extends Model
{
    use BelongsToCompany;

    protected $fillable = [
        'company_id',
        'packaging_id',
        'supplier_id',
        'date',
        'pack_price',
        'current_unit_price',
        'source',
        'notes',
    ];

    protected $casts = [
        'company_id' => 'integer',
        'packaging_id' => 'integer',
        'supplier_id' => 'integer',
        'date' => 'date',
        'pack_price' => 'decimal:2',
        'current_unit_price' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function packaging(): BelongsTo
    {
        return $this->belongsTo(Packaging::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }
}
