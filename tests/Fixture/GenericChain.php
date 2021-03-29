<?php
declare(strict_types=1);

namespace OniBus\Test\Fixture;

use OniBus\Chain;
use OniBus\ChainTrait;
use OniBus\Message;

abstract class GenericChain implements Chain
{
    use ChainTrait;

    public function dispatch(Message $message)
    {
        $this->before();
        $this->next($message);
        $this->after();
    }

    public function before()
    {
    }

    public function after()
    {
    }
}
