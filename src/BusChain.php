<?php
declare(strict_types=1);

namespace XBus;

use RuntimeException;

class BusChain implements Bus
{
    /**
     * @var Bus[]
     */
    protected $buses = [];

    public function __construct(Bus ...$buses)
    {
        if (empty($buses)) {
            throw new RuntimeException('Bus Chain cannot be empty.');
        }

        foreach ($buses as $bus) {
            $this->append($bus);
        }
    }

    protected function lastBus(): ?Bus
    {
        $endKey = count($this->buses) - 1;
        return $this->buses[$endKey] ?? null;
    }

    protected function append(Bus $bus): void
    {
        $last = $this->lastBus();
        $this->buses[] = $bus;

        if ($last instanceof Chain) {
            $last->setNext($bus);
        }
    }

    public function dispatch(Message $message)
    {
        return $this->buses[0]->dispatch($message);
    }
}
