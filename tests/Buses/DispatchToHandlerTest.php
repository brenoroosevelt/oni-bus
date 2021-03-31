<?php
declare(strict_types=1);

namespace OniBus\Test\Buses;

use Closure;
use OniBus\Buses\DispatchToHandler;
use OniBus\Handler\HandlerResolver;
use OniBus\Message;
use OniBus\Test\Fixture\GenericMessage;
use OniBus\Test\TestCase;

class DispatchToHandlerTest extends TestCase
{
    public function testShouldDispatchToHandler()
    {
        $resolver = new class implements HandlerResolver {
            public function resolve(Message $message): Closure
            {
                return function (Message $message) {
                    return 600;
                };
            }
        };

        $dispatchToHandler = new DispatchToHandler($resolver);
        $result = $dispatchToHandler->dispatch(new GenericMessage());
        $this->assertEquals(600, $result);
    }
}
