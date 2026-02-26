<?php

namespace Src\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';
}
