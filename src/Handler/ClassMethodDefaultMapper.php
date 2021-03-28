<?php
declare(strict_types=1);

namespace XBus\Handler;

use XBus\Message;
use XBus\NamedMessage;

class ClassMethodDefaultMapper implements ClassMethodMapper
{
    /**
     * @var array
     */
    protected $map;

    /**
     * @param array $map ['message' => 'class', ...]
     * @param string $method
     */
    public function __construct(array $map, string $method = "__invoke")
    {
        foreach ($map as $message => $class) {
            if (!method_exists($class, $method)) {
                continue;
            }

            $this->map[] = new ClassMethod($message, $class, $method);
        }
    }

    public function map(Message $message): array
    {
        $name = $message instanceof NamedMessage ? $message->getMessageName() : get_class($message);
        return array_filter($this->map, function ($item) use ($name) {
            return $item->message() === $name;
        });
    }
}
