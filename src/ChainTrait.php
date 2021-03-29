<?php
declare(strict_types=1);

namespace OniBus;

use RuntimeException;

trait ChainTrait
{
    /**
     * @var Bus|null
     */
    protected $nextBus = null;

    public function setNext(Bus $bus)
    {
        $this->nextBus = $bus;
    }

    /**
     * @param Message $message
     * @return mixed
     * @throws RuntimeException
     */
    protected function next(Message $message)
    {
        if (!$this->hasNext()) {
            throw new RuntimeException(
                sprintf("[%s] The next Bus was not defined in the chain.", get_class($this))
            );
        }

        return $this->nextBus->dispatch($message);
    }

    protected function hasNext(): bool
    {
        return $this->nextBus instanceof Bus;
    }
}
