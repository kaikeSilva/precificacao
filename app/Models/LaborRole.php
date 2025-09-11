<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use App\Models\Traits\BelongsToCompany;

class LaborRole extends Model
{
    use SoftDeletes, BelongsToCompany;

    protected $fillable = [
        'company_id',
        'name',
        'monthly_salary',
        'hours_per_month',
        'hour_cost_cached',
    ];

    protected $casts = [
        'company_id' => 'integer',
        'name' => 'string',
        'monthly_salary' => 'decimal:2',
        'hours_per_month' => 'integer',
        'hour_cost_cached' => 'decimal:4',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $model) {
            $hours = (int) $model->hours_per_month;
            $salary = (float) $model->monthly_salary;
            $model->hour_cost_cached = self::calculateHourCostCached($salary, $hours);
        });
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public static function calculateHourCostCached($salary, $hours)
    {
        $salary = (float) ($salary ?: 0);
        $hours = (int) ($hours ?: 0);
        if ($hours == 0) {
            return 0;
        }
        
        return round($salary / $hours, 4);
    }
}
