<?php
declare(strict_types=1);

namespace XBus\Test;

use Habemus\Container;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Psr16Cache;
use XBus\BusChain;
use XBus\Buses\DispatchToHandler;
use XBus\Handler\AttributesMapper;
use XBus\Handler\ClassMethodResolver;
use XBus\Test\Fixture\GenericMessage;
use XBus\Test\Fixture\HandlerUsingAttributes;

class HandlerTest extends TestCase
{
    public function testAttr()
    {
        if (PHP_VERSION_ID < 80000) {
            $this->markTestSkipped();
            return;
        }

        $handlers = [
            HandlerUsingAttributes::class
        ];

        $cache = new Psr16Cache(new ArrayAdapter());
        $resolver = new ClassMethodResolver(
            new Container(),
            new AttributesMapper($handlers, $cache, 'my_handlers')
        );

        $bus = new BusChain(new DispatchToHandler($resolver));
        $result = $bus->dispatch(new GenericMessage());
        $this->assertEquals(100, $result);
    }
}
