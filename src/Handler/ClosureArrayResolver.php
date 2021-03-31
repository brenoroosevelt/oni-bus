<?php
declare(strict_types=1);

namespace OniBus\Handler;

use Closure;
use OniBus\Exception\UnresolvableMessageException;
use OniBus\Message;
use OniBus\NamedMessage;

class ClosureArrayResolver implements HandlerResolver
{
    /**
     * @var array
     */
    protected $map;

    /**
     * @param array $map ['message' => function() {} ]
     */
    public function __construct(array $map)
    {
        $this->map = array_filter($map, function ($item) {
            return $item instanceof Closure;
        });
    }

    public function resolve(Message $message): Closure
    {
        if (!array_key_exists($this->getMessageName($message), $this->map)) {
            throw UnresolvableMessageException::message($message);
        }

        return $this->map[$this->getMessageName($message)];
    }

    protected function getMessageName(Message $message)
    {
        return $message instanceof NamedMessage ? $message->getMessageName() : get_class($message);
    }
}
