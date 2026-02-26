<?php

namespace Src\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Modelo Eloquent para tokens de acceso personales.
 */
class PersonalAccessToken extends Model
{
    use SoftDeletes;

    protected $table = 'personal_access_tokens';

    protected $fillable = [
        'ip_address',
        'latitude',
        'longitude',
        'tokenable_type',
        'user_id',
        'platform_type',
        'name_platform_type',
        'device_type',
        'token',
        'abilities',
        'last_used_at',
        'expires_at',
        'created_at_utc',
        'updated_at_utc',
    ];

    protected $casts = [
        'last_used_at' => 'datetime',
        'expires_at' => 'datetime',
        'created_at_utc' => 'datetime',
        'updated_at_utc' => 'datetime',
    ];

    /**
     * @return HasMany
     */
    public function refreshTokens(): HasMany
    {
        return $this->hasMany(RefreshAccessToken::class, 'personal_access_token_id');
    }
}
