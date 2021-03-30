<?php
declare(strict_types=1);

namespace OniBus;

use InvalidArgumentException;

class BusChain implements Bus
{
    /**
     * @var Chain[]
     */
    protected $buses = [];

    public function __construct(Chain ...$buses)
    {
        if (empty($buses)) {
            throw new InvalidArgumentException('Bus Chain cannot be empty.');
        }

        $this->buses = $buses;
        $count = count($buses);
        for ($i = 0; $i < $count; $i++) {
            $nextBus = $this->buses[$i + 1] ?? $this->dummyBus();
            $this->buses[$i]->setNext($nextBus);
        }
    }

    protected function dummyBus(): Bus
    {
        return new class implements Bus {
            public function dispatch(Message $message)
            {
            }
        };
    }

    public function dispatch(Message $message)
    {
        return $this->buses[0]->dispatch($message);
    }
}
