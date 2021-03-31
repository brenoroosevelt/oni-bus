<?php
declare(strict_types=1);

namespace OniBus\Test;

use Habemus\Container;
use OniBus\Handler\Builder\Resolver;
use OniBus\Test\Fixture\GenericMessage;
use OniBus\Test\Fixture\HandlerUsingAttributes;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Adapter\Psr16Adapter;
use Symfony\Component\Cache\Psr16Cache;

class Handler2Test extends TestCase
{
    public function testAttr()
    {
        $handlers = [
            HandlerUsingAttributes::class
        ];

        $resolver = Resolver::new(new Container())
            //->handlerFromDir()
            ->withHandlers($handlers)
            ->withCache(new Psr16Cache(new ArrayAdapter()), 'handlers')
            ->throwingExceptions()
            ->mapByMethod();

        $handler = $resolver->resolve(new GenericMessage());
        $this->assertEquals(200, $handler(new GenericMessage()));
    }
}
