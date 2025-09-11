<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Traits\BelongsToCompany;

class Recipe extends Model
{
    use SoftDeletes, BelongsToCompany;

    protected $fillable = [
        'company_id',
        'unit_id',
        'old_version_id',
        'name',
        'category',
        'production_qty',
        'preparation_min',
        'resting_min',
        'finishing_min',
        'active_time_only',
        'loss_pct',
        'version',
        'notes',
    ];

    protected $casts = [
        'company_id' => 'integer',
        'unit_id' => 'integer',
        'old_version_id' => 'integer',
        'name' => 'string',
        'category' => 'string',
        'production_qty' => 'decimal:3',
        'preparation_min' => 'integer',
        'resting_min' => 'integer',
        'finishing_min' => 'integer',
        'active_time_only' => 'boolean',
        'loss_pct' => 'decimal:2',
        'version' => 'integer',
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

    public function oldVersion(): BelongsTo
    {
        return $this->belongsTo(self::class, 'old_version_id');
    }
}
