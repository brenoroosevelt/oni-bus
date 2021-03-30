<?php
declare(strict_types=1);

namespace OniBus\Handler;

use OniBus\Message;
use OniBus\NamedMessage;

class ClassMethodDefaultMapper implements ClassMethodMapper
{
    /**
     * @var array
     */
    protected $map = [];

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
        $filtered = array_filter($this->map, function (ClassMethod $classMethod) use ($name) {
            return $classMethod->message() === $name;
        });

        return array_values($filtered);
    }
}
