<?php
declare(strict_types=1);

namespace OniBus\Test\Query;

use InvalidArgumentException;
use OniBus\Chain;
use OniBus\ChainTrait;
use OniBus\Message;
use OniBus\Query\Query;
use OniBus\Query\QueryBus;
use OniBus\Test\Fixture\GenericBusChain;
use OniBus\Test\Fixture\GenericMessage;
use OniBus\Test\TestCase;
use stdClass;

class QueryBusTest extends TestCase
{
    public function testShouldQueryBusDispatchAQueryMessage()
    {
        $task = new stdClass();

        $queryMessage = new class implements Query {
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

        $queryBus = new QueryBus($bus);
        $queryBus->dispatch($queryMessage);
        $this->assertEquals("executed", $task->value);
    }

    public function testShouldNotQueryBusDispatchInvalidMessage()
    {
        $queryBus = new QueryBus(new GenericBusChain());
        $this->expectException(InvalidArgumentException::class);
        $queryBus->dispatch(new GenericMessage());
    }
}
