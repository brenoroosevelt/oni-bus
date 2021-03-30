<?php
declare(strict_types=1);

namespace OniBus\Test;

use InvalidArgumentException;
use OniBus\BusChain;
use OniBus\Chain;
use OniBus\ChainTrait;
use OniBus\Message;
use OniBus\Test\Fixture\DummyMessage;
use OniBus\Test\Fixture\GenericBusChain;

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

    public function testShouldBusChainThrowsExceptionIfEmpty()
    {
        $this->expectException(InvalidArgumentException::class);
        new BusChain();
    }

    public function testShouldBusChainAcceptOnlyOneChain()
    {
        $tasks = [];
        $chain =
            $this->getMockBuilder(GenericBusChain::class)
                ->setMethods(['before'])
                ->getMock();

        $chain
            ->expects($this->any())
            ->method('before')
            ->willReturnCallback(
                static function () use (&$tasks) {
                    $tasks[] = 100;
                }
            );

        $busChain = new BusChain($chain);
        $busChain->dispatch(new DummyMessage('msg'));

        $this->assertEquals(100, $tasks[0]);
    }

    public function testShouldBusChainExecuteAllBuses()
    {
        $tasks = [];

        $chain1 =
            $this->getMockBuilder(GenericBusChain::class)
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
            $this->getMockBuilder(GenericBusChain::class)
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
            $this->getMockBuilder(GenericBusChain::class)
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
        $busChain->dispatch(new DummyMessage('msg'));

        $this->assertEquals(100, $tasks[0]);
        $this->assertEquals(200, $tasks[1]);
        $this->assertEquals(300, $tasks[2]);
    }
}
