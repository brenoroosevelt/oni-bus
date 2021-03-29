<?php
declare(strict_types=1);

namespace XBus;

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
            $this->buses[$i]->setNext($this->nextChain($i));
        }
    }

    protected function nextChain(int $index): Bus
    {
        return $this->buses[$index + 1] ?? $this->emptyBus();
    }

    protected function emptyBus(): Bus
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
