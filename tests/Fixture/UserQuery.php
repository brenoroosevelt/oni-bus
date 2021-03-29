<?php
declare(strict_types=1);

namespace OniBus\Test\Fixture;

use OniBus\Payload;
use OniBus\Query\Pagination;
use OniBus\Query\PaginationTrait;
use OniBus\Query\Query;
use OniBus\Query\QueryObject;

/**
 * @method int userId()
 */
class UserQuery extends QueryObject
{
    public function __construct(int $userId, Pagination $pagination)
    {
        $this->setPagination($pagination);
    }
}
