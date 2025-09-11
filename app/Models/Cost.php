<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use App\Models\Traits\BelongsToCompany;
use App\Models\Company;

/**
 * Cost
 *
 * @property int $id
 * @property int $company_id
 * @property string $type
 * @property string $name
 * @property string|null $category
 * @property string $value
 * @property string $date
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $deleted_at
 */
class Cost extends Model
{
    use SoftDeletes, BelongsToCompany;

    protected $fillable = [
        'company_id',
        'type',
        'name',
        'category',
        'value',
        'date',
    ];

    protected $casts = [
        'company_id' => 'integer',
        'type' => 'string',
        'name' => 'string',
        'category' => 'string',
        'value' => 'decimal:2',
        'date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
