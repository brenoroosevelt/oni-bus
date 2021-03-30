<?php
declare(strict_types=1);

namespace OniBus\Test\Query;

use OniBus\Query\Query;
use OniBus\Query\QueryObject;
use OniBus\Test\TestCase;

class QueryObjectTest extends TestCase
{
    public function testShouldQueryObjectImplementQueryInterface()
    {
        $queryObject = new QueryObject();
        $this->assertInstanceOf(Query::class, $queryObject);
    }
}
