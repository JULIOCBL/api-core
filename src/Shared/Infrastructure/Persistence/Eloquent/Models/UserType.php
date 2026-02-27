<?php

namespace Src\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo Eloquent para catálogo de tipos de usuario.
 */
class UserType extends Model
{
    public const TABLE = 'user_types';

    public const ROOT_1 = 1;
    public const SUPER_USUARIO_2 = 2;
    public const ADMINISTRATOR_3 = 3;
    public const USER_4 = 4;

    protected $table = self::TABLE;

    protected $fillable = [
        'name',
        'constant',
    ];
}
