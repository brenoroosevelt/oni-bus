<?php
declare(strict_types=1);

namespace OniBus\Handler;

use Psr\SimpleCache\CacheInterface;
use Psr\SimpleCache\InvalidArgumentException;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use OniBus\Attributes\Handler;
use OniBus\Message;
use OniBus\NamedMessage;
use RuntimeException;

class ClassMethodAttributesMapper implements ClassMethodMapper
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
    /**
     * @var string
     */
    private $attribute;

    public function __construct(array $handlersFQCN, string $attribute, CacheInterface $cache, string $cacheKey)
    {
        $this->attribute = $attribute;
        $this->cache = $cache;
        $this->cacheKey = $cacheKey;
        $this->map = $this->mapHandlers($handlersFQCN);
    }

    /**
     * @inheritDoc
     */
    public function map(Message $message): array
    {
        $name = $message instanceof NamedMessage ? $message->getMessageName() : get_class($message);
        return array_filter($this->map, function ($item) use ($name) {
            return $item->message() === $name;
        });
    }

    /**
     * @param  array $handlersFQCN
     * @return ClassMethod[]
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
     * @return ClassMethod[]
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

            $handlers[] = new ClassMethod($this->extractMessage($method, $attribute), $class, $method->getName());
        }

        return $handlers;
    }

    protected function extractHandlerAttribute(ReflectionMethod $method): ?Handler
    {
        $attribute = $method->getAttributes($this->attribute, ReflectionAttribute::IS_INSTANCEOF)[0] ?? null;
        if ($attribute instanceof ReflectionAttribute) {
            $attr =  $attribute->newInstance();
            assert($attr instanceof Handler);
            return $attr;
        }

        return null;
    }

    protected function extractMessage(ReflectionMethod $method, Handler $attribute): string
    {
        $hasParameters = (bool) $method->getNumberOfParameters();
        if (empty($attribute->getMessage()) && !$hasParameters) {
            throw new RuntimeException(
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
