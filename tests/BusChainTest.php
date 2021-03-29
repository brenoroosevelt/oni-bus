<?php
declare(strict_types=1);

namespace OniBus\Test;

use InvalidArgumentException;
use OniBus\BusChain;
use OniBus\Chain;
use OniBus\ChainTrait;
use OniBus\Message;
use OniBus\NamedMessage;
use OniBus\Test\Fixture\GenericChain;
use OniBus\Test\Fixture\Tasks;
use PHPUnit\Framework\TestCase;

class BusChainTest extends TestCase
{
    protected function newDummyChain(): Chain
    {
        return new class implements Chain {

            use ChainTrait;

            public function dispatch(Message $message)
            {
            }
        };
    }

    protected function newNamedMessage($name): NamedMessage
    {
        return new class($name) implements NamedMessage {

            protected $name;

            public function __construct($name)
            {
                $this->name = $name;
            }

            public function getMessageName(): string
            {
                return $this->name;
            }
        };
    }

    public function testShouldBusChainThrowsExceptionIfEmpty()
    {
        $this->expectException(InvalidArgumentException::class);
        new BusChain();
    }

    public function testShouldBusChainAcceptOnlyOneChain()
    {
        $tasks = new Tasks();
        $chain =
            $this->getMockBuilder(GenericChain::class)
                ->setMethods(['before'])
                ->getMock();

        $chain
            ->expects($this->any())
            ->method('before')
            ->willReturnCallback(
                static function () use ($tasks) {
                    $tasks->set('task1', 100);
                }
            );

        $busChain = new BusChain($chain);
        $busChain->dispatch($this->newNamedMessage('message'));

        $this->assertEquals(100, $tasks->get('task1'));
    }

    public function testShouldBusChainExecuteAllBuses()
    {
        $tasks = [];

        $chain1 =
            $this->getMockBuilder(GenericChain::class)
                ->setMethods(['before'])
                ->getMock();
        $chain1
            ->expects($this->any())
            ->method('before')
            ->willReturnCallback(
                static function () use (&$tasks) {
                    $tasks[] = 100;
                }
            );

        $chain2 =
            $this->getMockBuilder(GenericChain::class)
                ->setMethods(['before'])
                ->getMock();
        $chain2
            ->expects($this->any())
            ->method('before')
            ->willReturnCallback(
                static function () use (&$tasks) {
                    $tasks[] = 200;
                }
            );

        $chain3 =
            $this->getMockBuilder(GenericChain::class)
                ->setMethods(['before'])
                ->getMock();
        $chain3
            ->expects($this->any())
            ->method('before')
            ->willReturnCallback(
                static function () use (&$tasks) {
                    $tasks[] = 300;
                }
            );


        $busChain = new BusChain($chain1, $chain2, $chain3);
        $busChain->dispatch($this->newNamedMessage('message'));

        $this->assertEquals(100, $tasks[0]);
        $this->assertEquals(200, $tasks[1]);
        $this->assertEquals(300, $tasks[2]);
    }
}
