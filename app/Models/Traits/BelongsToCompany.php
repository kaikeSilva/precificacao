<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;

trait BelongsToCompany
{
    protected static function bootBelongsToCompany(): void
    {
        static::creating(function ($model) {
            // dd("dentro do create ", $model, currentCompanyId());
            if (empty($model->company_id) && function_exists('currentCompanyId') && currentCompanyId()) {
                $model->company_id = currentCompanyId();
            }
        });

        static::addGlobalScope('company', function (Builder $builder) {
            if (function_exists('currentCompanyId')) {
                $companyId = currentCompanyId();
                if ($companyId) {
                    $builder->where($builder->getModel()->getTable() . '.company_id', $companyId);
                }
            }
        });
    }

    public static function withoutCompanyScope(): Builder
    {
        return static::withoutGlobalScope('company')->newQuery();
    }
}
