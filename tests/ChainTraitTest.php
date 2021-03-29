<?php
declare(strict_types=1);

namespace OniBus\Test;

use http\Exception\RuntimeException;
use OniBus\Bus;
use OniBus\ChainTrait;
use OniBus\Message;
use OniBus\Test\Fixture\DummyMessage;
use OniBus\Test\Fixture\GenericBusChain;
use OniBus\Test\Fixture\GenericChain;
use OniBus\Test\Fixture\GenericMessage;
use PHPUnit\Framework\TestCase;
use stdClass;

class ChainTraitTest extends TestCase
{
    protected function newChainTrait()
    {
        return new class {

            use ChainTrait;

            public function hasNextBus()
            {
                return $this->hasNext();
            }

            public function callNext(Message $message)
            {
                return $this->next($message);
            }
        };
    }

    public function testChainTraitSetDefaultToNull()
    {
        $trait = $this->newChainTrait();
        $this->expectException(\RuntimeException::class);
        $trait->callNext(new DummyMessage('msg'));
    }

    public function testChainTraitSetNextBus()
    {
        $trait = $this->newChainTrait();
        $trait->setNext(new GenericBusChain());
        $this->assertTrue($trait->hasNextBus());
    }

    public function testChainTraitExecuteNextBus()
    {
        $task = new stdClass();
        $trait = $this->newChainTrait();

        $bus = new class ($task) implements Bus {
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

        $trait->setNext($bus);
        $trait->callNext(new DummyMessage('msg'));
        $this->assertEquals("executed", $task->value);
    }
}
