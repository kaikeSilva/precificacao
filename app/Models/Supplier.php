<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use App\Models\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

/**
 * Supplier
 * 
 * @property int $id
 * @property int $company_id
 * @property string $name
 * @property string|null $contact_email
 * @property string|null $phone
 * @property string|null $notes
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $deleted_at
 * @property Company $company
 */
class Supplier extends Model
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
        'contact_email',
        'phone',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'company_id' => 'integer',
        'name' => 'string',
        'contact_email' => 'string',
        'phone' => 'string',
        'notes' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Inverse relation: Supplier belongs to a Company.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Inverse relation: Supplier has many Ingredients.
     */
    public function ingredients(): HasMany
    {
        return $this->hasMany(Ingredient::class);
    }

    /**
     * Cost history items linked to this supplier.
     */
    public function costs(): HasMany
    {
        return $this->hasMany(IngredientCostHistoryItem::class);
    }

    /**
     * Scope para buscar fornecedores com nomes semelhantes.
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
