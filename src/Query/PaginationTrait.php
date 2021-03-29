<?php
declare(strict_types=1);

namespace OniBus\Query;

trait PaginationTrait
{
    /**
     * @var Pagination
     */
    protected $pagination;

    protected function setPagination(Pagination $pagination)
    {
        $this->pagination = $pagination;
    }

    public function pagination(): ?Pagination
    {
        return $this->pagination;
    }

    public function hasPagination(): bool
    {
        return $this->pagination instanceof Pagination;
    }
}
