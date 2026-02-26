<?php

namespace Src\Companies\Domain\ValueObjects;

use Src\Companies\Domain\Entities\Company;

/**
 * Value object para respuesta paginada de compañías.
 */
class PaginatedCompanies
{
    /**
     * @param Company[] $companies
     */
    public function __construct(
        private array $companies,
        private int $total,
        private int $per_page,
        private int $current_page,
        private int $last_page,
        private ?int $from,
        private ?int $to,
        private string $path,
        private ?string $first_page_url,
        private ?string $last_page_url,
        private ?string $next_page_url,
        private ?string $prev_page_url
    ) {
    }

    /**
     * @return Company[]
     */
    public function getCompanies(): array
    {
        return $this->companies;
    }

    public function getTotal(): int
    {
        return $this->total;
    }

    public function getPerPage(): int
    {
        return $this->per_page;
    }

    public function getCurrentPage(): int
    {
        return $this->current_page;
    }

    public function getLastPage(): int
    {
        return $this->last_page;
    }

    public function getFrom(): ?int
    {
        return $this->from;
    }

    public function getTo(): ?int
    {
        return $this->to;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getFirstPageUrl(): ?string
    {
        return $this->first_page_url;
    }

    public function getLastPageUrl(): ?string
    {
        return $this->last_page_url;
    }

    public function getNextPageUrl(): ?string
    {
        return $this->next_page_url;
    }

    public function getPrevPageUrl(): ?string
    {
        return $this->prev_page_url;
    }
}
