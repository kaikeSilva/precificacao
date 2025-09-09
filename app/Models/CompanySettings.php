<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;
/**
 * CompanySettings
 * 
 * @property int $id
 * @property int $company_id
 * @property int $work_hours_per_month
 * @property string $rounding_mode
 * @property float $rounding_step
 * @property string $currency
 * @property bool $active_time_only
 * @property float $pricing_min_margin_pct
 * @property float $pricing_max_margin_pct
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class CompanySettings extends Model
{
    // Rounding mode constants
    const ROUNDING_MODE_UP = 'up';
    const ROUNDING_MODE_NEAREST = 'nearest';
    const ROUNDING_MODE_DOWN = 'down';

    const ROUNDING_MODES = [
        self::ROUNDING_MODE_UP => 'Para Cima',
        self::ROUNDING_MODE_NEAREST => 'Mais Próximo',
        self::ROUNDING_MODE_DOWN => 'Para Baixo',
    ];

    protected $fillable = [
        'company_id',
        'work_hours_per_month',
        'rounding_mode',
        'rounding_step',
        'currency',
        'active_time_only',
        'pricing_min_margin_pct',
        'pricing_max_margin_pct',
    ];

    protected $casts = [
        'work_hours_per_month' => 'integer',
        'rounding_step' => 'decimal:2',
        'active_time_only' => 'boolean',
        'pricing_min_margin_pct' => 'decimal:2',
        'pricing_max_margin_pct' => 'decimal:2',
    ];

    protected $attributes = [
        'work_hours_per_month' => 220,
        'rounding_mode' => self::ROUNDING_MODE_UP,
        'rounding_step' => 0.05,
        'currency' => 'BRL',
        'active_time_only' => true,
        'pricing_min_margin_pct' => 30.00,
        'pricing_max_margin_pct' => 50.00,
    ];

    /**
     * Get the company that owns the settings.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the rounding mode label.
     */
    public function getRoundingModeLabel(): string
    {
        return self::ROUNDING_MODES[$this->rounding_mode] ?? $this->rounding_mode;
    }

    /**
     * Scope to filter by company.
     */
    public function scopeForCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }
}
