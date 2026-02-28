<?php

namespace Src\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Modelo Eloquent compartido de permisos asignados a roles.
 */
class RolePermission extends Model
{
    use SoftDeletes;

    protected $table = 'role_permissions';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'key',
        'role_id',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];
}
