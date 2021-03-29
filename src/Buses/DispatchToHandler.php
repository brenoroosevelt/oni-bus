<?php
declare(strict_types=1);

namespace OniBus\Buses;

use OniBus\Chain;
use OniBus\ChainTrait;
use OniBus\Handler\HandlerResolver;
use OniBus\Message;

class DispatchToHandler implements Chain
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
        return $handler($message);
    }
}
