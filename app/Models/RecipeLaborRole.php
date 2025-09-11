<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Traits\BelongsToCompany;

class RecipeLaborRole extends Model
{
    use SoftDeletes, BelongsToCompany;

    protected $fillable = [
        'company_id',
        'recipe_id',
        'labor_role_id',
        'working_min',
    ];

    protected $casts = [
        'company_id' => 'integer',
        'recipe_id' => 'integer',
        'labor_role_id' => 'integer',
        'working_min' => 'decimal:3',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function recipe(): BelongsTo
    {
        return $this->belongsTo(Recipe::class);
    }

    public function laborRole(): BelongsTo
    {
        return $this->belongsTo(LaborRole::class);
    }
}
