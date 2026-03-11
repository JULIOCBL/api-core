<?php

namespace Src\Shared\Infrastructure\Persistence\Builders;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * Clase base para builders reutilizables del proyecto.
 */
abstract class BaseQueryBuilder
{
    /**
     * Pagina la consulta con soporte para traer todos los registros usando -1.
     *
     * @param Builder $query
     * @param int $page
     * @param int $n_rows
     * @return LengthAwarePaginator
     */
    protected function toPaginate(Builder $query, int $page = 1, int $n_rows = 10): LengthAwarePaginator
    {
        $num_rows = $n_rows === -1 ? (int) $query->count() : $n_rows;
        $num_rows = $num_rows === 0 ? 10 : $num_rows;

        $paginator = $query->paginate($num_rows, ['*'], 'page', $page);
        $relative_path = '/' . ltrim((string) request()->path(), '/');

        $paginator->setPath($relative_path);
        $paginator->appends(request()->except('page'));

        return $paginator;
    }

    /**
     * Obtiene un listado con límite opcional.
     *
     * @param Builder $query
     * @param int $limit
     * @return Collection<int, mixed>
     */
    protected function toList(Builder $query, int $limit = 0): Collection
    {
        $query->when($limit > 0, function (Builder $subquery) use ($limit): void {
            $subquery->limit($limit);
        });

        return $query->get();
    }
}
