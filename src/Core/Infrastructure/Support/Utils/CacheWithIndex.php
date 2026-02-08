<?php

namespace Src\Core\Infrastructure\Support\Utils;

use Closure;
use Illuminate\Support\Facades\Cache;

class CacheWithIndex
{
    protected string $index_key;

    public function __construct(string $index_key)
    {
        $this->index_key = $index_key;
    }

    /**
     * Obtiene o almacena en caché un valor usando un prefijo y condiciones como clave compuesta.
     *
     * @param string $prefix Prefijo lógico de la clave (ej: 'permissions.all.active')
     * @param array $conditions Condiciones que afectan el contenido de la caché
     * @param Closure $callback Lógica a ejecutar si no existe la caché
     */
    public function rememberForever(string $prefix, array $conditions, Closure $callback): mixed
    {
        $cache_key = $this->buildKey($prefix, $conditions);
        $this->addToIndex($cache_key);
        return Cache::rememberForever($cache_key, $callback);
    }

    /**
     * Genera una clave única combinando un prefijo y condiciones serializadas.
     */
    protected function buildKey(string $prefix, array $conditions): string
    {
        ksort($conditions); // asegura consistencia
        $conditions_string = implode(',', array_map(
            fn($key, $value) => strval($key) . '=' . (is_array($value) ? json_encode($value) : strval($value)),
            array_keys($conditions),
            $conditions
        ));

        return $this->index_key . "." . $prefix . ":" . $conditions_string;
    }

    /**
     * Agrega la clave al índice si no está ya registrada.
     */
    protected function addToIndex(string $cache_key): void
    {
        $keys = Cache::get($this->index_key, []);
        if (!in_array($cache_key, $keys)) {
            $keys[] = $cache_key;
            Cache::forever($this->index_key, $keys);
        }
    }

    /**
     * Devuelve todas las claves registradas en el índice.
     */
    public function getAllKeys(): array
    {
        return Cache::get($this->index_key, []);
    }

    /**
     * Limpia una clave específica o un grupo por prefijo del índice.
     *
     * @param string|null $match Clave exacta o patrón con asterisco (ej: 'permissions.all.active*').
     */
    public function clear(?string $match = null): void
    {
        $all_keys = $this->getAllKeys();
        $remaining_keys = [];
        $deleted = 0;

        foreach ($all_keys as $key) {
            if ($this->shouldDeleteKey($key, $match)) {
                Cache::forget($key);
                $deleted++;
            } else {
                $remaining_keys[] = $key;
            }
        }

        if ($match === null || $deleted === count($all_keys)) {
            Cache::forget($this->index_key);
        } else {
            Cache::forever($this->index_key, $remaining_keys);
        }
    }

    /**
     * Determina si una clave debe ser eliminada según un patrón opcional.
     */
    protected function shouldDeleteKey(string $key, ?string $match): bool
    {
        if ($match === null) return true;

        $is_prefix = str_ends_with($match, '*');
        $term = $is_prefix ? rtrim($match, '*') : $match;

        return $is_prefix
            ? str_starts_with($key, $term)
            : $key === $term;
    }
}
