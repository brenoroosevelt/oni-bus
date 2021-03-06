<?php
declare(strict_types=1);

namespace OniBus\Query;

trait FilterTrait
{
    /**
     * @var Filter
     */
    protected $filters;

    protected function setFilter(Filter $filter)
    {
        $this->filters = $filter;
    }

    public function filters(): Filter
    {
        return $this->filters ?? new Filter();
    }

    public function hasFilters(): bool
    {
        return $this->filters instanceof Filter && !$this->filters->isEmpty();
    }
}
