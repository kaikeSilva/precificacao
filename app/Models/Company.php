<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Company extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The primary key type is UUID (string) and not incrementing.
     */
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'name',
        'document',
        'owner_user_id',
        'timezone',
    ];

    protected $attributes = [
        'timezone' => 'America/Sao_Paulo',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    public function ownerUser()
    {
        return $this->belongsTo(User::class, 'owner_user_id');
    }
}
