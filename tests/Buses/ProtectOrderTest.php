<?php
declare(strict_types=1);

namespace OniBus\Test\Buses;

use Closure;
use OniBus\Buses\ProtectOrder;
use OniBus\Chain;
use OniBus\ChainTrait;
use OniBus\Message;
use OniBus\Test\Fixture\GenericMessage;
use OniBus\Test\TestCase;

class ProtectOrderTest extends TestCase
{
    public function testShouldProtectOrderOfExecution()
    {
        $original = new GenericMessage();
        $newMessage = new GenericMessage();
        $execution = [];

        $protectOrder = new ProtectOrder();

        $fn = function (Message $message) use ($protectOrder, $original, $newMessage, &$execution) {
            if ($message === $original) {
                $execution[] = 'start-original';
                $protectOrder->dispatch($newMessage);
                $execution[] = 'finish-original';
            }
            if ($message === $newMessage) {
                $execution[] = 'start-new';
                $execution[] = 'finish-new';
            }
        };

        $nextChain = new class ($fn) implements Chain {

            use ChainTrait;

            /**
             * @var Closure
             */
            protected $fn;

            public function __construct(Closure $fn)
            {
                $this->fn = $fn;
            }

            public function dispatch(Message $message)
            {
                ($this->fn)($message);
            }
        };

        $protectOrder->setNext($nextChain);

        $protectOrder->dispatch($original);

        $this->assertEquals([
            'start-original',
            'finish-original',
            'start-new',
            'finish-new'
        ], $execution);
    }
}
