<?php
declare(strict_types=1);

namespace OniBus\Query;

class QueryObject implements Query
{
    use PaginationTrait,
        OrderTrait,
        FilterTrait;
}
