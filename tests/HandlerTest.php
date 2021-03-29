<?php
declare(strict_types=1);

namespace OniBus\Test;

use Habemus\Container;
use OniBus\Query\Pagination;
use OniBus\Test\Fixture\UserQuery;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Psr16Cache;
use OniBus\Attributes\CommandHandler;
use OniBus\BusChain;
use OniBus\Buses\DispatchToHandler;
use OniBus\Handler\ClassMethodAttributesMapper;
use OniBus\Handler\ClassMethodResolver;
use OniBus\Test\Fixture\GenericMessage;
use OniBus\Test\Fixture\HandlerUsingAttributes;

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
