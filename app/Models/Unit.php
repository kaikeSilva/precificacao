<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use App\Models\Traits\BelongsToCompany;
use App\Models\Company;
use Illuminate\Database\Eloquent\Builder;
/**
 * Unit
 * 
 * @property int $id
 * @property int $company_id
 * @property string $name
 * @property string|null $abbreviation
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $deleted_at
 * @property Company $company
 */
class Unit extends Model
{
    use SoftDeletes, BelongsToCompany;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'company_id',
        'name',
        'abbreviation',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'company_id' => 'integer',
        'name' => 'string',
        'abbreviation' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Inverse relation: Unit belongs to a Company.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function recipes(): HasMany
    {
        return $this->hasMany(Recipe::class);
    }

        /**
     * Scope para buscar unidades com nomes semelhantes.
     * Normaliza o texto removendo acentos e usando lower-case.
     */
    public function scopeSimilarName(Builder $query, string $value): Builder
    {
        $normalized = Str::of($value)->lower()->ascii()->value();

        return $query->where(function ($q) use ($normalized) {
                $q->whereRaw('LOWER(name) = ?', [$normalized]);
            });
    }
}
