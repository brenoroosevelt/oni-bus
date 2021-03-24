<?php
declare(strict_types=1);

namespace XBus\Handler;

use XBus\Exception\UnresolvableMenssageExcpetion;
use XBus\Message;
use XBus\NamedMessage;

class ArrayMapper implements ClassMethodMapper
{
    /**
     * @var array
     */
    protected $map;

    /**
     * @param array $map ['messageFQN' => ['classFQN', 'method'], ...]
     */
    public function __construct(array $map)
    {
        $this->map = array_filter(
            $map,
            function ($item) {
                list($class, $method)  = $item;
                return method_exists($class, $method);
            }
        );
    }

    public function map(Message $message): ClassMethod
    {
        $name = $message instanceof NamedMessage ? $message->getMessageName() : get_class($message);
        if (!array_key_exists($name, $this->map)) {
            throw UnresolvableMenssageExcpetion::message($message);
        }

        list($class, $method)  = $this->map[$name];
        return new ClassMethod($class, $method);
    }
}
