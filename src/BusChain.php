<?php
declare(strict_types=1);

namespace OniBus;

use RuntimeException;

class BusChain implements Bus
{
    /**
     * @var Chain[]
     */
    protected $buses = [];

    public function __construct(Chain ...$buses)
    {
        if (empty($buses)) {
            throw new RuntimeException('Bus Chain cannot be empty.');
        }

        $this->buses = $buses;
        for ($i = 0; $i < count($buses); $i++) {
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
