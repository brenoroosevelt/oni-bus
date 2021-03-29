<?php
declare(strict_types=1);

namespace OniBus\Buses;

use OniBus\Chain;
use OniBus\ChainTrait;
use OniBus\Message;

class ProtectOrder implements Chain
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
