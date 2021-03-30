<?php
declare(strict_types=1);

namespace OniBus\Test;

use Habemus\Container;
use OniBus\Handler\Builder\Resolver;
use OniBus\Test\Fixture\UserCreatedEvent;
use OniBus\Test\Fixture\GenericMessage;
use OniBus\Test\Fixture\HandlerUsingAttributes;

class Handler2Test extends TestCase
{
    public function testAttr()
    {
        $handlers = [
            HandlerUsingAttributes::class
        ];

        $resolver = Resolver::new(new Container())->inflectByMethod($handlers);
        $handler = $resolver->resolve(new GenericMessage());
        $this->assertEquals(200, $handler(new GenericMessage()));
    }
}
