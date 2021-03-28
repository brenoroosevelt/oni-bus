<?php
declare(strict_types=1);

namespace XBus\Query;

trait FilterTrait
{
    /**
     * @var Filter
     */
    protected $filters;

    protected function setFilters(Filter $filter)
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
