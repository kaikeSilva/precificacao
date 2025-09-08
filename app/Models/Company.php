<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
        'name',
        'document',
        'owner_user_id',
        'timezone',
    ];

    public function companyUsers()
    {
        return $this->belongsToMany(User::class, 'company_user')
            ->using(CompanyUser::class)
            ->withPivot('role')
            ->withTimestamps();
    }

    public function owner_user()
    {
        return $this->belongsTo(User::class, 'owner_user_id');
    }

    public static function createCompany($data)
    {
        $company = static::create($data);
        $company->companyUsers()->attach($data['owner_user_id'], ['role' => CompanyUser::ROLE_OWNER]);
        return $company;
    }
}
