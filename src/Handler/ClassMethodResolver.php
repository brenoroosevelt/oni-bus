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
        $classMethods = $this->mapper->map($message);
        $fn = function () {
        };

        foreach ($classMethods as $classMethod) {
            $fn = function (Message $message) use ($fn, $classMethod) {
                $fn($message);
                $class = $this->container->get($classMethod->class());
                return call_user_func_array([$class, $classMethod->method()], [$message]);
            };
        }

        return $fn;
    }
}
