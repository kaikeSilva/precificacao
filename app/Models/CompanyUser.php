<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class CompanyUser extends Pivot
{
    protected $table = 'company_user';

    protected $fillable = [
        'company_id',
        'user_id',
        'role',
    ];

    public const ROLE_OWNER = 'owner';
    public const ROLE_MANAGER = 'manager';
    public const ROLE_OPERATOR = 'operator';
    public const ROLE_VIEWER = 'viewer';

    public const ROLES = [
        self::ROLE_OWNER,
        self::ROLE_MANAGER,
        self::ROLE_OPERATOR,
        self::ROLE_VIEWER,
    ];

    public const ROLE_LABELS = [
        self::ROLE_OWNER => 'Dono',
        self::ROLE_MANAGER => 'Gerente',
        self::ROLE_OPERATOR => 'Operador',
        self::ROLE_VIEWER => 'Visualizador',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
