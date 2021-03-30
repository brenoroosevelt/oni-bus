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
        $order = new Order(['field1' => Order::ASC]);
        $order->add('field2', Order::DESC);
        $this->assertTrue($order->has('field2'));
        $this->assertEquals(Order::DESC, $order->field2);
        $this->assertEquals(Order::DESC, $order->field2());
        $this->assertEquals(Order::DESC, $order->get('field2'));
    }

    public function testShouldOrderThrowsExceptionWhenInvalidDirectionWhenCreate()
    {
        $this->expectException(InvalidArgumentException::class);
        new Order(['field' => 'invalid']);
    }

    public function testShouldOrderThrowsExceptionWhenInvalidDirectionWhenAdd()
    {
        $order = new Order();
        $this->expectException(InvalidArgumentException::class);
        $order->add('field', 'invalid');
    }

    public function testShouldOrderCreateUsingStaticMethod()
    {
        $order = Order::by('field');
        $this->assertEquals(Order::ASC, $order->get('field'));
    }
}
