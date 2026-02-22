<?php

namespace Src\Companies\Infrastructure\Persistence\Builders;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Src\Shared\Infrastructure\Persistence\Eloquent\Models\Company as CompanyModel;
use Src\Shared\Infrastructure\Persistence\Eloquent\Traits\AppliesQueryFilters;

class CompanyDataQueryBuilder
{
    use AppliesQueryFilters;

    public function paginate(array $filters, int $page, int $per_page): LengthAwarePaginator
    {
        $query = $this->buildBaseQuery();
        $this->applyFilters($query, $filters);

        if ($per_page === -1) {
            $total = (int) (clone $query)->count();
            $per_page = $total > 0 ? $total : 1;
        }

        return $query->paginate($per_page, ['*'], 'page', $page);
    }

    public function selector(): Collection
    {
        return $this->buildBaseQuery()
            ->reorder('commercial_name')
            ->get(['id', 'commercial_name']);
    }

    private function buildBaseQuery(): Builder
    {
        return CompanyModel::query()->orderBy('id');
    }

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
}
