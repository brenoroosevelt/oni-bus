<?php
declare(strict_types=1);

namespace XBus\Handler;

use Closure;
use Psr\Container\ContainerInterface;
use XBus\Message;

class ClassMethodResolver implements HandlerResolver
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var ClassMethodMapper
     */
    private $mapper;

    public function __construct(ContainerInterface $container, ClassMethodMapper $mapper)
    {
        $this->container = $container;
        $this->mapper = $mapper;
    }

    public function resolve(Message $message): Closure
    {
        $classMethod = $this->mapper->map($message);
        return function (Message $message) use ($classMethod) {
            $handler = $this->container->get($classMethod->class());
            return call_user_func_array([$handler, $classMethod->method()], [$message]);
        };
    }
}
