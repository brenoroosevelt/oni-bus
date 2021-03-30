<?php
declare(strict_types=1);

namespace OniBus\Handler;

use Closure;
use OniBus\Exception\UnresolvableMessageException;
use OniBus\Message;

interface HandlerResolver
{
    /**
     * @param  Message $message
     * @return Closure
     * @throws UnresolvableMessageException
     */
    public function resolve(Message $message): Closure;
    public function canResolve(Message $message): bool;
}
