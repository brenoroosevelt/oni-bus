<?php
declare(strict_types=1);

namespace XBus\Query;

class QueryObject implements Query
{
    use PaginationTrait, OrderTrait, FilterTrait;
}
