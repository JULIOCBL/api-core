<?php

namespace Src\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Modelo Eloquent compartido de roles para módulos del proyecto.
 */
class Role extends Model
{
    use SoftDeletes;

    protected $table = 'roles';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'company_id',
        'user_type_id',
        'name',
        'required_mail',
        'status',
    ];

    protected $casts = [
        'required_mail' => 'boolean',
        'status' => 'boolean',
    ];
}
