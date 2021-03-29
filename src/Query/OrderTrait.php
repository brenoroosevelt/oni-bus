<?php
declare(strict_types=1);

namespace OniBus\Query;

trait OrderTrait
{
    /**
     * @var Order
     */
    protected $orderBy;

    protected function setOrder(Order $orderBy)
    {
        $this->orderBy = $orderBy;
    }

    public function orders(): Order
    {
        return $this->orderBy ?? new Order();
    }

    public function hasOrders(): bool
    {
        return $this->orderBy instanceof Order && !$this->orderBy->isEmpty();
    }
}
