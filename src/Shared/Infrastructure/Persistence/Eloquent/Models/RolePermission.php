<?php

namespace Src\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';
}
