<?php
declare(strict_types=1);

namespace OniBus\Handler;

use OniBus\Message;
use OniBus\NamedMessage;

class ClassMethodArrayMapper implements ClassMethodMapper
{
    /**
     * @var array
     */
    protected $map;

    /**
     * @param array $map [['message', 'class', 'method'], ...]
     */
    public function __construct(array $map)
    {
        $this->map =
            array_map(
                function ($item) {
                    list($message, $class, $method) = $item;
                    return new ClassMethod($message, $class, $method);
                },
                $map
            );
    }

    public function map(Message $message): array
    {
        $name = $message instanceof NamedMessage ? $message->getMessageName() : get_class($message);
        return array_filter($this->map, function ($item) use ($name) {
            return $item->message() === $name;
        });
    }
}
