<?php
declare(strict_types=1);

namespace XBus\Handler;

use Psr\SimpleCache\CacheInterface;
use Psr\SimpleCache\InvalidArgumentException;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use XBus\Attributes\Handler;
use XBus\Exception\UnresolvableMenssageExcpetion;
use XBus\Message;
use XBus\NamedMessage;

class AttributesMapper implements ClassMethodMapper
{
    /**
     * @var array
     */
    protected $map;

    /**
     * @var string
     */
    protected $cacheKey;

    /**
     * @var CacheInterface
     */
    protected $cache;

    public function __construct(array $handlersFQCN, CacheInterface $cache, string $cacheKey)
    {
        $this->cache = $cache;
        $this->cacheKey = $cacheKey;
        $this->map = $this->mapHandlers($handlersFQCN);
    }

    /**
     * @inheritDoc
     */
    public function map(Message $message): ClassMethod
    {
        $name = $message instanceof NamedMessage ? $message->getMessageName() : get_class($message);
        if (!array_key_exists($name, $this->map)) {
            throw UnresolvableMenssageExcpetion::message($message);
        }

        list($class, $method)  = $this->map[$name];
        return new ClassMethod($class, $method);
    }

    /**
     * @param  array $handlersFQCN
     * @return array ['message' => ['class', 'method'], ...]
     * @throws InvalidArgumentException|ReflectionException
     */
    protected function mapHandlers(array $handlersFQCN): array
    {
        if ($this->cache->has($this->cacheKey)) {
            return $this->cache->get($this->cacheKey);
        }

        $mapped = [];
        foreach ($handlersFQCN as $class) {
            $mapped = array_merge($mapped, $this->getHandlers($class));
        }

        if (!empty($mapped)) {
            $this->cache->set($this->cacheKey, $mapped);
        }

        return $mapped;
    }

    /**
     * @param  string $class
     * @return array ['message' => ['class', 'method'], ...]
     * @throws ReflectionException
     */
    protected function getHandlers(string $class): array
    {
        $handlers = [];
        if (!class_exists($class)) {
            return $handlers;
        }

        $reflectionClass = new ReflectionClass($class);
        foreach ($reflectionClass->getMethods() as $method) {
            $attribute = $this->extractHandlerAttribute($method);
            if (empty($attribute)) {
                continue;
            }

            $handlers[$this->extractMessage($method, $attribute)] = [$class, $method->getName()];
        }

        return $handlers;
    }

    protected function extractHandlerAttribute(ReflectionMethod $method): ?Handler
    {
        $attribute = $method->getAttributes(Handler::class, ReflectionAttribute::IS_INSTANCEOF)[0] ?? null;
        if ($attribute instanceof ReflectionAttribute) {
            return $attribute->newInstance();
        }

        return null;
    }

    protected function extractMessage(ReflectionMethod $method, Handler $attribute): string
    {
        $hasParameters = (bool) $method->getNumberOfParameters();
        if (empty($attribute->getMessage()) && !$hasParameters) {
            throw new \RuntimeException(
                sprintf(
                    "Invalid Handler: No 'Message' has been set in %s::%s",
                    $method->getDeclaringClass()->getName(),
                    $method->getName()
                )
            );
        }

        return $attribute->getMessage() ?? (string) $method->getParameters()[0]->getType();
    }
}
