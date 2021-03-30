<?php
declare(strict_types=1);

namespace OniBus\Test\Query;

use InvalidArgumentException;
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

    public function testShouldOrderThrowsExceptionWhenInvalidDirectionWhenCreate()
    {
        $this->expectException(InvalidArgumentException::class);
        new Order(['field' => 'invalid']);
    }

    public function testShouldOrderThrowsExceptionWhenInvalidDirectionWhenAdd()
    {
        $filter = new Order();
        $this->expectException(InvalidArgumentException::class);
        $filter->add('field', 'invalid');
    }

    public function testShouldOrderCreateUsingStaticMethod()
    {
        $filter = Order::by('field');
        $this->assertEquals(Order::ASC, $filter->get('field'));
    }
}
