<?php
declare(strict_types=1);

namespace OniBus\Test\Query;

use OniBus\Query\Order;
use OniBus\Query\OrderTrait;
use OniBus\Test\TestCase;

class OrderTraitTest extends TestCase
{
    protected function newOrderTrait()
    {
        return new class {
            use OrderTrait;
        };
    }

    public function testShouldOrderTraitInitializeWithEmptyOrders()
    {
        $trait = $this->newOrderTrait();
        $this->assertFalse($trait->hasOrders());
    }

    public function testShouldOrderTraitAlwaysReturnInstanceOfOrder()
    {
        $trait = $this->newOrderTrait();
        $this->assertInstanceOf(Order::class, $trait->orders());
    }

    public function testShouldOrderTraitEmptyWhenOrdersAreEmpty()
    {
        $trait = $this->newOrderTrait();
        $this->invokeMethod($trait, 'setOrder', [new Order()]);
        $this->assertFalse($trait->hasOrders());
    }

    public function testShouldOrderTraitSetOrder()
    {
        $trait = $this->newOrderTrait();
        $this->invokeMethod($trait, 'setOrder', [new Order(['field' => Order::ASC])]);
        $this->assertEquals(Order::ASC, $trait->orders()->get('field'));
    }
}
