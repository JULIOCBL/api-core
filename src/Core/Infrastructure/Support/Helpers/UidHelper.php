<?php


use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

/**
 * Genera un UUID4 único para la llave primaria del modelo indicado.
 *
 * @param Model $model
 * @return string
 */
function uid(Model $model): string
{

    do {
        // Genera un nuevo UUID
        $uuid = Uuid::uuid4()->toString();

        // Verifica si el UUID ya existe en el modelo
    } while ($model->where($model->getKeyName(), $uuid)->exists());

    // Retorna el UUID único
    return $uuid;
}
