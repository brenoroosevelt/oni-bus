<?php
declare(strict_types=1);

namespace OniBus\Test\Command;

use InvalidArgumentException;
use OniBus\Chain;
use OniBus\ChainTrait;
use OniBus\Command\Command;
use OniBus\Command\CommandBus;
use OniBus\Message;
use OniBus\Test\Fixture\GenericBusChain;
use OniBus\Test\Fixture\GenericMessage;
use OniBus\Test\TestCase;
use stdClass;

class CommandBusTest extends TestCase
{
    public function testShouldCommandBusDispatchACommand()
    {
        $task = new stdClass();

        $command = new class implements Command {
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

        $commandBus = new CommandBus($bus);
        $commandBus->dispatch($command);
        $this->assertEquals("executed", $task->value);
    }

    public function testShouldNotCommandBusDispatchInvalidMessage()
    {
        $commandBus = new CommandBus(new GenericBusChain());
        $this->expectException(InvalidArgumentException::class);
        $commandBus->dispatch(new GenericMessage());
    }
}
