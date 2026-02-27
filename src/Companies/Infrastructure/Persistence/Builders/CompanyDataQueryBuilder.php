<?php

namespace Src\Companies\Infrastructure\Persistence\Builders;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Src\Shared\Infrastructure\Persistence\Eloquent\Models\Company as CompanyModel;
use Src\Shared\Infrastructure\Persistence\Eloquent\Traits\AppliesQueryFilters;

/**
 * Builder de consulta para compañías.
 * Centraliza la base de query y variantes de salida (paginado y selector).
 */
class CompanyDataQueryBuilder
{
    use AppliesQueryFilters;

    /**
     * Obtiene compañías paginadas aplicando filtros y orden.
     *
     * @param array<string, mixed> $filters
     * @param int $page
     * @param int $per_page
     * @return LengthAwarePaginator
     */
    public function paginate(array $filters, int $page, int $per_page): LengthAwarePaginator
    {
        $query = $this->buildBaseQuery();
        $this->applyFilters($query, $filters);
        $this->applySorting($query, $filters);

        if ($per_page === -1) {
            $total = (int) (clone $query)->count();
            $per_page = $total > 0 ? $total : 1;
        }

        return $query->paginate($per_page, ['*'], 'page', $page);
    }

    /**
     * Obtiene listado para selector (sin paginación).
     *
     * @return Collection<int, CompanyModel>
     */
    public function selector(): Collection
    {
        return $this->buildBaseQuery()
            ->reorder()
            ->orderByRaw('LOWER(commercial_name) ASC')
            ->get(['id', 'commercial_name']);
    }

    /**
     * Construye query base para compañías.
     *
     * @return Builder
     */
    private function buildBaseQuery(): Builder
    {
        return CompanyModel::query()->orderBy('id');
    }

    /**
     * Aplica filtros disponibles del endpoint de compañías.
     *
     * @param Builder $query
     * @param array<string, mixed> $filters
     */
    private function applyFilters(Builder $query, array $filters): void
    {
        $exact_filters = [
            'id' => 'id',
            'status' => 'status',
            'rfc' => 'rfc',
            'email' => 'email',
        ];
        $like_filters = [
            'name' => 'name',
            'commercial_name' => 'commercial_name',
            'bussiness_name' => 'bussiness_name',
        ];
        $range_filters = [
            'created_from' => ['column' => 'created_at', 'operator' => '>='],
            'created_to' => ['column' => 'created_at', 'operator' => '<='],
        ];
        $search_columns = [
            'name',
            'commercial_name',
            'bussiness_name',
            'rfc',
            'email',
        ];

        $this->applyExactFilters($query, $filters, $exact_filters);
        $this->applyLikeFilters($query, $filters, $like_filters);
        $this->applyRangeFilters($query, $filters, $range_filters);
        $this->applySearchFilter($query, $filters, 'search', $search_columns);
    }

    /**
     * Aplica ordenamiento permitido del endpoint de compañías.
     *
     * @param Builder $query
     * @param array<string, mixed> $filters
     */
    private function applySorting(Builder $query, array $filters): void
    {
        $sort_columns = [
            'id' => 'id',
            'name' => 'name',
            'commercial_name' => 'commercial_name',
            'company_name' => 'commercial_name',
            'bussiness_name' => 'bussiness_name',
            'rfc' => 'rfc',
            'email' => 'email',
            'status' => 'status',
            'created_at' => 'created_at',
        ];

        $this->applySortFilter($query, $filters, $sort_columns, 'id', 'asc');
    }
}
