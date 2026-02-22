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
}
