<?php
declare(strict_types=1);

namespace XBus\Buses;

use XBus\Bus;
use XBus\Chain;
use XBus\ChainTrait;
use XBus\Message;

class ProtectOrder implements Bus, Chain
{
    use ChainTrait;

    /**
     * @var array
     */
    protected $queue;

    /**
     * @var bool
     */
    protected $isDispatching = false;

    public function dispatch(Message $message)
    {
        $this->queue[] = $message;
        $lastResult = null;

        if (!$this->isDispatching) {
            $this->isDispatching = true;

            while ($message = array_shift($this->queue)) {
                $lastResult = $this->next($message);
            }

            $this->isDispatching = false;
        }

        return $lastResult;
    }
}
