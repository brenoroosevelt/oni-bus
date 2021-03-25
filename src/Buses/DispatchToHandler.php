<?php
declare(strict_types=1);

namespace XBus\Buses;

use XBus\Bus;
use XBus\Chain;
use XBus\ChainTrait;
use XBus\Handler\HandlerResolver;
use XBus\Message;

class DispatchToHandler implements Bus, Chain
{
    use ChainTrait;

    /**
     * @var HandlerResolver
     */
    protected $handlerResolver;

    public function __construct(HandlerResolver $handlerResolver)
    {
        $this->handlerResolver = $handlerResolver;
    }

    public function dispatch(Message $message)
    {
        $handler = $this->handlerResolver->resolve($message);
        $result = $handler($message);

        if ($this->hasNext()) {
            return $this->next($message);
        }

        return $result;
    }
}
