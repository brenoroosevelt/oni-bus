<?php
declare(strict_types=1);

namespace XBus;

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
     * @param  Message $message
     * @return mixed
     */
    protected function next(Message $message)
    {
        if ($this->nextBus instanceof Bus) {
            return $this->nextBus->dispatch($message);
        }

        return null;
    }
}
