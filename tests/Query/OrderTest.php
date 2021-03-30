<?php
declare(strict_types=1);

namespace OniBus\Test\Query;

use OniBus\Query\Order;
use OniBus\Test\TestCase;

class OrderTest extends TestCase
{
    public function testShouldAddOrderBy()
    {
        $filter = new Order(['field1' => 'asc']);
        $filter->add('field2', 'desc');
        $this->assertTrue($filter->has('field2'));
        $this->assertEquals('desc', $filter->field2);
        $this->assertEquals('desc', $filter->field2());
        $this->assertEquals('desc', $filter->get('field2'));
    }
}
