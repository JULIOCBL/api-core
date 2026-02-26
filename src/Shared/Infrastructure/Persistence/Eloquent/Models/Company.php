<?php

namespace Src\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Modelo Eloquent compartido de compañías para módulos del proyecto.
 */
class Company extends Model
{
    use SoftDeletes;

    protected $table = 'companies';

    protected $fillable = [
        'name',
        'commercial_name',
        'bussiness_name',
        'rfc',
        'contact_phone',
        'email',
        'primary_color',
        'secondary_color',
        'tertiary_color',
        'image_logo',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];
}
