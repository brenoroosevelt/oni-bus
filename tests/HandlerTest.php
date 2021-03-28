<?php
declare(strict_types=1);

namespace XBus\Test;

use Habemus\Container;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Psr16Cache;
use XBus\Attributes\Authorizer;
use XBus\Attributes\CommandHandler;
use XBus\BusChain;
use XBus\Buses\DispatchToHandler;
use XBus\Handler\ClassMethodAttributesMapper;
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

        $cache1 = new Psr16Cache(new ArrayAdapter());
        $resolver = new ClassMethodResolver(
            new Container(),
            new ClassMethodAttributesMapper($handlers, CommandHandler::class, $cache1, 'aadd')
        );

        $commandHandler = new DispatchToHandler($resolver);

        $bus = new BusChain($commandHandler);
        $result = $bus->dispatch(new GenericMessage());
        $this->assertEquals(100, $result);
    }
}
