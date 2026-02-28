<?php

namespace Src\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo Eloquent para catálogo de tipos de usuario.
 */
class UserType extends Model
{
    public const TABLE = 'user_types';

    public const ROOT = 1;
    public const SUPER_USUARIO = 2;
    public const ADMINISTRATOR = 3;
    public const USER = 4;

    protected $table = self::TABLE;

    protected $fillable = [
        'name',
        'constant',
        'required_mail',
    ];

    protected $casts = [
        'required_mail' => 'boolean',
    ];
}
