<?php

namespace Src\Shared\Infrastructure\Persistence\Eloquent\Traits;

use Illuminate\Database\Eloquent\Builder;

trait AppliesQueryFilters
{
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

    protected function applySortFilters(
        Builder $query,
        array $filters,
        array $sort_columns,
        string $default_order_by = 'id',
        string $default_order_direction = 'asc'
    ): void {
        $sort_instructions = $this->resolveSortInstructions(
            filters: $filters,
            sort_columns: $sort_columns,
            default_order_by: $default_order_by,
            default_order_direction: $default_order_direction
        );

        $query->reorder();

        foreach ($sort_instructions as $sort_instruction) {
            $order_by = $sort_instruction['order_by'];
            $order_direction = $sort_instruction['order_direction'];
            $query->orderBy($sort_columns[$order_by], $order_direction);
        }
    }

    protected function resolveSortInstructions(
        array $filters,
        array $sort_columns,
        string $default_order_by = 'id',
        string $default_order_direction = 'asc'
    ): array {
        $sort_instructions = [];

        if (isset($filters['orders']) && is_array($filters['orders'])) {
            foreach ($filters['orders'] as $order_item) {
                if (!is_array($order_item) || !isset($order_item['column'])) {
                    continue;
                }

                $order_by = (string) $order_item['column'];
                if (!array_key_exists($order_by, $sort_columns)) {
                    continue;
                }

                $order_direction = isset($order_item['direction']) ? strtolower((string) $order_item['direction']) : 'asc';
                $order_direction = $order_direction === 'desc' ? 'desc' : 'asc';

                $sort_instructions[] = [
                    'order_by' => $order_by,
                    'order_direction' => $order_direction,
                ];
            }
        }

        if (empty($sort_instructions)) {
            $sort_instructions[] = [
                'order_by' => $default_order_by,
                'order_direction' => strtolower($default_order_direction) === 'desc' ? 'desc' : 'asc',
            ];
        }

        return $sort_instructions;
    }
}
