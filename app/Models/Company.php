<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Company
 * 
 * @property int $id
 * @property string $name
 * @property string $document
 * @property string $timezone
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Collection|CompanyUser[] $companyUsers
 * @property CompanySettings $settings
 * @property HasMany|Collection|CompanyUser[] $owners
 * @property Collection|CompanyUser[] $selectUsers
 */
class Company extends Model
{
    protected $fillable = [
        'name',
        'document',
        'timezone',
    ];

    public function companyUsers()
    {
        return $this->hasMany(CompanyUser::class);
    }

    public function settings()
    {
        return $this->hasOne(CompanySettings::class);

    }

    public function owners()
    {
        return $this->hasMany(CompanyUser::class)
            ->where('role', CompanyUser::ROLE_OWNER)
            ->with('user');
    }
}
