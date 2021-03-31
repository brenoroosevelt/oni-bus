<?php
declare(strict_types=1);

namespace OniBus\Test\Buses;

use Closure;
use OniBus\BusChain;
use OniBus\Buses\DispatchToHandler;
use OniBus\Buses\EventsDispatcher;
use OniBus\Chain;
use OniBus\ChainTrait;
use OniBus\Event\Event;
use OniBus\Event\EventProvider;
use OniBus\Handler\HandlerResolver;
use OniBus\Message;
use OniBus\Test\Fixture\GenericEvent;
use OniBus\Test\Fixture\GenericMessage;
use OniBus\Test\TestCase;

class EventsDispatcherTest extends TestCase
{
    public function testShouldDispatchEvents()
    {
        $eventProvider = new EventProvider();

        $resolver = new class implements HandlerResolver {
            public function resolve(Message $message): Closure
            {
                return function (GenericEvent $event) {
                    $event->value = 500;
                };
            }
        };

        $nextBus = new class ($eventProvider) implements Chain {
            use ChainTrait;

            protected $eventProvider;

            public function __construct(EventProvider $eventProvider)
            {
                $this->eventProvider = $eventProvider;
            }

            public function dispatch(Message $message)
            {
                $event = new GenericEvent();
                $this->eventProvider->recordEvent($event);
                return $event; // not usual, only for tests
            }
        };

        $eventBus = new BusChain(new DispatchToHandler($resolver));

        $eventsDispatcher = new EventsDispatcher($eventProvider, $eventBus);
        $eventsDispatcher->setNext($nextBus);
        $result = $eventsDispatcher->dispatch(new GenericMessage());
        $this->assertEquals(500, $result->value);
    }
}
