<?php
declare(strict_types=1);

namespace OniBus\Test\Event;

use InvalidArgumentException;
use OniBus\Chain;
use OniBus\ChainTrait;
use OniBus\Event\Event;
use OniBus\Event\EventBus;
use OniBus\Message;
use OniBus\Test\Fixture\GenericBusChain;
use OniBus\Test\Fixture\GenericMessage;
use OniBus\Test\TestCase;
use stdClass;

class EventBusTest extends TestCase
{
    public function testShouldEventBusDispatchAEvent()
    {
        $task = new stdClass();

        $event = new class implements Event {
        };

        $bus = new class ($task) implements Chain {

            use ChainTrait;

            protected $task;

            public function __construct(stdClass $task)
            {
                $this->task = $task;
            }

            public function dispatch(Message $message)
            {
                $this->task->value = "executed";
            }
        };

        $eventBus = new EventBus($bus);
        $eventBus->dispatch($event);
        $this->assertEquals("executed", $task->value);
    }

    public function testShouldNotEventBusDispatchInvalidMessage()
    {
        $eventBus = new EventBus(new GenericBusChain());
        $this->expectException(InvalidArgumentException::class);
        $eventBus->dispatch(new GenericMessage());
    }
}
