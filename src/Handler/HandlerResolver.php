<?php
declare(strict_types=1);

namespace XBus\Handler;

use Closure;
use XBus\Exception\UnresolvableMenssageExcpetion;
use XBus\Message;

interface HandlerResolver
{
    /**
     * @param  Message $message
     * @return Closure
     * @throws UnresolvableMenssageExcpetion
     */
    public function resolve(Message $message): Closure;
}
