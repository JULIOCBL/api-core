<?php

namespace Src\Shared\Infrastructure\Persistence\Eloquent\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Src\Core\Infrastructure\Traits\AttemptsEncryptedPassword;

/**
 * Modelo Eloquent compartido de usuarios.
 */
class User extends Authenticatable
{
    use AttemptsEncryptedPassword;

    public $incrementing = false;
    protected $keyType = 'string';
}
