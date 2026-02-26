<?php

namespace Src\Shared\Infrastructure\Persistence\Eloquent\Traits;

use Illuminate\Database\Eloquent\Builder;

/**
 * Trait reutilizable para aplicar filtros estándar de consultas:
 * exactos, like, rango, búsqueda global y ordenamiento.
 */
trait AppliesQueryFilters
{
    /**
     * Aplica filtros exactos con mapeo request => columna.
     *
     * @param Builder $query
     * @param array<string, mixed> $filters
     * @param array<string, string> $exact_filters
     */
    protected function applyExactFilters(Builder $query, array $filters, array $exact_filters): void
    {
        foreach ($exact_filters as $request_key => $column_name) {
            if (!array_key_exists($request_key, $filters)) {
                continue;
            }

            if ($filters[$request_key] === null || $filters[$request_key] === '') {
                continue;
            }

            $query->where($column_name, $filters[$request_key]);
        }
    }

    /**
     * Aplica filtros tipo LIKE con mapeo request => columna.
     *
     * @param Builder $query
     * @param array<string, mixed> $filters
     * @param array<string, string> $like_filters
     */
    protected function applyLikeFilters(Builder $query, array $filters, array $like_filters): void
    {
        foreach ($like_filters as $request_key => $column_name) {
            if (!array_key_exists($request_key, $filters)) {
                continue;
            }

            $value = trim((string) $filters[$request_key]);
            if ($value === '') {
                continue;
            }

            $query->where($column_name, 'like', '%' . $value . '%');
        }
    }

    /**
     * Aplica filtros de rango usando configuración por campo.
     *
     * @param Builder $query
     * @param array<string, mixed> $filters
     * @param array<string, array{column: string, operator: string}> $range_filters
     */
    protected function applyRangeFilters(Builder $query, array $filters, array $range_filters): void
    {
        foreach ($range_filters as $request_key => $range_config) {
            if (!array_key_exists($request_key, $filters)) {
                continue;
            }

            if (!is_array($range_config)) {
                continue;
            }

            if (!isset($range_config['column']) || !isset($range_config['operator'])) {
                continue;
            }

            $value = $filters[$request_key];
            if ($value === null || $value === '') {
                continue;
            }

            $query->where($range_config['column'], $range_config['operator'], $value);
        }
    }

    /**
     * Aplica búsqueda global en múltiples columnas definidas.
     *
     * @param Builder $query
     * @param array<string, mixed> $filters
     * @param string $search_key
     * @param array<int, string> $columns
     */
    protected function applySearchFilter(Builder $query, array $filters, string $search_key, array $columns): void
    {
        if (!array_key_exists($search_key, $filters)) {
            return;
        }

        $search_value = trim((string) $filters[$search_key]);
        if ($search_value === '' || empty($columns)) {
            return;
        }

        $query->where(function (Builder $sub_query) use ($columns, $search_value): void {
            foreach ($columns as $column_name) {
                $sub_query->orWhere($column_name, 'like', '%' . $search_value . '%');
            }
        });
    }

    /**
     * Aplica un ordenamiento único con columna permitida y dirección validada.
     *
     * @param Builder $query
     * @param array<string, mixed> $filters
     * @param array<string, string> $sort_columns
     * @param string $default_order_by
     * @param string $default_order_direction
     * @param string $order_by_key
     * @param string $order_direction_key
     */
    protected function applySortFilter(
        Builder $query,
        array $filters,
        array $sort_columns,
        string $default_order_by = 'id',
        string $default_order_direction = 'asc',
        string $order_by_key = 'order_by',
        string $order_direction_key = 'order_direction'
    ): void {
        $order_by = isset($filters[$order_by_key]) ? (string) $filters[$order_by_key] : $default_order_by;
        $order_direction = isset($filters[$order_direction_key]) ? (string) $filters[$order_direction_key] : $default_order_direction;
        $order_direction = strtolower($order_direction) === 'desc' ? 'desc' : 'asc';

        if (!array_key_exists($order_by, $sort_columns)) {
            $order_by = $default_order_by;
        }

        $query->reorder($sort_columns[$order_by], $order_direction);
    }
}
