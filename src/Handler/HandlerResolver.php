<?php
declare(strict_types=1);

namespace OniBus\Handler;

use Closure;
use OniBus\Exception\UnresolvableMenssageExcpetion;
use OniBus\Message;

interface HandlerResolver
{
    /**
     * @param  Message $message
     * @return Closure
     * @throws UnresolvableMenssageExcpetion
     */
    public function resolve(Message $message): Closure;
    public function canResolve(Message $message): bool;
}
