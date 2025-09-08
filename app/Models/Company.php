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

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function owner_user()
    {
        return $this->belongsTo(User::class, 'owner_user_id');
    }
}
